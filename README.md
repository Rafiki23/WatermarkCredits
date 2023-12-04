
# WatermarkCredits

WatermarkCredits is a Laravel package for easily applying watermarks with names/logos and author information on images.

## Installation

Install the package via composer:

```bash
composer require rafiki23/watermarkcredits
```

Add the service provider to your `config/app.php` file:

```php
'providers' => [
    // Other Service Providers

    Rafiki23\WatermarkCredits\WatermarkServiceProvider::class,
],
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Rafiki23\WatermarkCredits\WatermarkServiceProvider" --tag="config"
```

## Configuration

After publishing the configuration file, it is located at `config/watermark.php`. You can modify it to set your default configurations.

```php
return [
    'opacity' => 0.6,
    'text_color' => '#ffffff',
    'font_size' => 24,
    'default_logo_path' => 'path/to/your/logo.png',
    'logo_text' => 'Default Logo Text',
    // other configurations...
];
```

## Usage

Here's how you can use the WatermarkCredits package:

```php
use Rafiki23\WatermarkCredits\Watermark;

$watermark = new Watermark();

// Apply watermark to an image
$watermark->applyWatermark('path/to/image.jpg', 'Your Watermark Text')->save('path/to/output/image.jpg');
```

You can also specify a custom logo:

```php
$watermark->applyWatermark('path/to/image.jpg', 'Your Watermark Text', 'path/to/your/custom/logo.png')->save('path/to/output/image.jpg');
```

## License

The WatermarkCredits package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
