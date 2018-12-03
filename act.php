<html>
<body>
	<?php
	//VARIAVEIS RECEBIDAS
	$vato = $_REQUEST['vatowner'];
	$name = $_REQUEST['name'];
	$date = $_REQUEST['date'];
	$vata = $_REQUEST['vatassistant'];
	if ($vata=="Choose"){
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

	$connection->beginTransaction();
	if ($vata != null){
		$sql = $connection->prepare("INSERT INTO participation
			VALUES(':name', ':VAT_owner', ':date_timestamp', ':VAT_assistant');");
		$sql->execute([':name' => $name,
										':VAT_owner'=>$vato,
										':date_timestamp'=>$date,
										':VAT_assistant'=>$vata]);
		// tem de se confirmar a inserção aqui... porque pode já haver este. mas acho que é tranquilo
	}

	$sql = $connection->prepare("SELECT max(num) FROM proced
		WHERE name = :name AND VAT_owner = :VAT_owner AND :date_timestamp = :date_timestamp;");
	$sql->execute([':name' => $name,
									':VAT_owner'=>$vato,
									':date_timestamp'=>$date]);

	$result=$sql->fetch();
	$number = $result[0] + 1;

	$sql = "insert into proced values('$name', '$vato', '$date', $number, 'blood test');"; //adicionar descrição
	echo("SQL = $sql<br>");
	$connection->exec($sql);

	$sql = "insert into test_procedure values('$name', '$vato', '$date', $number, 'blood');";
	echo("SQL = $sql<br>");
	$connection->exec($sql);

	foreach ($indicators as $nome => $value){
			echo("<p>");
			if($value != 0){
				$sql = "insert into produced_indicator values('$name', '$vato', '$date', $number , '$nome', $value);";
				echo("SQL = $sql<br>");
				$connection->exec($sql);
			}
			echo("</p>");
	}

	$connection->rollback();
	//$connection->commit();
	$connection = null;
?>
</body>
</html>
