<html>
<body>
<h1 align="center">MCKT</h1>
<br><br><br><br>
<?php
 $email=$_POST['email'];
 $name=$_POST['name'];
 $Symbol = strtoupper($_POST['Symbol']);
  $StockExchange =$_POST['StockExchange'];
  If($StockExchange=='NASDAQ')
	 {
		 $indx="DOW";
	 }
	 elseif($StockExchange=="NSE")
	 {
		 $indx="NIFTY";
	 }
	 elseif($StockExchange=="SGX")
	 {
		 $indx="STI";
	 }
	 elseif($StockExchange=="ADR")
	 {
		 $indx="ADR";
	 }
	 
	$link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
	$queryI=mysqli_query($link, "select indx from Indexsymbol where symbol='".$Symbol."'");
	 $row2=$queryI->fetch_array();
	 $s=$row2['indx'];
	 if($s==$indx)
	 {
		 
	 }
	 else
	 {
		 $styl = "display:none";
		 echo "<center>You cannot buy this Stock. Please choose another</center>";
		 echo "<center>
	<br>
	<form method='POST' action='buy.php'>
	<input type='hidden' name='email' value=".$email." >
	<input type='hidden' name='name' value=".$name.">
	<input name ='Return' type = 'submit' value='Return to Buy'>
	</form>
	</center>";
		 
	 }
  //Obtain Quote Info
  
  $quote = file_get_contents('http://finance.google.com/finance/info?client=ig&q='.$StockExchange.":".$Symbol);
  //Remove CR's from ouput - make it one line
    $json = str_replace("\n", "", $quote);

  //Remove //, [ and ] to build qualified string  
    $data = substr($json, 4, strlen($json) -5);

  //decode JSON data
    $json_output = json_decode(utf8_decode($data));

  // get the last price
    $lastPrice = $json_output->l;
	$exchange = $_POST['StockExchange'];
	$noOfShares =$_POST['NumberofShares'];
	$Currency='USD';
	if($exchange=="NSE")
	 {
		 $Currency="INR";
	 }
	 elseif($exchange=="SGX")
	 {
		 $Currency="SGD";
		 
	 }
	 
?>

    <form method="POST" action="../DataLayer/Buydbms.php">
		<div style=<?php echo $styl ;?>>
		<table align="center">
		<tr>
		<td>
         StockExchange
		 </td>
		 <td>
		 <input list = "StockExchange" name = "StockExchange"type = "text" value=<?php echo $exchange?> id="pr" readonly></td>
		 </td>
         <datalist id="StockExchange">
            <option value="NASDAQ"/>DOW30 USD</option>
            <option value="NSE"/>NIFTY50 INR</option>
            <option value="SGX"/>STI SGD</option>
			<option value="ADR"/>ADR USD</option>
        </datalist>
		</tr>
		<tr>
		<td>
         Symbol
		 </td>
		 <td>
		 <input name = "Symbol" type = "text" id="sr" value=<?php echo $Symbol?> readonly>
		 </td>
		 </tr>
		 <tr>
		 <td>
         NumberofShares
		 </td>
		 <td>
		 <input name = "NumberofShares" type ="text" id="sd" value=<?php echo $noOfShares ?> readonly>
		 </td>
		 </tr>
		 <?php 
		 $hisq = mysqli_query($link, "select * from Stock where userID='".$email."' and symbol='".$Symbol."'");
		 $hisp = mysqli_query($link, "select * from Transaction where userID='".$email."' and symbol='".$Symbol."'");
                $oltran = 0;
			if(mysqli_num_rows($hisq)==0 && mysqli_num_rows($hisp)==0)
			{ 
                $oltran = 1;
				if($indx=='NIFTY')
				{
					$Symbol1=$Symbol.".NS";
				}
				elseif($indx=='STI')
				{
					$Symbol1=$Symbol.".SI";
				}
				$Symbol1= $Symbol;
				$file = file('http://chart.finance.yahoo.com/table.csv?s='.$Symbol1.'&a=8&b=12&c=2016&d=8&e=12&f=2016&g=d&ignore=.csv');
				foreach($file as $k)
				{
					$csv[]= explode(',',$k);
					$lastPrice= $csv[1][4];
				}
			}
		if($Currency=='INR')				
				{
                   
					$xchang = file_get_contents('https://www.exchangerate-api.com/USD/INR?k=1a29aab047894198cd188e14

');
            $lastPrice = str_replace(",","",$lastPrice);

					$data = json_decode($xchang,true);               
					$ulastPrice=round($lastPrice/$data,2);
				}
		elseif($Currency=='SGD')
				{
					$xchang = file_get_contents('https://www.exchangerate-api.com/USD/SGD?k=1a29aab047894198cd188e14

');
            $lastPrice = str_replace(",","",$lastPrice);
					$data = json_decode($xchang,true);
					$ulastPrice= round($lastPrice/($data),2);
				}
		else
		{
			$ulastPrice=$lastPrice;
		}
		mysqli_close($link);
		?>
		<td>
			Current Price:
		</td>
		<td>
		<input type="text" name="uprice" value= <?php echo $ulastPrice ?> readonly>
		</td>
		 <tr>
		 <td>
		 Native price:
		 </td>
		 <td>
         <input type="text" id="price" name="price" value= <?php echo $lastPrice ?> readonly>
		 </td>
		 </tr>
		 <tr>
		 <td>
		 Total Price
		 </td>
		 <td>
         <input type="text" id="tprice" name="tprice" value= <?php echo ($ulastPrice*$noOfShares) ?> readonly>
		 </td>
		 </tr>
		 <tr>
		 <td>
         <input name ="Buy" type = "submit" value="Buy">
		 </td>
		 </tr>
		 </table>
		 </div>
    <input type="hidden" name="email" value=<?php echo $email ?>>
    <input type="hidden" name="name" value=<?php echo $name ?>>
    <input type='hidden' name='oltran' value=<?php echo $oltran ?>>
     </form>
</body>
</html>