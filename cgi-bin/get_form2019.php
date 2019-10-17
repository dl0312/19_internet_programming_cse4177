<html>

<SCRIPT Language="JavaScript">

function CheckSIN(SIN_Number, email) {


      var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
      if (email == '' || !re.test(email)) {
    		alert("Please enter a valid email address.");
      		return false; }


      if (SIN_Number.length != 9) {
         alert("You have not entered a nine character string");
         return false;}


      return true ;
}

</SCRIPT>
<body>

<form action="welcome_get2.php" method="get" onSubmit="return CheckSIN(this.SIN.value, this.email.value)"></p>
Enter your <br>
<item>
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
Social Insurance Number: <input NAME="SIN" VALUE="123456789"><br>
</item>
<input TYPE="SUBMIT"> <input TYPE="RESET">
</form>

</body>
</html>

