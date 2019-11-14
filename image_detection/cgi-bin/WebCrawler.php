<html>
<style>
<?php include '../style.css';?>
</style>
<body>
<div id="container">


<?php

function connect_mysql($hostname, $username, $password, $dbname)
{
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $mysqli = new mysqli($hostname, $username, $password)
    or die("DB Connection Failed");

    if ($mysqli) {
        echo ("MySQL Server Connect Success!");
    } else {
        echo ("MySQL Server Connect Failed!");
    }

    if ($mysqli->multi_query("INSERT INTO objects (label_name, probability, x, y, w, h) VALUES (\"bench\", 0.324781924, 0.256128967, 0.387623757, 0.703355372, 0.505128562);")
        === true) {
        echo "<div>New records created successfully</div>";
    } else {
        echo "<div>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
    return $mysqli;
}
function draw_rectangle_on_image($image_src, $x1, $y1, $x2, $y2)
{
//     $canvas = imagecreatefromjpeg($image_src);

    echo "x1: " . $x1 . "<br>";
    echo "y1: " . $y1 . "<br>";
    echo "x2: " . $x2 . "<br>";
    echo "y2: " . $y2 . "<br>";
    echo "<br>";
}

function object_detection($mysqli, $image_src)
{
    exec("python ./request.py $image_src", $out, $status);

    $label_list = array("person", "bicycle", "car", "motorcycle", "airplane", "bus", "train", "truck", "boat", "traffic light", "fire hydrant", "stop sign",
        "parking meter", "bench", "bird", "cat", "dog", "horse", "sheep", "cow", "elephant", "bear", "zebra", "giraffe", "backpack",
        "umbrella", "handbag", "tie", "suitcase", "frisbee", "skis", "snowboard", "sports ball", "kite", "baseball bat", "baseball glove",
        "skateboard", "surfboard", "tennis racket", "bottle", "wine glass", "cup", "fork", "knife", "spoon", "bowl", "banana", "apple",
        "sandwich", "orange", "broccoli", "carrot", "hot dog", "pizza", "donut", "cake", "chair", "couch", "potted plant", "bed", "dining table", "toilet", "tv", "laptop", "mouse", "remote", "keyboard", "cell phone", "microwave", "oven", "toaster", "sink", "refrigerator",
        "book", "clock", "vase", "scissors", "teddy bear", "hair drier", "toothbrush");

    $num_detections_idx = 1;
    $image_link = $out[0]; // Load the image
    $num_detections = $out[$num_detections_idx];

    $detections = array();
    for ($i = 0; $i < $num_detections; $i++) {
        $detections[$i]['class'] = $out[$i * 6 + $num_detections_idx + 1];
        $detections[$i]['score'] = $out[$i * 6 + $num_detections_idx + 2];
        for ($j = 0; $j < 4; $j++) {
            $detections[$i]['position'][$j] = $out[$i * 6 + $num_detections_idx + 3 + $j];
        }
    }
    if ($status === 0) {
        echo "<img src=" . $image_link . " /><br>";
        echo "# of objects: " . $num_detections . "<br>";
        if ($num_detections) {
            echo "<div class='objects'>";
            for ($i = 0; $i < $num_detections; $i++) {
                $class = $detections[$i]['class'];
                $label = $label_list[(int) ($detections[$i]['class']) - 1];
                $score = $detections[$i]['score'];
                $x1 = $detections[$i]['position'][0];
                $y1 = $detections[$i]['position'][1];
                $x2 = $detections[$i]['position'][2];
                $y2 = $detections[$i]['position'][3];
                $sql = "INSERT INTO objects (label_name, probability, x, y, w, h)
                VALUES (\"$label\", $score, $x1, $y1, $x2, $y2);";
                if ($mysqli->multi_query($sql) === true) {
                    echo "<div>New records created successfully</div>";
                } else {
                    echo "<div>Error: " . $sql . "<br>" . $conn->error . "</div>";
                }
                echo "<div class='object'>";
                echo "class: " . $class . "<br>";
                echo "label: " . $label . "<br>";
                echo "score: " . $score . "<br>";
                draw_rectangle_on_image($image_link, $x1, $y1, $x2, $y2);
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "has no detected object<br><br>";
        }
    }
}
include './simplehtmldom_1_9_1/simple_html_dom.php';
$mysqli = connect_mysql("localhost", "cse20131582", "1205", "db_cse20131582");
$website = '';
$image_links = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $website = $_POST["website"];
    echo "<h1>Object Detection for Images</h1><br><br>";
    echo "<h1>URL: " . $website . "</h1><br><br>";
    if ($website != '') {
        // Create DOM from URL or file
        $html = file_get_html($website);
        // Find all images
        foreach ($html->find('img') as $element) {
            array_push($image_links, $element->src);
        }
        foreach ($image_links as $image_link) {
            object_detection($mysqli, $image_link);
        }
    } else {
        echo 'empty input';
    }
    return $image_links;
}

?>
</div>
</body>
</html>