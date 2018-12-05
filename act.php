<html>
<body>
	<?php
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

	$VAT_owner = $_REQUEST['vatowner'];
	$name = $_REQUEST['name'];
	$date_timestamp = $_REQUEST['date'];
	$VAT_assistant = $_REQUEST['vatassistant'];
	if ($VAT_assistant=="choose"){
		$VAT_assistant=null;
	}
	$indicators=$_REQUEST['indicator'];

	$connection->beginTransaction();

	$ok = 0;
	while($ok == 0){

		$sql = $connection->prepare("SELECT max(num) as max FROM proced
			WHERE name = :name AND VAT_owner = :VAT_owner AND date_timestamp = :date_timestamp;");
		if($sql == FALSE){
			break;
		}

		$test = $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp]);
		if($test == FALSE){
			break;
		}

		$result=$sql->fetch();
		if($result['max'] == NULL){
			$number = 1;
		}
		else{
			$number = $result['max'] + 1;
		}

		$sql= $connection->prepare("INSERT INTO proced VALUES(:name, :VAT_owner, :date_timestamp, :num, 'blood test');");
		if($sql == FALSE){
			break;
		}

		$test = $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp, ':num'=>$number ]);
		if($test == FALSE){
			break;
		}

		$sql= $connection->prepare("INSERT INTO test_procedure VALUES(:name, :VAT_owner, :date_timestamp, :num, 'blood');");
		if($sql == FALSE){
			break;
		}

		$test = $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp, ':num'=>$number ]);
		if($test == FALSE){
			break;
		}

		$ninsert=0;
		foreach($indicators as $nome => $value){
			echo("<p>");
			if($value != null){
				$sql = $connection->prepare("INSERT INTO produced_indicator VALUES(:name, :VAT_owner, :date_timestamp, :num, :indicator_name, :indicator_value);");
				if($sql == FALSE){
					break 2;
				}

				$test = $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp, ':num'=>$number, ':indicator_name'=>$nome, ':indicator_value'=>$value]);
				if($test == FALSE){
					break 2;
				}
				$ninsert++;
			}
			echo("</p>");
		}

		if ($ninsert==0){
			echo("No data was given so nothing was inserted in the database.");
			$connection->rollback;
			$ok = -1;
		}

		if ($VAT_assistant != null){
			$sql = $connection->prepare("INSERT INTO performed
				VALUES(:name, :VAT_owner, :date_timestamp, :num, :VAT_assistant);");
			if($sql == FALSE){
				break;
			}

			$test = $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp, ':num'=>$number,':VAT_assistant'=>$VAT_assistant]);
			if($test == FALSE)
				break;
			}
			//$connection->rollback();
			$connection->commit();
			$ok = 1;
	}

	if($ok == 0){
		echo("<p>There was a problem with the insertion. Try again.</p>");
		echo("<p>Error: {$info[2]}</p>");
	}else if($ok == 1){
		echo("<p>Successfull insertion");
		$sql = $connection->prepare("SELECT indicator_name, value FROM produced_indicator
			WHERE name = :name AND VAT_owner = :VAT_owner AND date_timestamp = :date_timestamp AND num = :num;");
		if($sql == FALSE){
			echo(" but we are unable to show you the results.</p>");
			echo("<p>Error: {$info[2]}</p>");
		} else {
			$sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp, ':num'=>$number]);

			echo(". Review your results:</p>");
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
	}
	$connection = null;
	?>
	<form action='check.php' method='post'>
		<h3>Go back to homepage</h3>
		<p><input type='submit' value='Homepage'/></p>
</body>
</html>
