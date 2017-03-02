<?php
$email=$_POST['email'];
$name=$_POST['name'];

 $link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
 $retrieve = mysqli_query($link,"SELECT stockx, symbol, numberOfShares, buyPrice, currency FROM Stock WHERE userID='".$email."'");
 echo "<center><br><br><br><h2>Your Stocks</h2>
 <br><br><br><br><br><br>
<form method='POST' action='sellcheck.php'>
<input type='hidden' name='email' value=".$email." >
 <input type='hidden' name='name' value=".$name.">
 <table border='2' style= background-color: border: 1>
      <thead>
        <tr>
          <th>Select</th>
          <th>Stock Exchange</th>
          <th>Symbol</th>
          <th>Number of shares</th>
          <th>Bought Price</th>
		  <th>Current Price</th>
		  <th>Native Currency Price</th>
		  <th>Change</th>
		  <th>Selling shares number</th>
        </tr>
      </thead>
      <tbody>";
	  $n = 0;
 while($row = $retrieve->fetch_array())
 {
 $i=0;
 $s = "now";
	  $quote = file_get_contents('http://finance.google.com/finance/info?client=ig&q='.$row["stockx"].":".$row["symbol"]);
  //Remove CR's from ouput - make it one line
    $json = str_replace("\n", "", $quote);

  //Remove //, [ and ] to build qualified string  
    $data = substr($json, 4, strlen($json) -5);

  //decode JSON data
    $json_output = json_decode(utf8_decode($data));

  // get the last price
    $lastPrice = $json_output->l;
     $lastPrice = str_replace(",","",$lastPrice);
 echo "<tr>
 <td>
 <input type ='checkbox' name= 'selector[]' value='".$n."' id='".$n."' onclick='return OptionsSelected(this)'>
 <input type= 'hidden' name='stockx[]' value=".$row["stockx"].">
 <input type= 'hidden' name='symbol[]' value=".$row["symbol"].">
 <input type= 'hidden' name='numberOfShares[]' value=".$row["numberOfShares"].">
 <input type= 'hidden' name='buyPrice[]' value=".$row["buyPrice"].">
  
</td>
 <td>
 ". $row["stockx"]. "
 </td>
 <td>
 " . $row["symbol"]. "
 </td>
 <td> 
 ". $row["numberOfShares"]."
 </td>
 <td>
 ". $row["buyPrice"]."</td>";
 
 if($row["currency"]=='INR')
				{
					$xchang = file_get_contents('https://www.exchangerate-api.com/USD/INR?k=1a29aab047894198cd188e14

');
					$data = json_decode($xchang,true);
					$ulastPrice=round($lastPrice/($data),2);
				}
	elseif($row["currency"]=='SGD')
				{
					$xchang = file_get_contents('https://www.exchangerate-api.com/USD/INR?k=1a29aab047894198cd188e14');
					$data = json_decode($xchang,true);
					$ulastPrice= round($lastPrice/($data),2);
				}
			
		else
		{
			$ulastPrice=$lastPrice;
		}
echo "<td>".$ulastPrice."</td><td>".$lastPrice."</td>";
echo "<input type= 'hidden' name='ulastPrice[]' value=".$ulastPrice.">
      <input type= 'hidden' name='lastPrice[]' value=".$lastPrice.">";
 echo "<td>";
			$change = ($lastPrice*$row["numberOfShares"])-($row["buyPrice"]*$row["numberOfShares"]);
			if($change<1)
				{
					echo "<font color='red'>";
					$change = round($change,3);
					echo $change;
					}
				else
				{
					echo "<font color='limegreen'>";
					$change = round($change,3);
					echo $change;
				}
				echo "</font>";
				echo "</td>";
    
	echo "<input type= 'hidden' name='change[]' value=".$change.">
	<td><input type ='text' id='".$s.$n."' name= 'noss[]' onchange='change()' disabled></td>";
	
 $n+=1;
 $i+=1;
 }
 echo " </tbody>
</table>
<input type = 'submit' value='submit' id ='chk' style='position:fixed; bottom:75px;'>
</form>
</center>";
echo '<script type ="text/javascript">
function change(){
    var a = document.getElementById("noss").value;
      if(a<=0){
      	alert("the values cannot be negative or Zero");
      	document.getElementById("chk").disabled = true;
      }
}
function OptionsSelected(me)
{
var s = "now"+me.id;
document.getElementById(s).disabled = false;
}

</script>';

 mysqli_close($link);
?>
<html>
<body>

<body>
</html>