<!DOCTYPE html>
<html>

<body>
       <?php
       $website = '';
       include './simplehtmldom_1_9_1/simple_html_dom.php';
       if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $website = $_POST["website"];
              echo $website;
              if ($website != '') {
                     // Create DOM from URL or file
                     $html = file_get_html($website);
                     echo $html;
                     // Find all images
                     foreach ($html->find('img') as $element)
                            echo '<img src=' . $element->src . '></img>' . '<br>';
                     // Find all links
                     foreach ($html->find('a') as $element) {
                            echo '<a href=' . $element->href . '>' . $element->plaintext . '</a><br>';
                            $tmp_html = file_get_html($element->href);
                            echo $tmp_html;
                     }
              } else {
                     echo 'empty input';
              }
       }
       ?>

</body>

</html>