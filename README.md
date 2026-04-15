# Netgen Layouts integration with Sylius CMS plugin

Integrates [Netgen Layouts](https://netgen.io/services/netgen-layouts) with
[`sylius/cms-plugin`](https://github.com/Sylius/CmsPlugin), providing content browser
backends, layout resolver targets, query types, value converters and block
definitions for the plugin's `Page`, `Block`, `Media` and `Collection` entities.

For Sylius 1.x with the BitBag CMS plugin, use
[`netgen/layouts-sylius-bitbag`](https://github.com/netgen-layouts/layouts-sylius-bitbag)
instead.

## Installation

### 1. Require the bundle

```bash
composer require netgen/layouts-sylius-cms
```

This also pulls `sylius/cms-plugin`, `netgen/layouts-sylius`, and the rest of the
Netgen Layouts stack as transitive dependencies. Symfony Flex registers the bundle
and other Netgen Layouts / Sylius CMS bundles in `config/bundles.php` automatically.

### 2. Configure Netgen Layouts

If Netgen Layouts is **already installed and configured** in your Sylius app (e.g.
from an existing `netgen/layouts-sylius` install), step 1 is the whole install.

If this is your first Netgen Layouts bundle in the project, you also need:

**Security** (`config/packages/security.yaml` — extends the admin firewall to cover
Layouts routes, and grants every Sylius admin user full Layouts permissions):

```yaml
parameters:
    sylius.security.admin_regex: "^(/%sylius_admin.path_name%|/nglayouts/app|/nglayouts/api|/nglayouts/admin|/cb)"

security:
    role_hierarchy:
        ROLE_ADMINISTRATION_ACCESS: ['ROLE_NGLAYOUTS_ADMIN']
```

**Framework** (`config/packages/framework.yaml`):

```yaml
framework:
    esi: true
    fragments: true
```

**Pagelayout fallback template** (`config/packages/netgen_layouts.yaml`):

```yaml
netgen_layouts:
    pagelayout: templates/shop/layout.html.twig
```

### 3. Run migrations

Sylius CMS plugin migrations:

```bash
bin/console doctrine:migrations:migrate
```

Netgen Layouts core migrations ship in a separate configuration file and are not
auto-discovered:

```bash
bin/console doctrine:migrations:migrate \
    --configuration=vendor/netgen/layouts-core/migrations/doctrine.yaml
```
