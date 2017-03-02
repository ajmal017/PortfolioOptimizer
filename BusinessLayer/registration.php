<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Insert title here</title>
</head>
<body>
<?php ?>
<h1 align="center">MCKT</h1>
<h3 align="center">REGISTER</h3>
<form method="post" action="registrationValidation.php">
<table align="center">
	<tr>
	<td>
	Email:
	</td>
	<td>
	<input type="text" name="email" />
	</td>
	</tr>
	<tr>
	<td>
	Full Name:
	</td>
	<td>
	<input type="text" name="Name"/>
	</td>
	</tr>
	<tr>
	<td>
	Date of Birth:
	</td>
	<td>
	<input type="date" name="dOB"/>
	</td>
	</tr>
	<tr>
	<td>
	Gender:
	</td>
	<td>
	<input type="text" name="gender"/>
	</td>
	</tr>
	<tr>
	<td>
	Phone number:
	</td>
	<td>
	<input type="tel" name="phno"/>
	</td>
	<tr>
	<td>
	Password:
	</td>
	<td>
	<input type="password" name="pass"/>
	</td>
	</tr>
	<tr>
	<td>
	Confirm Password:
	</td>
	<td>
	<input type="password" name="cpass"/>
	</td>
	</tr>
	<tr>
	<td>
	</td>
	<td>
	<br>
	<input type="submit" name="register" value="Register"/>
	</td>
	</tr>
</table>
</form>
</body>
</html>