<html>
<body>
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

  $_SESSION['VAT_client'] = $_REQUEST['VAT_client'];
  $_SESSION['animal_name'] = $_REQUEST['animal_name'];

  $sqls = $connection->prepare("SELECT VAT FROM client WHERE VAT= :VAT_client");
  $sqls->execute([':VAT_client'=> $_SESSION['VAT_client']]);
  $result=$sqls->fetchAll();

  if ($result == 0) {
    $info = $sqls->errorInfo();
    echo("<p>Error: {$info[2]}</p>");
    exit();
  }
  $nrows = $sqls->rowCount();
  if ($nrows == 0)
  {
    echo("<p>There is no client with this VAT.</p>");
  }

  $sqls = $connection->prepare("SELECT distinct animal.name as an_name, animal.VAT,person.name
    FROM animal inner join person on animal.VAT=person.VAT
    WHERE animal.name=:animal_name and person.name like CONCAT('%',:owner_name,'%')");

  $sqls->execute([':animal_name' => $_SESSION['animal_name'],
  ':owner_name' => $_REQUEST['owner_name']]);

  $result_f=$sqls->fetchAll();

  if ($result_f == 0) {
    $info = $sqls->errorInfo();
    echo("<p>Error: {$info[2]}</p>");
    exit();
  }

  $nrows_an = $sqls->rowCount();

  if ($nrows_an == 0)
  {
    echo("<p>No animal found. Please fullfill the animal charactheristics </p>");
    echo(" <form action='insertanimal.php' method='post'>
    <h3>Animal information</h3>
    <p>Colour: <input type='text' name='colour'/></p>
    <p>Gender: <input type='text' name='gender'/></p>
    <p>Species name:<input type='text' name='species_name'/> </p>
    <p>Birth year: <input type='date' name='birth_year'/></p>
    <p>Age: <input type='text' name='age'/></p>
    <p><input type='submit' value='Submit'/></p>
    </form>"); // aqui no form Species devia ser um menu de opções. a age não faz sentido ele meter
  }

  else
  {
    echo("<p>Results found<p>");
    echo("<table border=\"1\" cellpadding=\"4\">");
    echo("<tr><td>name</td><td>VAT</td><td>animal name</td></tr>");
    foreach($result_f as $row)
    {
      echo("<tr><td>");
      echo($row['name']);
      echo("</td><td>");
      echo($row['VAT']);
      echo("</td><td>");
      echo($row['an_name']);
      echo("</td></td>"); //mostrar o resto dos dados
      echo("<td><a href=\"consults.php?name=");
      echo($row['an_name']);
      echo("&VAT_owner=");
      echo($row['VAT']);
      echo("\">View animal</a></td></tr>\n");

    }
    echo("</table>");
  }

  echo(" <form action='check.php' method='post'>
  <h3>Come back to homepage</h3>
  <p><input type='submit' value='Homepage'/></p>
  </form>");

  $connection = null;
  ?>
</body>
</html>
