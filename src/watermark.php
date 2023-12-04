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
        $this->rectangleHeightRatio = config('watermark.rectangle_height_ratio', 1 / 6);
    }

    /**
     * Draws a rectangle on the image at a specified position.
     *
     * @param int $x The X coordinate for the rectangle.
     * @param int $y The Y coordinate for the rectangle.
     * @param int $width The width of the rectangle.
     * @param int $height The height of the rectangle.
     */
    protected function drawRectangle(int $x, int $y, int $width, int $height): void
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
    protected function addText(string $text, int $x, int $y, int $rectwidth, int $rectheight): void
    {

        //$addmargin = 80;

        // Calculate proportional font size
        $fontSize = $rectheight / 5;

        $this->image->text($text, $x, $y, function ($font) use ($fontSize) {
            $font->file($this->fontPath);
            $font->size($fontSize);
            $font->color($this->textColor);
            $font->align('left');
            $font->valign('top');
        });
    }

    /**
     * Inserts a logo and text.
     *
     * @param string|null $logoPath The path to the logo.
     * @param string $text The text to use.
     * @param int $x The X coordinate for the logo or text.
     * @param int $y The Y coordinate for the logo or text.
     */
    protected function insertLogoAndText(?string $logoPath, string $text, int $x, int $y, int $rectwidth, int $rectheight): void
    {

        $padding = round($rectheight * 0.2);

        if (!$logoPath) {
            $logoPath = config('watermark.default_logo_path');
        }

        if ($logoPath && !file_exists($logoPath)) {
            throw new \Exception("Logo file does not exist: {$logoPath}");
        }

        if ($logoPath && file_exists($logoPath)) {
            $logo = Image::make($logoPath);

            $logoheight = $logo->height();

            if ($logo->height() / $rectheight > 0.3) {
                $logoheight = round($rectheight * 0.3);
                $logo->resize(round(((($rectheight * 0.3) * $logo->width())) / $logo->height()), $logoheight);
            }

            $this->image->insert($logo, 'top-left', $x + $padding, $y + $padding);

            $this->addText($text, $x + $padding, $y + $logoheight + $padding + ($logoheight / 4), $rectwidth, $rectheight);
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
    public function applyWatermark(string $imagePath, string $text, ?string $logoPath = null): self
    {
        if (!file_exists($imagePath)) {
            throw new \Exception("Image file does not exist: {$imagePath}");
        }

        $this->image = Image::make($imagePath);

        // Calculate the position for the rectangle
        $shortSide = min($this->image->width(), $this->image->height());
        $rectangleHeight = $shortSide / 7;
        $y = $this->image->height() - (2 * $rectangleHeight);
        $width = $this->image->width(); // Adjust width as needed
        $x = $this->image->width() - ($this->image->width() * 0.55);
        $rectwidth = $width - $x;
        $rectheight = $rectangleHeight;

        // Draw the rectangle
        $this->drawRectangle($x, $y, $width, $y + $rectangleHeight);

        // Handle logo insertion or alternate text if logo path is not provided
        $this->insertLogoAndText($logoPath, $text, round($x), round($y), $rectwidth, $rectheight);

        return $this;
    }

    /**
     * Save the image with watermark to a specified path.
     *
     * @param string $outputPath Path to save the image.
     */
    public function save(string $outputPath): bool
    {
        try {
            $this->image->save($outputPath);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
