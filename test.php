<html>
<body>
	<?php
	//VARIAVEIS DE ESTADO
	$vatowner= $_REQUEST['VAT_owner'];
	$name= $_REQUEST['name'];
	$date= $_REQUEST['date_timestamp'];

	$host = "db.tecnico.ulisboa.pt";
	$user = "ist425496";
	$pass = "abjq7123";
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

	$sql="SELECT * FROM assistant";
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

	echo ("<h3> BLOOD TESTS RESULTS </h3><br>");
	echo ("<form action=\"act.php\" method=\"post\">");
	echo ("<input type=\"hidden\" id=\"vatowner\" name=\"vatowner\" value=\"$vatowner\">");
	echo ("<input type=\"hidden\" id=\"name\" name=\"name\" value=\"$name\">");
	echo ("<input type=\"hidden\" id=\"date\" name=\"date\" value=\"$date\">");
	echo ("<fieldset style='max-width:400px'><legend> Results of the Blood Tests </legend>");
	echo ("<label for=\"vatassistant\"> VAT of the assistant:</label>");
	echo ("<select id=\"vatassistant\" name=\"vatassistant\">");
	echo("<option value=\"escolha\">Choose</option>");
	foreach($assistants as $row){
		$vat=$row["VAT"];
		echo("<option value=\"$vat\">$vat</option>");
	}
	echo ("</select><br><br>");
	foreach($indicators as $row){
		$indicator = $row["name"];
		$units = $row["units"];
		echo("<p>$indicator: <input type='number' min='0' style='width:60px;' name='indicator[$indicator]'/> $units</p>");
	}
	echo ("<input type=\"submit\" value=\"Submit\">");
	echo ("</fieldset>");
	echo ("</form>");

	$connection = null;
	?>
	<form action='check.php' method='post'>
	<h3>Go back to homepage</h3>
	<p><input type='submit' value='Homepage'/></p>
</body>
</html>
