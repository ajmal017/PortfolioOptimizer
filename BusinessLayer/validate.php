<html>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<body align="center">
<?php
	$email = $_POST['uId'];
	$password = $_POST['pId'];
	$link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
	$result = mysqli_query($link, "select fullName,password from Credentials where userid='".$email."'");
    $row = $result->fetch_array();
    $pass= $row["password"];
	$name = $row["fullName"];
	if($pass==$password)
	{
		echo '<h3>'."Login successful".'</h3>';
		echo '<br><br><br><br>';
		echo '<form method="POST" action="../WelcomePage.php" id="dateForm">';
		echo '<input type="hidden" name="name" value='.$name.'>';
		echo '<input type="hidden" name="email" value='.$email.'>';
		echo '<input type="submit" value ="Home Page"/>';
		echo '</form>';
	}
	else
	{
		echo "UserID and Password combination does not match.";
		echo '<br><br><br><br>';
		echo '<form action="../index.html">';
		echo '<input type="submit" value ="Return to Login Page"/>';
		echo '</form>';
	}
	
	mysqli_close($link);
?>
</body>
</html>