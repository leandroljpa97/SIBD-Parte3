<html>
<body>
	<?php
	//VARIAVEIS DE ESTADO
	$vatowner= (string) "00000005";
	$name= (string) "Goofy";
	$date= "2018-1-10 12:30:00.75";

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

	$sql="SELECT name FROM indicator";
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
	echo ("<fieldset><legend> Results of the Blood Tests </legend>");
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
		echo("<p>$indicator: <input type='number' min='0' name='indicator[$indicator]'/></p>");
	}
	echo ("<input type=\"submit\" value=\"Submit\">");
	echo ("</fieldset>");
	echo ("</form>");

	$connection = null;
	?>
</body>
</html>
