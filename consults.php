<html>
<body>
  <h3>Consults</h3>
  <?php
  session_start();
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

  if(isset($_REQUEST['name'])){
    $_SESSION['name'] = $_REQUEST['name'];
    $_SESSION['VAT_owner'] = $_REQUEST['VAT_owner'];
  }
  $VAT_client = $_SESSION['VAT_client'];
  $_SESSION['date_timestamp'] = date('Y-m-d H:i:s', time());
  $name = $_SESSION['name'];
  $VAT_owner = $_SESSION['VAT_owner'];

  $sql = "SELECT date_timestamp FROM consult WHERE name = '$name' AND VAT_owner = '$VAT_owner'";
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
    echo($name);
    echo("&VAT_owner=");
    echo($VAT_owner);
    echo("&date_timestamp=");
    echo($row['date_timestamp']);
    echo("\">View consult</a></td>\n");
    echo("</tr>\n");
  }
  echo("</table>\n");
  ?>

  <form action='addconsult.php' method='post'>
    <h3>Add another consult</h3>
    <p>VAT veterinary doctor: <input type='text' name='VAT_vet' required/></p>
    <p>Weight: <input type='number' name='weight' required /> kg</p>
    <p>S: <input type='text' name='s'/></p>
    <p>O: <input type='text' name='o'/></p>
    <p>A: <input type='text' name='a'/></p>
    <p>P: <input type='text' name='p'/></p>
    <p>Diagnosis:<br/>
      <?php
      $sql = "SELECT * FROM diagnosis_code";
      $result = $connection->query($sql);
      if ($result == FALSE)
      {
        $info = $connection->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
      }
      foreach($result as $row)
      {
        $code = $row['code'];
        $name = $row['name'];
        echo("<input type='checkbox' name='diagnosis[]' value='$code'/>$name<br/>");
      }

      $connection = null;
      ?></p>
      <p><input type='submit' value='Submit'/></p>
    </form>
  </body>
  </html>
