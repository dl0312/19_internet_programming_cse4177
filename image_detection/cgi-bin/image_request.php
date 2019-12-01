<html>

<head>
    <style>
        <?php include '../style.css'; ?>
    </style>
    <script>
        function draw() {
            var canvas = document.getElementById('canvas');
            if (canvas.getContext) {
                var ctx = canvas.getContext('2d');
                var imagePaper = new Image();
                imagePaper.onload = function() {
                    ctx.drawImage(imagePaper, 0, 0);
                }
                imagePaper.src = "https://ssl.pstatic.net/mimgnews/image/311/2019/11/17/0001075726_002_20191117070101111.jpg";
            }
        }
    </script>
</head>

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

            return $mysqli;
        }
        function draw_rectangle_on_image($index, $image_src, $x, $y, $width, $height)
        {
            echo "x: " . $x . "<br>";
            echo "y: " . $y . "<br>";
            echo "width: " . $width . "<br>";
            echo "height: " . $height . "<br>";
            echo "<br>";
            echo "<script>
            
                    setTimeout(() => {
                        ctx_$index.strokeRect(img_$index.width * $x, img_$index.height * $y, img_$index.width * $width, img_$index.height * $height);
                        ctx_$index.stroke();
                    }, 300 * $index);
                </script>";
        }

        function object_detection($index, $mysqli, $image_src)
        {
            exec("python ./request.py $image_src", $out, $status);

            $label_list = array(
                "person", "bicycle", "car", "motorcycle", "airplane", "bus", "train", "truck", "boat", "traffic light", "fire hydrant", "stop sign",
                "parking meter", "bench", "bird", "cat", "dog", "horse", "sheep", "cow", "elephant", "bear", "zebra", "giraffe", "backpack",
                "umbrella", "handbag", "tie", "suitcase", "frisbee", "skis", "snowboard", "sports ball", "kite", "baseball bat", "baseball glove",
                "skateboard", "surfboard", "tennis racket", "bottle", "wine glass", "cup", "fork", "knife", "spoon", "bowl", "banana", "apple",
                "sandwich", "orange", "broccoli", "carrot", "hot dog", "pizza", "donut", "cake", "chair", "couch", "potted plant", "bed", "dining table", "toilet", "tv", "laptop", "mouse", "remote", "keyboard", "cell phone", "microwave", "oven", "toaster", "sink", "refrigerator",
                "book", "clock", "vase", "scissors", "teddy bear", "hair drier", "toothbrush"
            );

            $num_detections_idx = 1;
            $image_link = $image_src; // Load the image
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
                echo "<img id=\"img_$index\" src=\"$image_link\" /><br>";
                $sql = "INSERT INTO image (url)
                        VALUES (\"$image_link\");";
                if ($mysqli->multi_query($sql) === true) {
                    echo "<div>New records created successfully</div>";
                } else {
                    echo "<div>Error: " . $sql . "<br>" . $mysqli->error . "</div>";
                }
                echo "<canvas id=\"canvas_$index\" >Your browser does not support the HTML5 canvas tag.</canvas><br>";
                echo "<script>
                        var canvas_$index = document.getElementById(\"canvas_$index\");
                        var img_$index = document.getElementById(\"img_$index\");
                        var ctx_$index = canvas_$index.getContext('2d');
                        img_$index.onload = function(e) {
                            setTimeout(() => {
                                ctx_$index.canvas.width = img_$index.width;
                                ctx_$index.canvas.height = img_$index.height;
                                ctx_$index.drawImage(img_$index, 0, 0, img_$index.width, img_$index.height);
                                ctx_$index.strokeStyle = \"#ff0000\";
                                ctx_$index.lineWidth = 2;     
                            }, 15 * $index);
                        }
                        
                    </script>";
                echo "# of objects: " . $num_detections . "<br>";
                if ($num_detections) {
                    echo "<div class='objects'>";
                    for ($i = 0; $i < $num_detections; $i++) {
                        if($detections[$i]['score'] > 0.5){
                            $class = $detections[$i]['class'];
                            $label = $label_list[(int) ($detections[$i]['class']) - 1];
                            $score = $detections[$i]['score'];
                            $y = $detections[$i]['position'][0];
                            $x = $detections[$i]['position'][1];
                            $height = $detections[$i]['position'][2] - $detections[$i]['position'][0];
                            $width = $detections[$i]['position'][3] - $detections[$i]['position'][1];
                            $sql = "INSERT INTO object (image_url, label, probability, x, y, w, h)
                                    VALUES (\"$image_link\", \"$label\", $score, $x, $y, $width, $height);";
                            if ($mysqli->multi_query($sql) === true) {
                                echo "<div>New records created successfully</div>";
                            } else {
                                echo "<div>Error: " . $sql . "<br>" . $conn->error . "</div>";
                            }
                            echo "<div class='object'>";
                            echo "class: " . $class . "<br>";
                            echo "label: " . $label . "<br>";
                            echo "score: " . $score . "<br>";
                            draw_rectangle_on_image($index, $image_link, $x, $y, $width, $height);
                            echo "</div>";
                        }
                    }
                    echo "</div>";
                } else {
                    echo "has no detected object<br><br>";
                }
            }
        }
        include './simplehtmldom_1_9_1/simple_html_dom.php';
        $mysqli = connect_mysql("localhost", "cse20131582", "1205", "db_cse20131582");
        $sql = "USE db_cse20131582;";
        if ($mysqli->multi_query($sql) === true) {
            echo "<div>New records created successfully</div>";
        } else {
            echo "<div>Error: " . $sql . "<br>" . $mysqli->error . "</div>";
        }
        $sql = "delete from object;";
        if ($mysqli->multi_query($sql) === true) {
            echo "<div>New records created successfully</div>";
        } else {
            echo "<div>Error: " . $sql . "<br>" . $mysqli->error . "</div>";
        }
        $sql = "delete from image;";
        if ($mysqli->multi_query($sql) === true) {
            echo "<div>New records created successfully</div>";
        } else {
            echo "<div>Error: " . $sql . "<br>" . $mysqli->error . "</div>";
        }
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
                $i=0;
                foreach ($html->find('img') as $element) {
                    // if($i<5){
                    //     array_push($image_links, $element->src);
                    // }
                                            array_push($image_links, $element->src);

                    $i += 1;
                }
                $index = 1;
                foreach ($image_links as $image_link) {
                    object_detection($index, $mysqli, $image_link);
                    $index += 1;
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