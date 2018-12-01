<html>
<body>
  <h3>Consult: <?=$_REQUEST['date_timestamp']?></h3>
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
  $name = $_REQUEST['name'];
  $VAT_owner = $_REQUEST['VAT_owner'];
  $date_timestamp = $_REQUEST['date_timestamp'];

  $sql = "SELECT * FROM animal WHERE name = '$name' AND VAT = '$VAT_owner'";
  $result = $connection->query($sql);
  if ($result == FALSE)
  {
    $info = $connection->errorInfo();
    echo("<p>Error: {$info[2]}</p>");
    exit();
  }
  echo("<table border=\"0\" cellspacing=\"5\">\n");
  foreach($result as $row)
  {
    echo("<tr><td>Name: {$row['name']}</td></tr>\n");
    echo("<tr><td>Owner's VAT: {$row['VAT']}</td></tr>\n");
    echo("<tr><td>Species: {$row['species_name']}</td></tr>\n");
    echo("<tr><td>Gender: {$row['gender']}</td></tr>\n");
    echo("<tr><td>Colour: {$row['colour']}</td></tr>\n");
    echo("<tr><td>Age: {$row['age']}</td></tr>\n");

  }

  $sql = "SELECT * FROM consult WHERE name = '$name' AND VAT_owner = '$VAT_owner' AND date_timestamp = '$date_timestamp'";
  $result = $connection->query($sql);
  if ($result == FALSE)
  {
    $info = $connection->errorInfo();
    echo("<p>Error: {$info[2]}</p>");
    exit();
  }

  foreach($result as $row)
  {
    echo("<tr><td>Weight: {$row['weight']}</td></tr>\n"); // weight desta consulta ou o mais recente do animal? suponho que desta
    echo("</table>\n");
    echo("<h4>SOAP notes</h4>\n");
    echo("<table border=\"0\" cellspacing=\"5\">\n");
    echo("<tr><td>S: {$row['s']}</td></tr>\n");
    echo("<tr><td>O: {$row['o']}</td></tr>\n");
    echo("<tr><td>A: {$row['a']}</td></tr>\n");
    echo("<tr><td>P: {$row['p']}</td></tr>\n");
  }
  echo("</table>\n");

  $sql = "SELECT * FROM consult_diagnosis WHERE name = '$name' AND VAT_owner = '$VAT_owner' AND date_timestamp = '$date_timestamp'";
  $result = $connection->query($sql);
  if ($result == FALSE)
  {
    $info = $connection->errorInfo();
    echo("<p>Error: {$info[2]}</p>"); // testar aqui se ele verifica se há resultados!!
    exit();
  }


  echo("<h4>Diagnosis codes:</h4>\n");
  foreach($result as $row)
  {
    echo("<table border=\"0\" cellspacing=\"5\">\n");
    echo("<tr><td>{$row['code']}</td></tr>\n");
  }
  echo("</table>\n");

  $sql = "SELECT * FROM prescription WHERE name = '$name' AND VAT_owner = '$VAT_owner' AND date_timestamp = '$date_timestamp'";
  $result = $connection->query($sql);
  if ($result == FALSE)
  {
    $info = $connection->errorInfo();
    echo("<p>Error: {$info[2]}</p>");
    exit();
  }

  echo("<h4>Prescriptions for each diagnosis:</h4>\n");
  foreach($result as $row) // fazer aqui tabela!
  {
    echo("<table border=\"0\" cellspacing=\"5\">\n");
    echo("<tr><td>{$row['code']}: {$row['name_med']} | {$row['lab']} | {$row['dosage']} | {$row['regime']}</td></tr>\n");
  }
  echo("</table>\n");

  $connection = null;
  ?>
</body>
</html>
