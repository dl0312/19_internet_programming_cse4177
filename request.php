<?php
// Create a 200 x 200 image
$canvas = imagecreatetruecolor(200, 200);

// Allocate colors
$pink = imagecolorallocate($canvas, 255, 105, 180);
$white = imagecolorallocate($canvas, 255, 255, 255);
$green = imagecolorallocate($canvas, 132, 135, 28);

// Draw three rectangles each with its own color
imagerectangle($canvas, 50, 50, 150, 150, $pink);
imagerectangle($canvas, 45, 60, 120, 100, $white);
imagerectangle($canvas, 100, 120, 75, 160, $green);

// Output and free from memory
header('Content-Type: image/jpeg');

imagejpeg($canvas);
imagedestroy($canvas);
?>


<?php

exec("python ./request.py", $out, $status);

$num_detections_idx = 1;
$image_link = $out[0];// Load the image
$num_detections = $out[$num_detections_idx];

$detections = array();
for($i=0;$i<$num_detections;$i++) {
    $detections[$i]['class'] = $out[$i*3 + $num_detections_idx + 1];
    $detections[$i]['score'] = $out[$i*3 + $num_detections_idx + 2];
    $detections[$i]['position'] = $out[$i*3 + $num_detections_idx + 3];
}

echo "status: " . $status . "<br><br>";
echo "image: <img src=" . $image_link . " />";
echo "number of detection: " . $num_detections . "<br>";
for($i=0;$i<$num_detections;$i++) {
    echo "class: " . $detections[$i]['class'] . "<br>";
    echo "score: " . $detections[$i]['score'] . "<br>";
    echo "position: " . $detections[$i]['position'] . "<br><br>";
}
?>