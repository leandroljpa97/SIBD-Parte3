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

	/////////////////////////// DAQUI PARA BAIXO TEMOS DE CONFIRMAR INSERÇÕES ///////////////////////////

	$sql = $connection->prepare("SELECT max(num) as max FROM proced
	WHERE name = :name AND VAT_owner = :VAT_owner AND date_timestamp = :date_timestamp;");
	$sql->execute([':name' => $name,
	':VAT_owner'=>$vato,
	':date_timestamp'=>$date]);

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
			$sql = "insert into produced_indicator values('$name', '$vato', '$date', $number , '$nome', $value);";
			echo("SQL = $sql<br>");
			$connection->exec($sql);
		}
		echo("</p>");
	}
	////////////////////////// AQUI ACHO QUE JÁ NÃO ////////////////////////777
	if ($vata != null){
		$sql = $connection->prepare("INSERT INTO performed
			VALUES(':name', ':VAT_owner', ':date_timestamp', ':num', ':VAT_assistant');");
			$sql->execute([':name' => $name,
			':VAT_owner'=>$vato,
			':date_timestamp'=>$date,
			':num'=>$number,
			':VAT_assistant'=>$vata]);
			// tem de se confirmar a inserção aqui... porque pode já haver este. mas acho que é tranquilo
		}
		$connection->rollback();
		//$connection->commit();
		$connection = null;
		?>
		<form action='check.php' method='post'>
		<h3>Go back to homepage</h3>
		<p><input type='submit' value='Homepage'/></p>
	</body>
	</html>
