<html>
<br><br><br><br><br><br><br><br><br><br><br><br><br>
<body align="center">
<?php
    echo "123456";
	
	$email = $_POST['email'];
	$password = $_POST['pass'];
	$cpswrd=$_POST['cpass'];
	$fullName = $_POST['Name'];
	$dob=$_POST['dOB'];
	$ph=$_POST['phno'];
	$gender = $_POST['gender'];
    
    $dob=explode("/",$dob);
    $YEAR=$dob[2];
	if($password==$cpswrd)
	{
		$link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
		$result = mysqli_query($link, "insert into Credentials values('".$email."','".$fullName."','".$password."','".$dob."','".$gender."','".$ph."')");
		if($result) {
			mysqli_query($link,"insert into Money values('".$email."',0)");
    
			echo "Registration Successful";
		}
	}
	else
	{
		echo "Registration Failed. Password and confirm password do not match.";
	}
	
	mysqli_close($link);
?>

</body>
</html>