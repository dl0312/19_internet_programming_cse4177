<html>
<body>

<?php
setcookie("TEST+COOKIE", "LALA", time()*3600, "/");
?>


<?php

print_r($_COOKIE);

?>
 
</body>
</html>

