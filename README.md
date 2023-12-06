
# WatermarkCredits

WatermarkCredits is a Laravel package for easily applying watermarks logo and author information on images.

It provides a straightforward interface for applying custom watermarks with adjustable opacity levels and the option to add supplemental text. 

This is perfect for photographers, content creators, and businesses looking to protect their work and ensure proper authorship attribution on their images.
    


![Add your logo and author name on image](watermark_preview.jpg)


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
    'opacity' => 0.6, // 60% opacity
    'rectangle_color' => [0, 0, 0, 0.6], // black with 50% opacity
    'text_color' => '#ffffff', // white
    'font_size' => 24, // default font size
    'font_path' => resource_path('fonts/Arial.ttf'), // path to the font file, assuming Arial.ttf is in the "fonts" folder under "resources"
    'font_ratio' => 0.1, // font size as a ratio of image height
    'default_logo_path' => resource_path('img/yourlogo.png'), // Default logo path, if you have a standard logo you want to use
];
```

## Usage

Here's how you can use the WatermarkCredits package:

```php
use Rafiki23\WatermarkCredits\Watermark;

$watermark = new Watermark();

// Apply watermark to an image
$watermark->applyWatermark('path/to/image.jpg', 'Author name')->save('path/to/output/image.jpg');
```

You can also specify a custom logo:

```php
$watermark->applyWatermark('path/to/image.jpg', 'Author name', 'path/to/your/custom/logo.png')->save('path/to/output/image.jpg');
```

## License

The WatermarkCredits package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
