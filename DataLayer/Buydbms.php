<html>
	<body>
	<center>
<?php
	 $email=$_POST['email'];
	 $name=$_POST['name'];
	 
	 $link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
	 $StockExchange = $_POST['StockExchange'];
	 $Symbol = strtoupper($_POST['Symbol']);
	 $noOfShares=$_POST['NumberofShares'];
	 $uprice = $_POST['uprice'];
	 $NCPrice = $_POST['price'];
	 $totalPrice=$_POST['tprice'];
    $oltran =$_POST['oltran'];
	 
	 //if its first buy for the user the price should be retrieved from historical data on 12/9/2016
	 //if its next buy todays date should be added
	 //the currency should be saved according to the stockexchange we are buying from
	 //buy stock wen there is no enough money
	 //buy from dow 30, nifty 50, sgi or adr
	 //add to transactions
	 
	 
	 
	 If($StockExchange=='NASDAQ')
	 {
		 $indx="DOW";
		 $Currency="USD";
	 }
	 elseif($StockExchange=="NSE")
	 {
		 $indx="NIFTY";
		 $Currency="INR";
	 }
	 elseif($StockExchange=="SGX")
	 {
		 $indx="STI";
		 $Currency="SGD";
	 }
	 elseif($StockExchange=="ADR")
	 {
		 $indx="ADR";
		 $Currency="USD";
	 }
        
        $queryTrans=mysqli_query($link, "select max(tID) from Transaction order by tID desc ");
        if($queryTrans)
        {
            
            $transrow=$queryTrans->fetch_array();
            $tID=$transrow["max(tID)"]+1;
        }
        else
        {
            $tID=1;
        }
        $queryMoney= mysqli_query($link, "select amount from Money where userID='".$email."'");
	    $row=$queryMoney->fetch_array();
	    $amount=$row["amount"];
        $result= mysqli_query($link, "select userID from Stock where userID='".$email."' and symbol='".$Symbol."'and buyPrice= '".$uprice."'");
        $row1=$result->fetch_array();
		$id=$row1['userID'];
		if($amount>=$totalPrice)
		{
            
			if ($id==$email)
			{
                $query=mysqli_query($link,"insert into Transaction values(".$tID.",'S','".$email."','".date('Y-m-d')."','".$Symbol."',null,".$uprice.",".$NCPrice.",".$totalPrice.",".$NCPrice*$noOfShares.",'".$Currency."',".$noOfShares.",'BUY')");
                
				$result1=mysqli_query($link,"update Stock set numberOfShares= numberOfShares + ".$noOfShares." where userID='".$email."' and symbol='".$Symbol."' and buyPrice='".$uprice."'");
		
				if($result1)
				{
					$usuccess="Update Shares Successful";
					echo '<br><br><br><br><br><br><br><br><br><br><center><h3>'.$usuccess.'</h3></center>' ;
					echo "<br>";
				}
				else
				{
                    $deleteTrans = mysqli_query($link,"delete from Transaction where tID=".$tID); 
					echo "Update Shares Failed";
				}
			}
			else
            {
                if($oltran==1)
                {
                    $query=mysqli_query($link,"insert into Transaction values(".$tID.",'S','".$email."','2016-09-12','".$Symbol."',null,".$uprice.",".$NCPrice.",".$totalPrice.",".$NCPrice*$noOfShares.",'".$Currency."',".$noOfShares.",'BUY')");
                    
                }
                else
                {
                     $query=mysqli_query($link,"insert into Transaction values(".$tID.",'S','".$email."','".date('Y-m-d')."','".$Symbol."',null,".$uprice.",".$NCPrice.",".$totalPrice.",".$NCPrice*$noOfShares.",'".$Currency."',".$noOfShares.",'BUY')");
                } 
				$result2 = mysqli_query($link, "insert into Stock values('".$email."','".$StockExchange."','".$Symbol."',".$noOfShares.",'".$uprice."','".$Currency."','".$NCPrice."' ,".$tID.")");
               
				if($result2)
					{

						$bsuccess="Buy Successful";
						echo '<br><br><br><br><br><br><br><br><br><br>';
						echo $bsuccess;
						echo "<br>";
					}
                else
				{
                    $deleteTrans = mysqli_query($link,"delete from Transaction where tID=".$tID); 
					echo "Buy Failed";
				}
			  
            }
					
                
		    if (strpos($bsuccess, 'Successful') !== false)
			 {
                $queryDeduct=mysqli_query($link, "update Money set amount= amount-".$totalPrice);
                if($queryDeduct)
					{
                        $queryTrans= mysqli_query($link, "select max(tID) from Transaction order by tID desc");
                        if($queryTrans)
                        {
                            $transrow=$queryTrans->fetch_array();
                            $tID=$transrow["max(tID)"]+1;  
                        }
            
                    if($oltran==1)
                    {
                         $moneyTrans= mysqli_query($link, "insert into Transaction values (".$tID.",'M','".$email."','2016-09-12', null ,'".$totalPrice."',null,null,null,null,'USD', null ,'WITHDRAW')");
                    }
                    else
                    {
                        $moneyTrans= mysqli_query($link, "insert into Transaction values (".$tID.",'M','".$email."','".date('Y-m-d')."', null ,'".$totalPrice."',null,null,null,null,'USD', null ,'WITHDRAW')");   
                    }
                           
						$msuccess="Money updated successfully";
						echo $msuccess;
					} 
			 } 
	     
		 
        }	
	 
		elseif($amount<$totalPrice)
		{
			echo "You dont have enough money in your account. Please add money to buy more stock.";
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
	<br>
	<form method="POST" action="../BusinessLayer/viewportfolio.php">
	<input type="hidden" name="email" value=<?php echo $email ?>>
	<input type="hidden" name="name" value=<?php echo $name ?>>
	<input name ="Return" type = "submit" value="View Portfolio">
	</form>
	
	</center>
	</body>
	</html>