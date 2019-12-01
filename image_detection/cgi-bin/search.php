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

    $mysqli = connect_mysql("localhost", "cse20131582", "1205", "db_cse20131582");
    $sql = "USE db_cse20131582;";
    if ($mysqli->multi_query($sql) === true) {
        echo "<div>New records created successfully</div>";
    } else {
        echo "<div>Error: " . $sql . "<br>" . $mysqli->error . "</div>";
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $label = $_POST["label"];
        $score = $_POST["score"];
        echo $label . "<br>";
        echo $score . "<br>";
        $sql = "SELECT *
                FROM object
                WHERE label = \"$label\" AND probability > $score;";
        $result = $mysqli->query($sql);
        $index = 1;
        while(true){
            $row = $result->fetch_array(MYSQLI_ASSOC);
            if($row == false) break;

            $image_url = $row["image_url"];
            $label = $row["label"];
            $score =  $row["probability"];
            $x = $row["x"];
            $y = $row["y"];
            $width = $row["w"];
            $height = $row["h"];
            echo "<img id=\"img_$index\" src=\"$image_url\" /><br>";
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
                                setTimeout(() => {
                                    ctx_$index.strokeRect(img_$index.width * $x, img_$index.height * $y, img_$index.width * $width, img_$index.height * $height);
                                    ctx_$index.stroke();
                                }, 300 * $index);
                            }, 15 * $index);
                        }
                            
                    </script>";
            echo "image_url: " . $image_url . "<br>";
            echo "label: " . $label . "<br>";
            echo "score: " . $score . "<br>";
            echo "x: " . $x . "<br>";
            echo "y: " . $y . "<br>";
            echo "width: " . $width . "<br>";
            echo "height: " . $height . "<br>";
            $index += 1;
        }
    }
?>