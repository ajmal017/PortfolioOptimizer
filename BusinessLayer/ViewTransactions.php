<html>
<body>
<center>
<br><br>
<h1>Transactions</h1>
<?php
    $uid = $_POST['email'];
    $name = $_POST['name'];
    $netAssetValue=$_POST['netAssetValue'];
    $link = mysqli_connect('sql2.njit.edu', 'kn259', '7bI1DDsD', 'kn259');
    $result = mysqli_query($link, "SELECT * FROM Transaction where userID='".$uid."' and type='S'");
    $fp = fopen("/afs/cad/u/k/n/kn259/public_html/BusinessLayer/Download/portfolio.csv", "w");
    $fields = array('Symbol','Number of Shares','Currency','Buy/Sell Price USD','Buy/Sell Price Native Currency','Total Amount USD','Total Amount Native','Transaction');
    fputcsv($fp, $fields);
	while($row = $result->fetch_array()) 
    {
    $ar = array($row['symbol'],$row['numberofShares'],$row['currency'],$row['BuyOrSellPrice'],$row['BuyORSellNPRice'],$row['TotalUSD'],$row['TotalNative'],$row['trans']);     
    fputcsv($fp,$ar);
    }
    $empty= array();
    fputcsv($fp,$empty);
    fputcsv($fp,$empty);
    $netValAr = array('Net Asset Value:', $netAssetValue);
    fputcsv($fp,$netValAr);
    mysqli_close($link);
    fclose($fp);
?>
<br><br><br><br>
<form action="DownloadCSV.php">
    <input type="submit" value="Export to CSV">
</form>
<br>
<br>
<form method="POST" action="../WelcomePage.php">
	<input type="hidden" name="email" value=<?php echo $uid ?>>
	<input type="hidden" name="name" value=<?php echo $name ?>>
	<input name ="Return" type = "submit" value="Return to Home">
	</form>
</center>
</body>
</html>