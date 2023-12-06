<?php

return [
    'opacity' => 0.6, // 60% opacity
    'rectangle_color' => [0, 0, 0, 0.6], // black with 50% opacity
    'text_color' => '#ffffff', // white
    'font_size' => 24, // default font size
    'font_path' => resource_path('fonts/Arial.ttf'), // path to the font file, assuming Arial.ttf is in the "fonts" folder under "resources"
    'font_ratio' => 0.1, // font size as a ratio of image height
    'rectangle_width_ratio' => 0.45, // rectangle width as a ratio of image width
    'rectangle_height_ratio' => 1/6, // rectangle height as a ratio of the shortest side of the image
    'logo_text' => 'Your default head text', // default text to use if no logo is provided
    'default_logo_path' => resource_path('img/yourlogo.png'), // Default logo path, if you have a standard logo you want to use
    // You can add more configuration options related to positioning, etc.
];
