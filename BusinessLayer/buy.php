<?php
    $email=$_POST['email'];
	$name=$_POST['name'];
?>
<html>
<body>
		<h1 align="center">MCKT</h1>
        <form method="POST" action="buycheck.php">
		<input type="hidden" name="email" value=<?php echo $email ?>>
        <input type="hidden" name="name" value=<?php echo $name ?>>
		<table align="center">
		<tr>
		<td>
         StockExchange
		 </td>
		 <td><input list = "StockExchange" name = "StockExchange" type = "text"  id="pr"></td>
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
		 <input name = "Symbol" type = "text" id="sr">
		 </td>
		 </tr>
		 <tr>
		 <td>
         NumberofShares
		 </td>
		 <td>
		 <input name = "NumberofShares" type ="text" id="sd">
		 </td>
		 </tr>
		 <tr>
		 <td>
         <input name ="Add" type = "submit">
		 </td>
		 </tr>
		 </table>
     </form>
    <p id="empty"></p>
</body>
</html>