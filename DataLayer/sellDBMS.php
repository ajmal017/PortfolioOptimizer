<html>
<body>
<center>
<?php 
	$email=$_POST['email'];
	$name=$_POST['name'];
	$symb = $_POST['symbol'];
	$bP = $_POST['buyPrice'];
	$numb = $_POST['noss'];
    $nativePrice=$_POST['native'];
    $currentPrice=$_POST['current'];
	$symb= explode(",",$symb);
	$bP=explode(",",$bP);
	$numb= explode(",",$numb);
    $nativePrice= explode(",", $nativePrice);
    $currentPrice= explode(",", $currentPrice);
	$counter= count($symb);
	$tootal = $_POST['tootal'];
    $change = $_POST['change'];
//update amount after selling is successful
//check if the given number of shares is less than the number of shares present
//if number of shares given equal to number of shares present delete the row
// add to transactions	
$link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
for($i=0;$i<$counter;$i++)
{
    $que = mysqli_query($link, "select numberOfShares from Stock where userID='".$email."' and buyPrice='".$bP[$i]."' and symbol='".$symb[$i]."'");
    $row = $que->fetch_array();
    $numOfShares = $row["numberOfShares"];  
    if($numOfShares>$numb[$i]) 
    {
        $quet = mysqli_query($link,"select max(tID) from Transaction order by tID DESC");
        $row = $quet->fetch_array();
        $tid=$row['max(tID)']+1;
        $curQuer = mysqli_query($link,"Select indx from Indexsymbol where symbol = '".$symb[$i]."'");
        $indexAr = $curQuer->fetch_array();
        $index - $inderAr['indx'];
        $cur = 'USD';
        if(indx=='NIFTY')
        {
            $cur='INR';
        }
        elseif(indx=='SGX')
        {
            $cur='SGD';
        }
        $queT = mysqli_query($link,"insert into Transaction values(".$tid.",'S','".$email."','".date('Y-m-d')."','".$symb[$i]."',null,".$currentPrice[$i].",".$nativePrice[$i].",".$currentPrice[$i]*$numb[$i].",".$nativePrice[$i]*$numb[$i].",'".$cur."',".$numb[$i].",'SELL')");
        
        $que1 = mysqli_query($link, "update Stock set numberOfShares= numberOfShares-".$numb[$i].", tID = ".$tid." where symbol='".$symb[$i]."' and userID='".$email."' and buyPrice='".$bP[$i]."'");
        if($que1)
        {
            $usuccess="Sell Shares Successful";
            echo '<br><br><br><br><br><br><br><br><br><br><center><h3>'.$usuccess.'</h3></center>' ;
            echo "<br>";
        }
        else
        {
            $queT1 = mysqli_query($link,"delete from Transaction where tID=".$tid);
            echo "Sell Shares Failed";
        }
    }
    elseif($numOfShares==$numb[$i])
    {
         $quet = mysqli_query($link,"select max(tID) from Transaction order by tID DESC");
        $row=$quet->fetch_array();
        $tid=$row['tID']+1;
        
        $queT = mysqli_query($link,"insert into Transaction values(".$tid.",'S','".$email."','".date('Y-m-d')."','".$symb[$i]."',null,".$currentPrice[$i].",".$nativePrice[$i].",".$currentPrice[$i]*$numb[$i].",".$nativePrice[$i]*$numb[$i].",'".$cur."',".$numb[$i].",'SELL')");
        
        
        $que2= mysqli_query($link, "delete from Stock where userID='".$email."' and buyPrice='".$bP[$i]."'and symbol='".$symb[$i]."'");
        if($que2)
        {
            $dsuccess="Sell Stock Successful";
		    echo '<br><br><br><br><br><br><br><br><br><br><center><h3>'.$dsuccess.'</h3></center>' ;
		    echo "<br>";
        }
       else
       {
           $queT1 = mysqli_query($link,"delete from Transaction where tID=".$tid);
		    echo "Sell Stock Failed";
       }
    }
    elseif($numOfShares<$numb[$i])
    {
        echo "Please re-check the number of given shares. The given number of shares is more than the number of shares present in this particular stock.";
    }
    

}
  
if (strpos($dsuccess, 'Successful') !== false || strpos($usuccess, 'Successful') !== false)
{
    $queMoney=mysqli_query($link,"update Money set amount= amount+".$tootal." where userID='".$email."'"); 
    if($queMoney)
    {
        $quet = mysqli_query($link,"select max(tID) from Transaction order by tID DESC");
        $row=$quet->fetch_array();
        $tid=$row['max(tID)']+1;
        
       
        
        $moneyTrans= mysqli_query($link, "insert into Transaction values (".$tid.",'M','".$email."','".date('Y-m-d')."', null ,'".$tootal."',null,null,null,null,'USD', null ,'ADD')");
        
   
        echo "Total amount in your account has been updated.";
    }
}





mysqli_close($link);

?>
    <br>
	<br>
	<br>
<form method="POST" action="../WelcomePage.php">
	<input type="hidden" name="email" value=<?php echo $email ?>>
	<input type="hidden" name="name" value=<?php echo $name ?>>
	<input name ="Return" type = "submit" value="Return to Home">
	</form>
    <br>
	<br>
	<form method="POST" action="../BusinessLayer/viewportfolio.php">
	<input type="hidden" name="email" value=<?php echo $email ?>>
	<input type="hidden" name="name" value=<?php echo $name ?>>
	<input name ="Return" type = "submit" value="View Portfolio">
	</form>
    </center>
    </body>
</html>
