<html>
<body>
<?php
//variaveis de estado
$vato = (string) $_REQUEST['vatowner'];
$name = (string) $_REQUEST['name'];
$date = $_REQUEST['date'];
$vata = (string) $_REQUEST['vatassistant'];
if ($vata=="escolha"){
	$vata=null;
}
$indicators[0][0]="White Blood Cells";
$indicators[0][1]=(integer) $_REQUEST['whitebloodcells'];
$indicators[1][0]="Red Blood Cells";
$indicators[1][1]=(integer) $_REQUEST['redbloodcells'];
//$neutro = (integer) $_REQUEST['neutrophils'];
//$lympho = (integer) $_REQUEST['lymphocytes'];
//$mono = (integer) $_REQUEST['monocytes'];

//if (($white>0 || $white=null) && ($neutro>0 || $neutro=null) 
//	&& ($lympho>0 || $lympho=null) && ($mono>0 || $mono=null))
if (($indicators[0][1]>0 || $indicators[0][1]==null) && 
    ($indicators[1][1]>0 || $indicators[1][1]==null)){
	$host = "db.ist.utl.pt";
	$user = "ist425496";
	$pass = "abjq7123";
	$dsn = "mysql:host=$host;dbname=$user";
	try{ $connection = new PDO($dsn, $user, $pass);
	}catch(PDOException $exception) {
		echo("<p>Error: ");
		echo($exception->getMessage());
		echo("</p>");
		exit();
	}
	echo("<p>Form variables: $name $vato $date $vata</p>");
	$connection->beginTransaction();
	if ($vata != null){
		$sql = "insert into participation values('$name', '$vato', '$date', '$vata');";
		$connection->exec($sql);
		//echo ("<p>insertion = $sql</p>");
		$sql = "SELECT * FROM participation WHERE 
		name='$name' AND VAT_owner='$vato' AND date_timestamp='$date' AND VAT_assistant='$vata';";
		//echo ("<p>query = $sql</p>");
		$result = $connection->query($sql);
		$row = $result->fetch();
		$numb = $result->rowCount();
		//echo("<p>row count = $num</p>");
		if ($numb == 1){
			$newname=$row["name"];
			$newowner=$row["VAT_owner"];
			$newdate=$row["date_timestamp"];
			$newassist=$row["VAT_assistant"];
			echo("<p>Variables Inserted: $newname $newowner $newdate $newassist</p>");
			echo ("<p>This will be inserted:</p>");
			echo ("<table><tr><th>Name</th><th>VAT owner</th><th>Date</th><th>VAT assistant</th></tr>");
			echo ("<tr><td>$newname</td><td>$newowner</td><td>$newdate</td><td>$newassist</td></tr></table>");
		} else {
			echo("<p>Something happened: there are $num rows with the information added</p>");
			$connection->rollback();
			exit();
		}
	}
	$number=0;
	foreach ($indicators as $ind){
		//echo "Indicator Name = $ind[0] and Indicator Value = $ind[1]<br>";
		if($ind[1]!=0){
			$number=$number+1;
			echo("<p>");
			
			$sql = "insert into proced values('$name', '$vato', '$date', $number, 'testing $ind[0]');";
			echo("SQL = $sql<br>");
			$connection->exec($sql);
			
			$sql = "insert into test_procedure values('$name', '$vato', '$date', $number, 'blood');";
			echo("SQL = $sql<br>");
			$connection->exec($sql);
			
			$sql = "insert into produced_indicator values('$name', '$vato', '$date', $number , '$ind[0]', $ind[1]);";
			echo("SQL = $sql<br>");
			$connection->exec($sql);
			echo("</p>");			
		}
    }
	$sql="SELECT * FROM proced WHERE name='$name' AND VAT_owner='$vato' AND date_timestamp='$date';";
	echo("SQL = $sql<br>");
	$result = $connection->query($sql);
	$rows[0] = $result->fetchAll();
	$num[0] = $result->rowCount();
	//echo("Something is wrong... num=$num[0] and number=$number<br>");
	
	$sql="SELECT * FROM test_procedure WHERE name='$name' AND VAT_owner='$vato' AND date_timestamp='$date';";
	echo("SQL = $sql<br>");
	$result = $connection->query($sql);
	$rows[1] = $result->fetchAll();
	$num[1] = $result->rowCount();
	//echo("Something is wrong... num=$num[1] and number=$number<br>");
	
	$sql="SELECT * FROM produced_indicator WHERE name='$name' AND VAT_owner='$vato' AND date_timestamp='$date';";
	echo("SQL = $sql<br>");
	$result = $connection->query($sql);
	$rows[2] = $result->fetchAll();
	$num[2] = $result->rowCount();
	//echo("Something is wrong... num=$num[2] and number=$number<br>");
	
	$i=0;
	while($i!=3){
		if ($num[$i]==$number && $num[$i]>0){
			if($num[$i]==1){
				if($i==2){
					//echo("<p>Inserted: $rows[$i][\"name\"] $rows[$i][\"VAT_owner\"] $rows[$i][\"date_timestamp\"] $rows[$i][\"num\"] $rows[$i][\"indicator_name\"] $rows[$i][\"value\"]</p>");
					echo("<p>Inserted: $rows[$i][0] $rows[$i][1] $rows[$i][2] $rows[$i][3] $rows[$i][4] $rows[$i][5]</p>");
				}
				if($i==1){
					//echo("<p>Inserted: $rows[$i][\"name\"] $rows[$i][\"VAT_owner\"] $rows[$i][\"date_timestamp\"] $rows[$i][\"num\"] $rows[$i][\"type\"] </p>");
					echo("<p>Inserted: $rows[$i][0] $rows[$i][1] $rows[$i][2] $rows[$i][3] $rows[$i][4] </p>");
				}					
				if($i==0){
					//echo("<p>Inserted: $rows[$i][\"name\"] $rows[$i][\"VAT_owner\"] $rows[$i][\"date_timestamp\"] $rows[$i][\"num\"] $rows[$i][\"description\"] </p>");
					echo("<p>Inserted: $rows[$i][0] $rows[$i][1] $rows[$i][2] $rows[$i][3] $rows[$i][4] </p>");
				}
				//echo ("<table><tr><th>Name</th><th>VAT owner</th><th>Date</th><th>Num</th><th>Indicator Name</th><th>Value</th></tr>");
				//echo ("<tr><td>$rows[$i][\"name\"]</td><td>$rows[$i][\"VAT_owner\"]</td><td>$rows[$i][\"date_timestamp\"]</td>");
				//echo ("<td>$rows[$i][\"num\"]</td><td>$rows[$i][\"indicator_name\"]</td><td><$rows[$i][\"value\"]/td></tr></table>");
			} else {
				foreach($rows[$i] as $r){
					if($i==2){
						//echo("<p>Inserted: $r[\"name\"] $r[\"VAT_owner\"] $r[\"date_timestamp\"] $r[\"num\"] $r[\"indicator_name\"] $r[\"value\"]</p>");
						echo("<p>Inserted: $r[0] $r[1] $r[2] $r[3] $r[4] $r[5]</p>");
					}
					if($i==1){
						//echo("<p>Inserted: $r[\"name\"] $r[\"VAT_owner\"] $r[\"date_timestamp\"] $r[\"num\"] $r[\"type\"] </p>");
						echo("<p>Inserted: $r[0] $r[1] $r[2] $r[3] $r[4] </p>");
					}						
					if($i==0){
						//echo("<p>Inserted: $r[\"name\"] $r[\"VAT_owner\"] $r[\"date_timestamp\"] $r[\"num\"] $r[\"description\"] </p>");
						echo("<p>Inserted: $r[0] $r[1] $r[2] $r[3] $r[4] </p>");
					}
					//echo ("<table><tr><th>Name</th><th>VAT owner</th><th>Date</th><th>Num</th><th>Indicator Name</th><th>Value</th></tr>");
					//echo ("<tr><td>$r[$i][\"name\"]</td><td>$r[$i][\"VAT_owner\"]</td><td>$r[$i][\"date_timestamp\"]</td>");
					//echo ("<td>$r[$i][\"num\"]</td><td>$r[$i][\"indicator_name\"]</td><td><$r[$i][\"value\"]/td></tr></table>");
				}
			}
		}
		else{
			echo("Something is wrong... num=$num[$i] and number=$number<br>");
			$connection->rollback();
			exit();
		}
		$i=$i+1;
	}
	$connection->rollback();
	//$connection->commit();
	$connection = null;
} else {
	echo("<p>Please specify a positive amounts...</p>");
	echo ("<p><a href=\"test.php\" target=\"_self\"> Resubmit here </a></p>");
}
?>
</body>
</html>

