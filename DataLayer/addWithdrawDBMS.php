<html>
<body>
<center>
<br><br><br><br>
<h1>MCKT
</h1>
<?php
	$email = $_POST['email'];
	$name = $_POST['name'];
	$amny = $_POST['amoney'];
	$wmny = $_POST['wmoney'];
	$total = $_POST['total'];
    
    
     $link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');      
	$total+=($amny-$wmny);
	if($total>=0)
	{
		$sql = "update Money set amount = '".$total."'where userId='".$email."'";
		if(!mysqli_query($link,$sql))
	 {
		 echo "Entering error";
	 }
	 else
	 {
         $queryTrans= mysqli_query($link, "select tID from Transaction order by tID desc limit 0,1");
         if($queryTrans)
         {
            $transrow=$queryTrans->fetch_array();
            $tID=$transrow["tID"]+1;  
         }
         if(!$amny==null)
         {
            $moneyTrans= mysqli_query($link, "insert into Transaction values (".$tID.",'M','".$email."','".date('Y-m-d')."', null ,'".$amny."', null, null, null, null,'USD', null ,'ADD')"); 
         }
         if(!$wmny==null)
         {
            $moneyTrans= mysqli_query($link, "insert into Transaction values (".$tID.",'M','".$email."','".date('Y-m-d')."', null ,'".$wmny."', null, null, null, null,'USD', null ,'WITHDRAW')"); 
         }

		 echo "Successful";
		 echo "<br><br> Account balance : ".$total;
	 }
	}
	else
	{
		echo "You can not withdraw more than what you have in account";
	}
		
	 mysqli_close($link);
 ?>
	<br><br><br><br>
	<form method="POST" action="../WelcomePage.php">
	<input type="hidden" name="email" value=<?php echo $email ?>>
	<input type="hidden" name="name" value=<?php echo $name ?>>
	<input name ="Return" type = "submit" value="Return to Home">
	</form>
</body>	
</html>	