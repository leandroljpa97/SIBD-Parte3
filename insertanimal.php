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

  $species_name = $_REQUEST['species_name'];
  $colour = $_REQUEST['colour'];
  $gender= $_REQUEST['gender'];
  $birth_year = $_REQUEST['birth_year'];
  $age=$_REQUEST['age'];
  $VAT_owner=$_REQUEST['VAT_owner'];


  $sqls = $connection->prepare("INSERT into animal values(:animal_name,:VAT_owner,:species_name,:colour,:gender,:birth_year,:age)");

  $sqls->execute([':animal_name' => $_SESSION['animal_name'],
  ':species_name'=>$species_name,
  ':VAT_owner'=>$_SESSION['VAT_client'],
  ':colour'=>$colour,
  ':gender'=>$gender,
  ':birth_year'=>$birth_year,
  ':age'=>$age]);

  $result_f=$sqls->fetchAll();

  if ($result_f == 0) {
    $info = $sqls->errorInfo();
    echo("<p>Error: The insertion was no success</p>");
  }

  else
  {
    header("Location: animals.php");
    echo("<p>Animal inserted successfully</p>");
    echo(" <form action='check.php' method='post'>
    <h3>Come back to homepage</h3>
    <p><input type='submit' value='Homepage'/></p>
    </form>");
  }

  ?>
</body>
</html>
