# Netgen Layouts integration with Sylius CMS plugin

Integrates [Netgen Layouts](https://netgen.io/services/netgen-layouts) with
[`sylius/cms-plugin`](https://github.com/Sylius/CmsPlugin), providing content browser
backends, layout resolver targets, query types, value converters and block
definitions for the plugin's `Page`, `Block`, `Media` and `Collection` entities.

For Sylius 1.x with the BitBag CMS plugin, use
[`netgen/layouts-sylius-bitbag`](https://github.com/netgen-layouts/layouts-sylius-bitbag)
instead.

## Installation

```bash
composer require netgen/layouts-sylius-cms
```

Symfony Flex will automatically enable the bundle.
