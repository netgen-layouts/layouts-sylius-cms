# Port: BitBag CMS → Sylius CMS Plugin (SPLAT-4203)

This document is the source of truth for the port from `netgen/layouts-sylius-bitbag`
to `netgen/layouts-sylius-cms`. It explains **what changed** and **why**, in enough
detail that the work can be understood, audited, or extended without having to dig
through commits or the diff.

---

## 1. Background

The `bitbag/sylius-cms-plugin` package was the de-facto CMS for Sylius 1.x. With
Sylius 2.x, Sylius released its own first-party `sylius/cms-plugin`, and the BitBag
plugin was discontinued. This bundle, which integrated Netgen Layouts with the BitBag
plugin, had to be ported to integrate with the new official plugin.

The plugins are similar in spirit but **not** drop-in compatible. Their entity models,
service IDs, route names, event system, and Twig integration all differ.

---

## 2. Entity Model Differences

The single most important thing to internalise is how the entity model changed,
because nearly every other change is downstream of this.

| BitBag | Sylius CMS | Notes |
|---|---|---|
| `Section` + `SectionTranslation` | `Collection` (NOT translatable) | `name` lives directly on the entity; no translation joins |
| `FrequentlyAskedQuestion` (+ translation) | **REMOVED** | Sylius CMS does not have this entity at all |
| `Page` with translatable `content` string | `Page` with `ContentConfiguration` elements | Content is now structured (an array of typed elements) instead of a single HTML string |
| `Block` with translatable `name`, `link`, `content` | `Block` with non-translatable `name`, no `link`, translatable `content` | Simplified |
| `Media` (translatable) | `Media` (translatable) | Same shape |

Two entity-model decisions drove most of the work:

1. **Section → Collection rename**, including the loss of translations. Anything
   that joined `section.translations` had to be rewritten to query `collection.name`
   directly. The user-facing entity name also became "Collection".
2. **Page content shape change** — from a single `content` string field to a
   structured `ContentConfiguration` of typed elements (text, image, etc.). This
   broke the `EntityField` block's content view, which used to dump
   `{{ entity.content|raw }}`.

### 2.1 Entity classes (canonical)

| Entity | Interface | Repository Interface |
|---|---|---|
| Page | `Sylius\CmsPlugin\Entity\PageInterface` | `Sylius\CmsPlugin\Repository\PageRepositoryInterface` |
| Block | `Sylius\CmsPlugin\Entity\BlockInterface` | `Sylius\CmsPlugin\Repository\BlockRepositoryInterface` |
| Media | `Sylius\CmsPlugin\Entity\MediaInterface` | `Sylius\CmsPlugin\Repository\MediaRepositoryInterface` |
| Collection | `Sylius\CmsPlugin\Entity\CollectionInterface` | `Sylius\CmsPlugin\Repository\CollectionRepositoryInterface` |

---

## 3. Service / Route / Event Mapping

| What | BitBag | Sylius CMS |
|---|---|---|
| Repo services | `bitbag_sylius_cms_plugin.repository.*` | `sylius_cms.repository.*` |
| Admin route — page | `bitbag_sylius_cms_plugin_admin_page_update` | `sylius_cms_admin_page_update` |
| Admin route — block | `bitbag_sylius_cms_plugin_admin_block_update` | `sylius_cms_admin_block_update` |
| Admin route — media | `bitbag_sylius_cms_plugin_admin_media_update` | `sylius_cms_admin_media_update` |
| Admin route — collection | `bitbag_sylius_cms_plugin_admin_section_update` | `sylius_cms_admin_collection_update` |
| Shop page show | `bitbag_sylius_cms_plugin_shop_page_show` | `sylius_cms_shop_page_show` |
| Collection index | `bitbag_sylius_cms_plugin_shop_page_index_by_section_code` | `sylius_cms_shop_collections_page_index` |
| Events | `bitbag_sylius_cms_plugin.page.show/index` | None — use `kernel.request` |
| Twig render | `bitbag_cms_render_content()` | `sylius_cms_render_content_elements()` |
| Templates | `@BitBagSyliusCmsPlugin/...` | `@SyliusCmsPlugin/...` |

Key consequence: Sylius CMS does **not** dispatch named events the way BitBag did, so
all listeners that previously hooked BitBag events were rewritten as `kernel.request`
listeners that match on route names.

---

## 4. Phases of Work

The port was deliberately broken into phases so each commit is small and
self-contained.

