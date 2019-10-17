<html>
<body>

<?php
$cookie_name = $_GET["name"] ;
$cookie_value = $_GET["email"] ;
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
?>

Welcome <?php echo $_GET["name"]; ?> <br>
Your email address is <?php echo $_GET["email"]; ?> <br>
Your Social Security Number is <?php echo $_GET["SIN"]; ?> <br> <br>


<?php
if(!isset($_COOKIE[$cookie_name])) {
    echo "Cookie named '" . $cookie_name . "' is not set!";
} else {
    echo "Cookie '" . $cookie_name . "' is set!<br>";
    echo "Value is: " . $_COOKIE[$cookie_name];
}
?>

</body>
</html>
