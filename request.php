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