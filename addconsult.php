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

  $name = $_SESSION['name'];
  $date_timestamp= $_SESSION['date_timestamp'];
  $VAT_vet = $_REQUEST['VAT_vet'];
  $VAT_client=$_SESSION['VAT_client'];
  $VAT_owner=$_SESSION['VAT_owner'];
  $weight= $_REQUEST['weight'];
  $s = $_REQUEST['s'];
  $o=$_REQUEST['o'];
  $a=$_REQUEST['a'];
  $p=$_REQUEST['p'];
  $diagnosis= $_REQUEST['diagnosis'];

  $sql = $connection->prepare("INSERT into consult values(:name,:VAT_owner,:date_timestamp,:s,:o,:a,:p,:VAT_client,:VAT_vet,:weight);");
  $sql->execute([':name' => $name,
                  ':VAT_owner'=>$VAT_owner,
                  ':date_timestamp'=>$date_timestamp,
                  ':s'=>$s,
                  ':o'=>$o,
                  ':a'=>$a,
                  ':p'=>$p,
                  ':VAT_client'=>$VAT_client,
                  ':VAT_vet'=>$VAT_vet,
                  ':weight'=>$weight]);

  $result=$sql->fetchAll();

// isto está mal, mudar!!!!
  if ($result == 0) {
    $info = $sql->errorInfo();
    echo("<p>Error: The insertion was no success</p>");
    exit();
  }
  else
  {
    echo("<p>Consult inserted successfully</p>");
    echo(" <form action='check.php' method='post'>
    <h3>Come back to homepage</h3>
    <p><input type='submit' value='Homepage'/></p>
    </form>"); // desnecessário
    if(!empty($diagnosis)){
      $sql = $connection->prepare("INSERT into consult_diagnosis values(:code,:name,:VAT_owner,:date_timestamp);"); //ver se é possível preparar por partes, acho que não
        foreach ($diagnosis as $code) {
          $sql->execute([':name' => $name,
                          ':VAT_owner'=>$VAT_owner,
                          ':date_timestamp'=>$date_timestamp,
                          ':code'=>$code]);
        } // fazer o check
    }
    header("Location: consults.php");
  }

$connection = null;
  ?>
</body>
</html>
