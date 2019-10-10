<html>
<body>

<?php
// define variables and set to empty values
$idnumber = $name = $email = $phone = $topping = 
   $paymethod  = $callfirst = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $idnumber = test_input($_POST["idnumber"]);
  $name = test_input($_POST["name"]);
  $email = test_input($_POST["email"]);
  $phone = test_input($_POST["phone"]);
  $topping = test_input($_POST["topping"]);
  $paymethod = test_input($_POST["paymethod"]);
  $callfirst = test_input($_POST["callfirst"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<?php
echo "<h2>Your Input:</h2>";
echo "ID Number : "  ;
echo $idnumber;
echo "<br>";
echo "Name : "  ;
echo $name;
echo "<br>";
echo "E-mail : "  ;
echo $email;
echo "<br>";
echo "Phone Number : "  ;
echo $phone;
echo "<br>";
echo "Topping : " ; 
echo $topping ;
echo "<br>";
echo "Pay Method : "  ;
echo $paymethod;
?>

</body>
</html>
