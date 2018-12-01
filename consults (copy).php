<html>
<body>
  <h3>Consults</h3>
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

  $name = 'Puma';
  $VAT_owner = '00000000';
  $VAT_client = '00000000'; // de onde vem isto?
  $date_timestamp = date('Y-m-d H:i:s', time()); // e isto? é a atual??

  $sql = "SELECT * FROM consult WHERE name = '$name' AND VAT_owner = '$VAT_owner'";
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
    echo("<tr>\n");
    echo("<td>{$row['date_timestamp']}</td>\n");
    echo("<td><a href=\"consultdetails.php?name=");
    echo('Puma');
    echo("&VAT_owner=");
    echo('00000000');
    echo("&date_timestamp=");
    echo($row['date_timestamp']);
    echo("\">View consult</a></td>\n");
    echo("</tr>\n");
  }
  echo("</table>\n");
  $connection = null;

  // add consult
  echo("  <form action='addconsult.php' method='post'>
      <h3>Add another consult</h3>
      <p><input type='hidden' name='name' value='$name'/></p>
      <p><input type='hidden' name='VAT_owner' value='$VAT_owner'/></p>
      <p><input type='hidden' name='date_timestamp' value='$date_timestamp'/></p>
      <p><input type='hidden' name='VAT_client' value='$VAT_client'/></p>
      <p>VAT veterinary doctor: <input type='text' name='VAT_vet' required/></p>
      <p>Weight: <input type='number' name='weight' required /> kg</p>
      <p>S: <input type='text' name='s'/></p>
      <p>O: <input type='text' name='o'/></p>
      <p>A: <input type='text' name='a'/></p>
      <p>P: <input type='text' name='p'/></p>
      <p>Diagnosis code: <input type='text' name='code'/></p> <!-- confirmar se é este tipo de input. será dos de selecionar? e devia dar para vários-->
      <p><input type='submit' value='Submit'/></p>
    </form>");
  ?>
</body>
</html>
