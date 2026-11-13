<?php
$imgPath = 'C:/Users/Admin/.gemini/antigravity/brain/cb386025-9a7e-4162-9523-1cc7dce8847c/.user_uploaded/media_1794562530431_b5715a23.jpg';
$im = imagecreatefromjpeg($imgPath);

// Let's sample pixels across the top of the first card
// Card 1 seems to be around x: 10 to 250, y: 70 to 700
echo "Color at (15, 75): " . sprintf("#%06X", imagecolorat($im, 15, 75)) . "\n";
echo "Color at (30, 75): " . sprintf("#%06X", imagecolorat($im, 30, 75)) . "\n";
echo "Color at (30, 90): " . sprintf("#%06X", imagecolorat($im, 30, 90)) . "\n";
echo "Color at (20, 420): " . sprintf("#%06X", imagecolorat($im, 20, 420)) . "\n";
echo "Color at (10, 420): " . sprintf("#%06X", imagecolorat($im, 10, 420)) . "\n";
