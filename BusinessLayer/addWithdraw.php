<html>
<body>
<center>
<br><br><br><br>
<h1>MCKT
</h1>
<?php
	$email = $_POST['email'];
	$name = $_POST['name'];
     $link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');      
     $result = mysqli_query($link, "SELECT amount FROM Money where userid='".$email."'");
     if($row = $result->fetch_array())
	 {
		 $total= $row["amount"];
		 //$sql = "UPDATE Money SET amount= amount+'".$total."' where userid='".$email."'";
	 }
	 else
	 {
		 $total =0;
		 //$sql = "insert into Money values('".$email."','".$total."')";
	 }  
	 echo "<center>Your account balance is USD ".$total."<center>";
 ?>
 
  
<form method="POST" action="../DataLayer/addWithdrawDBMS.php" onsubmit="return validate();"> 
<table>
<tr>
<td>
Add Money USD
</td>
</tr>
<tr>
<td>
<input type ="number" step="0.01" name="amoney" id="amny" >
</td>
</tr>
<tr>
<td>
<input type="submit" name="add" value="Add"> 
</td>
</tr>
</table>
<input type="hidden" name="email" value=<?php echo $email ?>>
 <input type="hidden" name="name" value=<?php echo $name ?>>
 <input type="hidden" name="total" value=<?php echo $total ?>>
 <script>
    function validate() {
        if (document.getElementById("amny").value<=0) {
            alert("Negative or 0 amount can not be added");
			
			return false;
        }
    }
</script>
</form>

<form method="POST" action="../DataLayer/addWithdrawDBMS.php" onsubmit="return validate1();"> 
<table>
<tr>
<td>
Withdraw Money USD
</td>
</tr>
<tr>
<td>
<input type ="number" step="0.01" name="wmoney" id="wmny" >
</td>
</tr>
<tr>
<td>
<input type="submit" name="withdraw" value="Withdraw"> 
</td>
</tr>
</table>
<input type="hidden" name="email" value=<?php echo $email ?>>
  <input type="hidden" name="name" value=<?php echo $name ?>>
  <input type="hidden" id="total" name="total" value=<?php echo $total ?>>
  <script>
    function validate1() {
        if (document.getElementById("wmny").value<=0 || document.getElementById("wmny").value> document.getElementById("total") ) {
            alert("Negative or 0 money can not be withdrawn");
			return false;
        }
    }
</script>

</center>
<?php	
mysqli_close($link);
?>
</body>	
</html>	