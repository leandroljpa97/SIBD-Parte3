<html>
<body>
	<?php
	$host = "db.tecnico.ulisboa.pt";
	$user = "istxxxxxx";
  $pass = "xxxxxxxx";
	$dsn = "mysql:host=$host;dbname=$user";
	try
	{
		$connection = new PDO($dsn, $user, $pass);
	}
	catch(PDOException $exception)
	{
		echo("<p>Error: ");
		echo($exception->getMessage());
		echo("</p>");
		exit();
	}

	$VAT_owner= $_REQUEST['VAT_owner'];
	$name= $_REQUEST['name'];
	$date_timestamp= $_REQUEST['date_timestamp'];

	$sql="SELECT VAT FROM assistant";
	$result=$connection->query($sql);
	if ($result == FALSE)
	{
		$info = $connection->errorInfo();
		echo("<p>Error: {$info[2]}</p>");
		exit();
	}
	$assistants=$result->fetchAll();

	$sql="SELECT name, units FROM indicator";
	$result=$connection->query($sql);
	if ($result == FALSE)
	{
		$info = $connection->errorInfo();
		echo("<p>Error: {$info[2]}</p>");
		exit();
	}
	$indicators=$result->fetchAll();

	$connection = null;
	?>
	<h3>Blood Test</h3><br>
	<form action="act.php" method="post">
	<input type="hidden" id="vatowner" name="vatowner" value='<?=$VAT_owner?>'>
	<input type="hidden" id="name" name="name" value='<?=$name?>'>
	<input type="hidden" id="date" name="date" value='<?=$date_timestamp?>'>
	<fieldset style='max-width:400px'><legend> Results of the Blood Tests </legend>
	<p><label for="vatassistant"> VAT of the assistant:</label></p>
	<?php
	foreach($assistants as $row){
		$vat=$row["VAT"];
		echo("<input type='checkbox' name='assistants[]' value='$vat'/>$vat<br/>");
	}
	echo("<p>Description:</p><p><textarea type='text' style='width:250px;height:100px;' name='description'></textarea></p>");
	foreach($indicators as $row){
		$indicator = $row["name"];
		$units = $row["units"];
		echo("<p>$indicator: <input type='number' min='0' style='width:60px;' name='indicator[$indicator]'/> $units</p>");
	}
	?>
	<input type="submit" value="Submit">
	</fieldset>
	</form>
	<form action='check.php' method='post'>
	<h3>Go back to homepage</h3>
	<p><input type='submit' value='Homepage'/></p>
</body>
</html>
