<?php
$email=$_POST['email'];
$name=$_POST['name'];


$counter = count($_POST["selector"]); 
$selector = $_POST["selector"];
$numb1 = $_POST["noss"];
$stockx = $_POST["stockx"];
$symbol=$_POST["symbol"];
$nos=$_POST["numberOfShares"];
$bPrice=$_POST["buyPrice"];
$cPrice=$_POST["ulastPrice"];
$ncPrice=$_POST["lastPrice"];
$change=$_POST["change"];
 echo "<center><br><br><br><h2>Your Stocks</h2>
 <br><br><br><br><br><br>
<form method='POST' action='../DataLayer/sellDBMS.php'>
 <table border='2' style= background-color: border: 1>
      <thead>
        <tr>
          <th>Stock Exchange</th>
          <th>Symbol</th>
          <th>Number of shares</th>
          <th>Bought Price</th>
		  <th>Current Price</th>
		  <th>Native Currency Price</th>
		  <th>Change</th>
		  <th>Number of shares to sell</th>
        </tr>
      </thead>
      <tbody>";
$numb = array();
foreach($numb1 as $val)
{
    if($val!=NULL)
    {
    array_push($numb,$val);}
}
$tootal= 0;
for($i=0; $i < $counter; $i++)
{
	$tootal+=$cPrice[$selector[$i]]*$numb[$i];
	if($i==0){
		$symb=$symbol[$selector[$i]];
	}
	else
	{
		$symb= $symb.",".$symbol[$selector[$i]];
	}
	
	if($i==0){
		$bp=$bPrice[$selector[$i]];
	}
	else
	{
		$bp= $bp.",".$bPrice[$selector[$i]];
	}
	if($i==0){
		$noss=$numb[$i];
	}
	else
	{
		$noss= $noss.",".$numb[$i];
	}
    if($i==0){
		$currentPrice=$cPrice[$i];
	}
	else
	{
		$currentPrice= $currentPrice.",".$cPrice[$i];
	}
    if($i==0){
		$nativePrice=$ncPrice[$i];
	}
	else
	{
		$nativePrice= $nativePrice.",".$ncPrice[$i];
	}
echo "<tr>
 <td>
 ".$stockx[$selector[$i]]."
 </td>
 <td>
 ".$symbol[$selector[$i]]."
 </td>
 <td> 
 ". $nos[$selector[$i]]."
 </td>
 <td>
 ". $bPrice[$selector[$i]]."
 </td>
 <td>
 ". $cPrice[$selector[$i]]."
 </td>
 <td>
 ". $ncPrice[$selector[$i]]."
 </td>
 <td>
 ".$change[$selector[$i]]."
 </td>
 <td>
 <input text type='number' name='numofsellshares' value=".$numb[$i]." readonly>
 <input type ='hidden' name = 'change[]' value='".$change[$selector[$i]]."'>
 </td>
 </tr>";
}
 echo " </tbody>
</table>
<br><br><br>
<input type=submit value='Sell' />
 <input type= 'hidden' name='symbol' value='".$symb."'>
 <input type= 'hidden' name='buyPrice' value='".$bp."'>
 <input type='hidden' name='noss' value='".$noss."'>
 <input type='hidden' name='email' value='".$email."'>
 <input type='hidden' name='name' value='".$name."'>
 <input type='hidden' name='tootal' value='".$tootal."'>
 <input type='hidden' name='current' value='".$currentPrice."'>
 <input type='hidden' name='native' value='".$nativePrice."'>
</form>
</center>";
mysqli_close($link);
?> 
<html>
<body>

	  
</body>
</html>