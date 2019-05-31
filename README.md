# Template loader ![StyleCI](https://github.styleci.io/repos/189646638/shield?branch=master)

A helper class to load template files from another location

## Installation

`$ composer require ralfhortt/wp-template-loader`

## Usage

```php
<?php
use RalfHortt\TemplateLoader\TemplateLocator;

// Will load WordPress front page from `views/front-page.php`
(new TemplateLocator('views'))->register();
```

## More Information about the WordPress hierarchy

[https://wphierarchy.com/](https://wphierarchy.com/)
