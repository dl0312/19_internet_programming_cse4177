<html>

<SCRIPT Language="JavaScript">

    function CheckSIN(SIN_Number) {
      if (SIN_Number.length != 9) {
         alert("You have not entered a nine character string");
         return false;}
      else { return true;}
      }

</SCRIPT>
<body>

<form action="welcome_get2.php" method="get" onSubmit="return CheckSIN(this.SIN.value)"></p>
Enter your <br>
<OL>
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
Social Insurance Number: <input type="password", NAME="SIN" VALUE="123456789"><br>
</item>
<input TYPE="button", value="안녕", onClick="confirm('Hello')"> <br>
<input TYPE="SUBMIT"> 
<input TYPE="RESET">
</form>

</body>
</html>

