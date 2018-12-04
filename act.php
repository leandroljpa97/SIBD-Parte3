<html>
<body>
	<?php
	//VARIAVEIS RECEBIDAS
	$vato = $_REQUEST['vatowner'];
	$name = $_REQUEST['name'];
	$date = $_REQUEST['date'];
	$vata = $_REQUEST['vatassistant'];
	if ($vata=="choose"){
		$vata=null;
	}

	$indicators=$_REQUEST['indicator'];

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

	$flag = 0;
	$connection->beginTransaction();

	/////////////////////////// DAQUI PARA BAIXO TEMOS DE CONFIRMAR INSERÇÕES ///////////////////////////

	$sql = $connection->prepare("SELECT max(num) as max FROM proced
	WHERE name = :name AND VAT_owner = :VAT_owner AND date_timestamp = :date_timestamp;");
	if(!$sql->execute([':name' => $name, ':VAT_owner'=>$vato, ':date_timestamp'=>$date])){
		$flag = 1;
	}
	else {
		$result=$sql->fetch();
		if($result['max'] == NULL)
		$number = 1;
		else
		$number = $result['max'] + 1;

		$sql = "insert into proced values('$name', '$vato', '$date', $number, 'blood test');"; //adicionar descrição
		echo("SQL = $sql<br>");
		$connection->exec($sql);

		$sql = "insert into test_procedure values('$name', '$vato', '$date', $number, 'blood');";
		echo("SQL = $sql<br>");
		$connection->exec($sql);

		foreach ($indicators as $nome => $value){
			echo("<p>");
			if($value != null){
				$sql = "insert into produced_indicator values('$name', '$vato', '$date', $number, '$nome', $value);";
				echo("SQL = $sql<br>");
				$connection->exec($sql);
			}
			echo("</p>");
		}

		if ($vata != null){
			$sql = $connection->prepare("INSERT INTO performed
				VALUES(:name, :VAT_owner, :date_timestamp, :num, :VAT_assistant);");
				if(!$sql->execute([':name' => $name, ':VAT_owner'=>$vato, ':date_timestamp'=>$date, ':num'=>$number,':VAT_assistant'=>$vata]))
					$flag = 1;
			}
			//$connection->rollback();
			$connection->commit();
		}

		if($flag == 1){
			echo("There was a problem with the insertion. Try again.");
		}else {
			echo("Successfull insertion. Review your results:");
			$sql = $connection->prepare("SELECT indicator_name, value FROM produced_indicator
				WHERE name = :name AND VAT_owner = :VAT_owner AND date_timestamp = :date_timestamp AND num = :num;");
			$sql->execute([':name' => $name, ':VAT_owner'=>$vato, ':date_timestamp'=>$date, ':num'=>$number]);
			$results = $sql->fetchAll();
			echo("<table border=\"1\" cellpadding=\"4\">");
			echo("<tr><td>Indicator</td><td>Value</td></tr>");
			foreach($results as $row)
			{
			echo("<tr><td>");
			echo($row['indicator_name']);
			echo("</td><td>");
			echo($row['value']);
			echo("</td></tr>\n");
			}
			echo("</table>");
		}
		$connection = null;
		?>
		<form action='check.php' method='post'>
			<h3>Go back to homepage</h3>
			<p><input type='submit' value='Homepage'/></p>
		</body>
		</html>
