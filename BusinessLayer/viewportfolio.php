<html>
<body align="center">
<h1>PORTFOLIO</h1>
<?php
	$uid = $_POST['email'];
	$name = $_POST['name'];
	$link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
	$result = mysqli_query($link, "SELECT amount FROM Money where userID='".$uid."'");
	$row = $result->fetch_array();
    $cashAmount= $row["amount"];
	echo "Cash Balance in USD: ".$cashAmount;
	echo "<br><br><br><br>";	
	$portfolio = mysqli_query($link, "SELECT stockx,symbol,numberOfShares,buyPrice,currency,nativecurrencyprice FROM Stock where userID='".$uid."'");
	$rowCnt = mysqli_num_rows($portfolio);
	$dis = "";
	if($rowCnt==0)
	{
		$dis = "display:none";
		echo "Buy some shares to view portfolio";
	}
	echo "<div style='".$dis."'><table align='center' border = 1>
			<tr>
			<td>
				Index
			</td>
			<td>
				Symbol
			</td>
			<td>
				Stock Ex
			</td>
			<td>
				Buy price 
			</td>
			<td>
				Last Price
			</td>
			<td>
				Shares
			</td>
			<td>
				Currency
			</td>
			<td>
				Native Price
			</td>
			<td>
				Current Native Price
			</td>
			<td>
				Change%
			</td>
			<td>
				Change
			</td>
			<td>
				Total
			</td>
			</tr>";
			$netAssetValue = $cashAmount;
			$dow = 0;
			while($tupple = $portfolio->fetch_array())
			{
				$quer = mysqli_query($link, "SELECT indx FROM Indexsymbol where symbol='".$tupple["symbol"]."'");
				$ind = $quer->fetch_array();
				$quote = file_get_contents('http://finance.google.com/finance/info?client=ig&q='.$tupple["stockx"].':'.$tupple["symbol"]);
				//Remove CR's from ouput - make it one line
				$json = str_replace("\n", "", $quote);
				//Remove //, [ and ] to build qualified string  
				$data = substr($json, 4, strlen($json) -5);
				//decode JSON data
				$json_output = json_decode(utf8_decode($data));
				// get the last price
				$last = $json_output->l;
				$lastNative = $json_output->l_cur;
				echo "<tr>";
				echo "
				<td>";
				echo $ind["indx"];
				echo "</td>";
				echo "
				<td>";
				echo $tupple["symbol"];
				echo "</td>
				<td>";
				echo $tupple["stockx"];
				echo "</td>
				<td>";
				echo $tupple["buyPrice"];
				echo "</td>
				<td>";
			
				if($tupple["currency"]=='INR')
				{
					$xchang = file_get_contents('http://api.fixer.io/latest?base=USD');
					$data = json_decode($xchang,true);
                    $last=str_replace(",","","$last");
					$last=round($last/($data["rates"]["INR"]),2);					
				}
				elseif($tupple["currency"]=='SGD')
				{
					$xchang = file_get_contents('http://api.fixer.io/latest?base=USD');
					$data = json_decode($xchang,true);
					$last= round($last/($data["rates"]["SGD"]),2);
				}
			
				echo $last;				
				echo "</td>
				<td>";
				echo $tupple["numberOfShares"];
				echo "</td>
				<td>";
				echo $tupple["currency"];
				echo "</td>
				<td>";
				echo $tupple["nativecurrencyprice"];
				echo "</td>
				<td>";
			
				if($last==$lastNative)
				{
					echo "USD".$lastNative;
				}
				else{
				echo $lastNative;
				}
				echo "</td>
				<td>";
				$changeP = ($last-$tupple["buyPrice"])*100/$tupple["buyPrice"];
				if($changeP<1)
				{
					echo "<font color='red'>";
					echo round($changeP,3);
					}
				else
				{
					echo "<font color='limegreen'>";
					echo round($changeP,3);
				}
				echo "</font>";
				echo "</td>
				<td>";
				$change = ($last*$tupple["numberOfShares"])-($tupple["buyPrice"]*$tupple["numberOfShares"]);
			if($change<1)
				{
					echo "<font color='red'>";
					echo round($change,3);
					}
				else
				{
					echo "<font color='limegreen'>";
					echo round($change,3);
				}
				echo "</font>";
				echo "</td>
				<td>";
				$total = $last*$tupple["numberOfShares"];
				$netAssetValue+=$total;
				echo $total;
				echo "</td></tr>";
				if($ind["indx"]=='DOW')
				{
					$dow+=$total;
				}             
			}
		echo "</table><br><br>";
        ?>    
    <br>
    <form method="POST"action="ViewTransactions.php">
<input type="hidden" name="email" value=<?php echo $uid ?>>
<input type="hidden" name="name" value=<?php echo $name ?>>
<input type="hidden" name="netAssetValue" value=<?php echo $netAssetValue ?>>
<input type="Submit" value="View Transactions">
</form>
    <?php
		echo "Net Asset Value: ".$netAssetValue;
		echo "<br><br>Portfolio Status: ";
    fclose($fp); 
		$cashValidation = (($cashAmount*100/$netAssetValue)>10)? False: True;
		$stockNumberValidation = ($rowCnt>6 && $rowCnt<11)? True: False;
		$dowValidation =(($dow*100/($netAssetValue-$cashAmount))> 70)? False: True;
		if($cashValidation && $stockNumberValidation && $dowValidation)
		{
			echo "<font color='limegreen'>";
			echo "Balanced";
		}
		else
		{
			echo "<font color='red'>";
			echo "Unbalanced";
		}
		if(!$cashValidation)
		{
			echo "<br>Cash in your account is more than 10% of the net asset value of your portfolio.
			<br>We recommend that you invest more. ";
		}
		if(!$stockNumberValidation)
		{
			echo "<br>The recommended number of stocks is between 7 and 10.";
		}
		if(!$dowValidation)
		{
			echo "<br>We recommend that you have 70% of shares in DOW30 index.\n";
            echo "<br>Current shares you have in DOW30 is :".round((($dow*100)/($netAssetValue-$cashAmount)),2)." %.";
            echo "<br>Current International shares you have :".round((100-(($dow*100)/($netAssetValue-$cashAmount))),2)." %.";
		}
		echo "</font></div>";
	mysqli_close($link);?>
	<br><br><br>
    <form method="POST" action="Optimize.php">
	<input type="hidden" name="email" value=<?php echo $uid ?>>
	<input type="hidden" name="name" value=<?php echo $name ?>>
	<input name ="Return" type = "submit" value="Optimize Portfolio">
	</form>
    <br><br>
	<form method="POST" action="../WelcomePage.php">
	<input type="hidden" name="email" value=<?php echo $uid ?>>
	<input type="hidden" name="name" value=<?php echo $name ?>>
	<input name ="Return" type = "submit" value="Return to Home">
	</form>
    
</body>
</html>