<?php

namespace Rafiki23\WatermarkCredits;

use Intervention\Image\Facades\Image;

class Watermark
{
    protected $image;
    protected $opacity;
    protected $textColor;
    protected $fontSize;
    protected $fontPath;
    protected $fontRatio;

    public function __construct($fontRatio = 0.1)
    {
        // Load default configurations
        $this->opacity = config('watermark.opacity', 0.6);
        $this->rectangleColor = config('watermark.rectangle_color', [0, 0, 0, 0.6]);
        $this->textColor = config('watermark.text_color', '#ffffff');
        $this->fontSize = config('watermark.font_size', 24);
        $this->fontPath = config('watermark.font_path', resource_path('fonts/Arial.ttf'));
        $this->fontRatio = $fontRatio;
        $this->rectangleWidthRatio = config('watermark.rectangle_width_ratio', 0.45);
        $this->rectangleHeightRatio = config('watermark.rectangle_height_ratio', 1/6);
    }

    /**
     * Draws a rectangle on the image at a specified position.
     *
     * @param int $x The X coordinate for the rectangle.
     * @param int $y The Y coordinate for the rectangle.
     * @param int $width The width of the rectangle.
     * @param int $height The height of the rectangle.
     */
    protected function drawRectangle($x, $y, $width, $height)
    {
        $this->image->rectangle($x, $y, $width, $height, function ($draw) {
            $draw->background($this->rectangleColor);
        });
    }

    /**
     * Adds text to the image at a specified position.
     *
     * @param string $text The text to add.
     * @param int $x The X coordinate for the text.
     * @param int $y The Y coordinate for the text.
     */
    protected function addText($text, $x, $y)
    {
        // Calculate proportional font size
        $fontSize = $this->image->height() * $this->fontRatio;

        $this->image->text($text, $x, $y, function($font) use ($fontSize) {
            $font->file($this->fontPath);
            $font->size($fontSize);
            $font->color($this->textColor);
            $font->align('left');
            $font->valign('bottom');
        });
    }

    /**
     * Inserts a logo or alternative text if the logo is not available.
     *
     * @param string|null $logoPath The path to the logo.
     * @param string $text The text to use if the logo is not available.
     * @param int $x The X coordinate for the logo or text.
     * @param int $y The Y coordinate for the logo or text.
     */
    protected function insertLogoOrText($logoPath, $text, $x, $y)
    {
        $fontSize = $this->image->height() * $this->fontRatio;

        if (!$logoPath) {
            $logoPath = config('watermark.default_logo_path');
        }

        if ($logoPath && file_exists($logoPath)) {
            $logo = Image::make($logoPath);
            $this->image->insert($logo, 'bottom-right', 10, 10);
        } else {
            $logoText = config('watermark.logo_text');
            $this->addText($logoText, $this->image->width() - 100, $this->image->height() - 30);
        }
    }

    /**
     * Apply a watermark to an image.
     *
     * @param string $imagePath Path to the image file.
     * @param string $text Watermark text to apply on the image.
     * @param string|null $logoPath Optional path to the logo; if not provided, a default will be used.
     * @return Watermark The current instance for method chaining.
     * @throws \Exception If the image file does not exist.
     */
    public function applyWatermark($imagePath, $text, $logoPath = null)
    {
        if (!file_exists($imagePath)) {
            throw new \Exception("Image file does not exist: {$imagePath}");
        }

        $this->image = Image::make($imagePath);

        // Calculate the position for the rectangle
        $shortSide = min($this->image->width(), $this->image->height());
        $rectangleHeight = $shortSide / 6;
        $y = $this->image->height() - (2*$rectangleHeight);
        $width = $this->image->width(); // Adjust width as needed
        $x = $this->image->width() - ($this->image->width() * 0.55);

        // Draw the rectangle
        $this->drawRectangle($x, $y, $width, $y+$rectangleHeight);

        // Add text to the rectangle
        //$this->addText($text, $x + 10, $y + ($rectangleHeight / 2));

        // Handle logo insertion or alternate text if logo path is not provided
        //$this->insertLogoOrText($logoPath, $text, $x, $y);

        return $this;
    }

    /**
     * Save the image with watermark to a specified path.
     *
     * @param string $outputPath Path to save the image.
     */
    public function save($outputPath)
    {
        $this->image->save($outputPath);
    }
}
