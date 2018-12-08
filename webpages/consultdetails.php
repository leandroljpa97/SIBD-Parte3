<html>
<body>
  <h3>Consult: <?=$_REQUEST['date_timestamp']?></h3>
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

  $name = $_REQUEST['name'];
  $VAT_owner = $_REQUEST['VAT_owner'];
  $date_timestamp = $_REQUEST['date_timestamp'];

  $sql = $connection->prepare("SELECT * FROM animal WHERE name = :name AND VAT = :VAT_owner;");
  if($sql == FALSE){
      $info = $connection->errorInfo();
      echo("<p>Error: {$info[2]}</p>");
      exit();
  }
  $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner]);
  $row=$sql->fetch();
  ?>

  <table border="0" cellspacing="5">
  <tr><td>Name: <?=$row['name']?></td></tr>
  <tr><td>Owner's VAT: <?=$row['VAT']?></td></tr>
  <tr><td>Species: <?=$row['species_name']?></td></tr>
  <tr><td>Gender: <?=$row['gender']?></td></tr>
  <tr><td>Colour: <?=$row['colour']?></td></tr>
  <tr><td>Age: <?=$row['age']?></td></tr>
  <tr><td>Birthday: <?=$row['birth_year']?></td></tr>

  <?php
  $sql = $connection->prepare("SELECT s, o, a, p, VAT_client, VAT_vet, weight
    FROM consult WHERE name = :name AND VAT_owner = :VAT_owner
    AND date_timestamp = :date_timestamp;");
  if($sql == FALSE){
      $info = $connection->errorInfo();
      echo("<p>Error: {$info[2]}</p>");
      exit();
  }
  $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp]);
  $row=$sql->fetch();
  ?>

  <tr><td>Vet's VAT: <?=$row['VAT_vet']?></td></tr>
  <tr><td>Client's VAT: <?=$row['VAT_client']?></td></tr>
  <tr><td>Weight: <?=$row['weight']?></td></tr>
  </table>
  <h4>SOAP notes</h4>
  <table border="0" cellspacing="5">
  <tr><td>Subjective: <?=$row['s']?></td></tr>
  <tr><td>Objective: <?=$row['o']?></td></tr>
  <tr><td>Assessment: <?=$row['a']?></td></tr>
  <tr><td>Plan: <?=$row['p']?></td></tr>
  </table>

  <?php
  $sql = $connection->prepare("SELECT VAT_assistant FROM participation
		WHERE name = :name AND VAT_owner = :VAT_owner AND date_timestamp = :date_timestamp;");
	if($sql == FALSE){
		$info = $connection->errorInfo();
		echo("<p>Error: {$info[2]}</p>");
		exit();
	}

	$sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp]);

	$result = $sql->fetchAll();

  if ($result != FALSE)
  {
    echo("<h4>Assistants:</h4>\n");
    foreach($result as $row)
    {
      echo("<table border=\"0\" cellspacing=\"5\">\n");
      echo("<tr><td>{$row['VAT_assistant']}</td></tr>\n");
    }
    echo("</table>\n");
  }

  $sql = $connection->prepare("SELECT code, d.name FROM consult_diagnosis
    inner join diagnosis_code as d using(code) WHERE consult_diagnosis.name = :name
    AND VAT_owner = :VAT_owner AND date_timestamp = :date_timestamp;");
  if($sql == FALSE){
      $info = $connection->errorInfo();
      echo("<p>Error: {$info[2]}</p>");
      exit();
  }
  $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp]);

  $result=$sql->fetchAll();

  if ($result != FALSE)
  {
    echo("<h4>Diagnosis codes:</h4>\n");
    foreach($result as $row)
    {
      echo("<table border=\"0\" cellspacing=\"5\">\n");
      echo("<tr><td>{$row['code']} &ndash; {$row['name']}</td></tr>\n");
    }
    echo("</table>\n");
  }

  $sql = $connection->prepare("SELECT code, name_med, lab, dosage, regime
    FROM prescription WHERE name = :name
    AND VAT_owner = :VAT_owner AND date_timestamp = :date_timestamp;");
  if($sql == FALSE){
      $info = $connection->errorInfo();
      echo("<p>Error: {$info[2]}</p>");
      exit();
  }
  $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner, ':date_timestamp'=>$date_timestamp]);
  $result=$sql->fetchAll();

  if ($result != FALSE)
  {
    echo("<h4>Prescriptions for each diagnosis:</h4>\n");
    foreach($result as $row)
    {
      echo("<table border=\"0\" cellspacing=\"5\">\n");
      echo("<tr><td>{$row['code']}: {$row['name_med']} | {$row['lab']} | {$row['dosage']} | {$row['regime']}</td></tr>\n");
    }
    echo("</table>\n");
  }

  $connection = null;
  ?>
  <form action='test.php' method='post'>
  </form>
  <form action='check.php' method='post'>
  <h3>Go back to homepage</h3>
  <p><input type='submit' value='Homepage'/></p>
</body>
</html>
