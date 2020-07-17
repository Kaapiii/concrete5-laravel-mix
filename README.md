# Loading easily versioned laravel mix assets in concrete5.

Provides wrapper methods for loading easily versioned laravel mix assets in concrete5 packages (and themes).

## Installation

1. Require libray with composer

```bash
    composer require kaapiii/concrete5-laravel-mix
```

## How does it work

Let's assume your mix-manifest.json contains following assets:

```json
{
    "/dist/css/main.css": "/dist/css/main.css?id=fd593dbed17d9fd9391f",
    "/dist/js/main.js": "/dist/js/main.js?id=c833b937dc546bf8c034"
}
```

By adding the following snippet to your head.php or footer.php or any php file...
```php
<?php $mix->printAsset('/dist/css/main.css') ?>
<?php $mix->printAsset('/dist/js/main.js') ?>
```

we get the following output (with cache busting).
```html
<link type="text/css" rel="stylesheet" href="/dist/css/main.css?id=fd593dbed17d9fd9391f" />
<script src="/dist/js/main.js?id=c833b937dc546bf8c034"></script>
```