| Phase | What |
|---|---|
| 1 | Remove FAQ |
| 2 | Rename Section → Collection everywhere |
| 3 | Repository rewrites (parent service + locale + search) |
| 4 | Event listeners: BitBag events → `kernel.request` |
| 5 | Route name updates |
| 6 | Twig template references |
| 7 | EntityField content view adapted to ContentElements |
| 8 | SortingTrait rewrite (no more `translation.*` paths) |
| 9 | Admin / standard item templates rewritten |
| 10 | Service-config fix (`type` → `parameter_type`) |
| 11 | Tests added, redundant docblocks removed |

### 4.1 Phase 1 — Remove FAQ

Sylius CMS has no FAQ entity. Anything that referenced `FrequentlyAskedQuestion`
(item type, content browser backend, value loader, value converter, item URL
generator, parameter type, target type, query type handler, repository) was deleted.

This is a **pure deletion** phase — no replacements. The FAQ class hierarchy is gone
end-to-end, with no orphan service registrations or dead tags.

### 4.2 Phase 2 — Section → Collection

Mechanical rename, but with a wrinkle: Collection is not translatable. In addition to
class renames, this phase:

- Dropped `SectionTranslation` joins from the repository.
- Replaced `section.translations.name` query usages with `collection.name`.
- Renamed item types in service tags: `sylius_cms_section` → `sylius_cms_collection`,
  `sylius_cms_section_page` → `sylius_cms_collection_page`.
- Updated parameter-type identifiers and validator names.
- Renamed test fixtures (`tests/_fixtures/data.php`) to use `sylius_cms_collection`
  and `sylius_cms_collection_page` rule-target type IDs.

No `Section` references remain in code identifiers.

### 4.3 Phase 3 — Repository rewrites

Each repository (`PageRepository`, `BlockRepository`, `CollectionRepository`,
`MediaRepository`) extends the corresponding Sylius CMS plugin repository. Specific
points:

- Service definitions use `parent: sylius_cms.repository.<entity>` so they inherit
  the EntityRepository constructor wiring from the plugin's resource configuration.
- `BlockRepository::createListQueryBuilder()` keeps the `string $localeCode`
  parameter from the parent for LSP compatibility, even though the Block entity is
  not translatable on `name`. The locale is unused in the body.
- `BlockRepository` no longer joins translations for searching (Block name isn't
  translatable in Sylius CMS).
- Search queries hit non-translatable fields (`name` on Collection/Block, `name` on
  Media) directly instead of going through translation joins.

Every `createSearchPaginator` builds its `LIKE` clause against columns that actually
exist in the Sylius CMS schema.

### 4.4 Phase 4 — Event listeners

BitBag dispatched named events (`bitbag_sylius_cms_plugin.page.show`, etc.) that we
listened to in order to populate request attributes like
`nglayouts_sylius_cms_collection`. Sylius CMS does **not** dispatch named events.

The fix: switch to `kernel.request` listeners that match on the route name
(`sylius_cms_shop_page_show`, `sylius_cms_shop_collections_page_index`) and load the
entity from the route parameters. The listeners populate the same request attributes
the rest of the bundle relies on.

Properties of the new listeners:

- They short-circuit cheaply on routes they don't care about.
- They use the repository (already a service) rather than entity-manager lookups.
- They run on the main request only, not sub-requests, so ESI fragments don't
  re-trigger entity loads.

### 4.5 Phase 5 — Route names

Sylius CMS routes have different names (see §3). All `path()` / `url()` Twig calls,
all PHP `UrlGenerator->generate()` calls, and all event-listener route checks were
updated. No `bitbag_sylius_cms_plugin_*` route string survives.

### 4.6 Phase 6 — Twig template references

Two changes:

- `@BitBagSyliusCmsPlugin/...` template references → `@SyliusCmsPlugin/...`.
- `bitbag_cms_render_content(entity)` Twig function (BitBag-supplied) →
  `sylius_cms_render_content_elements(entity)` (Sylius CMS-supplied).

The rendering function is meaningfully different — see §4.7.

### 4.7 Phase 7 — EntityField content view

