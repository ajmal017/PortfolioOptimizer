<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset ="utf-8">
	
    
    
</head>
    
<body align="center">
<h1>Welcome <?php 
	$email=$_POST['email'];
	$name=$_POST['name'];
    echo ucfirst($name);
    
?></h1>
 
 
<table  style="position:fixed; top:285px; left:200px">   
<tr>
<td>
<form method="POST"action="BusinessLayer/addWithdraw.php">
<input type="hidden" name="email" value=<?php echo $email ?>>
<input type="hidden" name="name" value=<?php echo $name ?>>
<input type="Submit" value="Add/Withdraw Money" style="width: 150px; height: 50px; font-size: 15px">
</form>
</td>
</tr>
</table> 
    
<table style="position:fixed; top:250px; right:200px">
<tr>
<td>
<form method="POST"action="BusinessLayer/buy.php">
<input type="hidden" name="email" value=<?php echo $email ?>>
<input type="hidden" name="name" value=<?php echo $name ?>>
<input type="Submit" value="Buy Stock" style="width: 150px; height: 50px; font-size: 15px">
</form>
</td>
</tr>
<tr>
<td>
<form method ="POST" action="BusinessLayer/sell.php">
<input type="Submit" value="Sell Stock" style="width: 150px; height: 50px; font-size: 15px">
<input type="hidden" name="email" value=<?php echo $email ?>>
<input type="hidden" name="name" value=<?php echo $name ?>>
</form>
</td>
</tr>
</table>
<table style="position:fixed; top:285px; right:600px" >
<tr>
<td>
<form method="POST" action="BusinessLayer/viewportfolio.php">
<input type="hidden" name="email" value=<?php echo $email ?>>
<input type="hidden" name="name" value=<?php echo $name ?>>
<input type="Submit" value="View Portfolio" style="width: 150px; height: 50px; font-size: 15px">
</form>
</td>
</tr>
</table>
    
    <script>
        document.getElementById("demo").innerHTML = Date();
    </script>  
</body>
</html>