`EntityField` is a block that renders one field of a CMS entity (e.g. "the Page's
content"). On BitBag this was straightforward because `content` was a translatable
string and you could echo it raw.

On Sylius CMS, Page (and other content-bearing entities) implement
`ContentElementsAwareInterface`, and the content is an array of typed elements. The
render path is now:

```twig
{{ sylius_cms_render_content_elements(entity) }}
```

This was the most surgical step in the port. The `EntityField` handler had to detect
the field type (string, boolean, content-elements, media) and the content view
template had to render each appropriately. New tests in
`tests/lib/Block/BlockDefinition/Handler/EntityFieldTest.php` cover all four
branches.

Properties of the new handler:

- It matches on the *field type discovered via reflection*, not on a hardcoded list
  of field names.
- `ContentElementsAwareInterface` is preferred over the older `ContentableInterface`
  (which doesn't exist in Sylius CMS).

### 4.8 Phase 8 — SortingTrait

Old code in `Collection/QueryType/Handler/Traits/SortingTrait.php` had a branch:

```php
if (str_starts_with($sortField, 'translation.')) {
    // join translations alias and ORDER BY translation.<field>
}
```

This was needed when sorting by translatable fields (e.g. Section name). Since
Collection name and Block name are no longer translatable, no caller passes a
`translation.*` field anymore. The branch was dead code and was removed. The unused
`in_array` and `str_starts_with` function imports went with it.

`SortingTrait::applySorting` now only handles plain field names against the root
alias.

### 4.9 Phase 9 — Item templates

Two batches:

- **Admin item templates** (`bundle/Resources/views/nglayouts/themes/admin/app/item/*`):
  switched to `nglayouts_item_admin_path()` (the supported helper) and removed
  references to `page.image` (Sylius CMS Page has no `image` accessor).
- **Standard item templates** (`bundle/Resources/views/nglayouts/themes/standard/item/*`):
  for Page, dropped the `<img>` from page image and the inline `entity.content` echo
  (content is now structured); for Block, render via the bundled
  `sylius_cms_render_block(block)` helper.

Templates no longer reach for fields that don't exist on the new entities.
Specifically `page.image`, `page.content` (string), and `block.link` are gone.

### 4.10 Phase 10 — Service-config fix

`Symfony\Component\Form\FormInterface` mappers register against parameter types via
a tag attribute that, in Layouts 1.x, was `type`. In Layouts 2.x it was renamed to
`parameter_type`. The tag attribute on
`netgen_layouts.sylius.cms.parameters.form_mapper.*` had to be updated. Without this,
the form mapper silently doesn't bind and the parameter renders as a generic text
field in the admin.

This was caught during manual UI testing — there is no compile-time error. Every
form-mapper service tag now uses `parameter_type:` (not `type:`).

### 4.11 Phase 11 — Tests + final cleanup

Three new test classes:

- `tests/lib/Browser/Backend/BlockBackendTest.php`
- `tests/lib/Browser/Backend/MediaBackendTest.php`
- `tests/lib/Block/BlockDefinition/Handler/EntityFieldTest.php`

These cover backends and the EntityField handler that the port touched. They follow
the same pattern as the existing `PageBackendTest` and `CollectionBackendTest`: stub
the repository interface, exercise each public method, assert types/counts.

The "redundant docblocks" cleanup removed PHPDoc that only restated what the method
signature already said — kept only docblocks that add real information (e.g. the
generic `@return Pagerfanta<Entity>` annotations, which **are** load-bearing because
they cannot be expressed natively).

---

## 5. Final Bugfixes (from pre-merge review)

A pre-merge review surfaced five blockers; all were fixed in one commit:

1. **Fixture data** still referenced `sylius_cms_section` / `sylius_cms_section_page`
   in `tests/_fixtures/data.php`. Tests would silently match zero rows. Renamed.
2. **`tests/lib/Layout/Resolver/TargetHandler/Doctrine/CollectionTest.php`** was
   copy-pasted from `PageTest.php` and still had `#[CoversClass(Page::class)]`.
   Updated to cover `Collection`.
3. **Service-ID typo** `netgen_layouts.sylius.cms..block...` (double dot). Removed
   the extra dot.
4. **PSR-4 violation** in `tests/lib/ContentBrowser/...` — directories had been moved
   to `tests/lib/Browser/` but namespaces still said `ContentBrowser`. Bulk namespace
   rename.
5. **`datetime.html.twig`** missing `class="`: rendered `<div field field-...>`
   (broken HTML). Added the attribute name.

---

## 6. Architecture (post-port)

```
lib/                    # Library code (no Symfony bundle dependency)
  Browser/              # Content browser backends and items
  Collection/           # Query type handlers
  Item/                 # Value loaders, converters, URL generators
  Layout/               # Layout resolver (target types, handlers)
  Block/                # Block definitions
  Parameters/           # Parameter types
  Repository/           # Repository interfaces and implementations
  Validator/            # Constraint validators
bundle/                 # Symfony bundle (DI, config, templates, listeners)
  EventListener/        # Kernel event listeners
  Resources/config/     # YAML service and view configuration
  Resources/views/      # Twig templates
  Templating/           # Twig extensions and runtimes
tests/                  # Mirrors lib/ and bundle/ structure
```

### 6.1 Service ID convention

All services use the prefix `netgen_layouts.sylius.cms.`. Examples:

- `netgen_layouts.sylius.cms.repository.page`
- `netgen_layouts.sylius.cms.browser.backend.page`
- `netgen_layouts.sylius.cms.item.value_loader.page`

### 6.2 Repository services

Repositories extend Sylius CMS parent services:

```yaml
netgen_layouts.sylius.cms.repository.page:
    class: Netgen\Layouts\Sylius\Cms\Repository\PageRepository
    parent: sylius_cms.repository.page
```

### 6.3 Content browser backends

Tagged with `netgen_content_browser.backend`:

```yaml
netgen_layouts.sylius.cms.browser.backend.page:
    class: Netgen\Layouts\Sylius\Cms\Browser\Backend\PageBackend
    tags:
        - { name: netgen_content_browser.backend, item_type: sylius_cms_page }
```

### 6.4 Code conventions in force

- `declare(strict_types=1);` in every PHP file.
- PHPStan Level 8 — zero errors required.
- `final` on all classes unless explicitly designed for extension.
- `readonly` on injected constructor dependencies.
- Constructor property promotion always.
- Native function calls: `\count()`, `\strlen()`, `\sprintf()` (backslash prefix).
- No Yoda comparisons.
- Trailing commas in multiline arrays/arguments/parameters.
- PHP 8.4: use `match`, first-class callables, union/intersection types, named
  arguments.
- Docblocks only when they carry information not expressible natively (e.g. generic
  `@return` types). No restating the signature in prose.
- YAML for service configuration; PHP attributes (`#[AsEventListener]`,
  `#[Autowire]`) deferred to a future cleanup.

---

## 7. Installation in a Sylius 2.x App

After requiring the package, the following setup steps are needed:

### 7.1 Security — extend admin regex and grant Layouts role

Add to `config/packages/security.yaml`:

```yaml
parameters:
    sylius.security.admin_regex: "^(/%sylius_admin.path_name%|/nglayouts/app|/nglayouts/api|/nglayouts/admin|/cb)"

security:
    role_hierarchy:
        ROLE_ADMINISTRATION_ACCESS: ['ROLE_NGLAYOUTS_ADMIN']
```

The regex allows the admin firewall to cover Netgen Layouts routes. The role
hierarchy grants every Sylius admin user full Netgen Layouts permissions (editing
layouts, mappings, groups, etc.).

### 7.2 Database — run Netgen Layouts migrations

Netgen Layouts ships its own Doctrine migrations with a separate config. They are
NOT auto-discovered by the app's `doctrine_migrations.yaml`. Run them explicitly:

```bash
bin/console doctrine:migrations:migrate --configuration=vendor/netgen/layouts-core/migrations/doctrine.yaml
```

This creates the `nglayouts_*` tables and tracks versions in
`nglayouts_migration_versions` (separate from Sylius's `sylius_migrations` table).

### 7.3 Framework — enable ESI and fragments

```yaml
# config/packages/framework.yaml
framework:
    esi: true
    fragments: true
```

### 7.4 Pagelayout — configure fallback template

```yaml
# config/packages/netgen_layouts.yaml
netgen_layouts:
    pagelayout: templates/shop/layout.html.twig
```

---

## 8. Verification

### 8.1 Static analysis

```bash
composer phpstan        # level 8 on lib/, bundle/
composer phpstan-tests  # level 8 on tests/
```

Both must report **zero errors**.

### 8.2 Code style

```bash
php-cs-fixer fix --dry-run --diff
```

Must report **zero files** that need fixing.

### 8.3 Unit tests

```bash
composer test
```

### 8.4 Manual smoke test

1. Apply the installation steps in §7.
2. Create a CMS Page in Sylius admin. Add some content elements.
3. Create a Layout in Netgen Layouts admin. Add a Sylius CMS Page block.
4. Use the content browser to pick the page. Verify the picker lists pages.
5. Render the layout on the storefront. Verify the page content renders via
   `sylius_cms_render_content_elements()`.
6. Repeat for Block, Media, Collection.
7. Test the EntityField block: render a single field of a Page, both a string field
   (e.g. `name`) and the `content` field (ContentElements).

---

## 9. Things This Port Deliberately Does Not Do

- **No PHP attributes for service config.** Sylius and Netgen Layouts both still ship
  YAML-first DI in this codebase. Migrating to `#[AsEventListener]` / `#[Autowire]`
  is a separate, future cleanup.
- **No new abstractions.** Where a single repository class fits, there is no
  abstract base. Where a one-off listener works, there's no factory. The port
  preserves the original architecture.
- **No backwards compatibility with BitBag.** This bundle targets
  `sylius/cms-plugin` exclusively. Users on BitBag stay on
  `netgen/layouts-sylius-bitbag` (Sylius 1.x).
- **No FAQ replacement.** If you need FAQ, build it as a custom CMS Page template or
  use a separate FAQ bundle. We did not invent a substitute entity.
