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

  $name = $_REQUEST['name'];
  $date_timestamp= $_REQUEST['date_timestamp'];
  $VAT_vet = $_REQUEST['VAT_vet'];
  $VAT_client=$_REQUEST['VAT_client'];
  $VAT_owner=$_REQUEST['VAT_owner'];
  $weight= $_REQUEST['weight'];
  $s = $_REQUEST['s'];
  $o=$_REQUEST['o'];
  $a=$_REQUEST['a'];
  $p=$_REQUEST['p'];
  $diagnosis= $_REQUEST['diagnosis'];

  $sqls = $connection->prepare("INSERT into consult values(:name,:VAT_owner,:date_timestamp,:s,:o,:a,:p,:VAT_client,:VAT_vet,:weight);");

  // INSERT into consult_diagnosis values(:code,:name,:VAT_owner,:date_timestamp);
  $sqls->execute([':name' => $name,
                  ':VAT_owner'=>$VAT_owner,
                  ':date_timestamp'=>$date_timestamp,
                  ':s'=>$s,
                  ':o'=>$o,
                  ':a'=>$a,
                  ':p'=>$p,
                  ':VAT_client'=>$VAT_client,
                  ':VAT_vet'=>$VAT_vet,
                  ':weight'=>$weight]);

  $result_f=$sqls->fetchAll();

// isto está mal, mudar!!!!
  if ($result_f == 0) {
    $info = $sqls->errorInfo();
    echo("<p>Error: The insertion was no success</p>");
  }
  else
  {
    echo("<p>Consult inserted successfully</p>");
    echo(" <form action='check.php' method='post'>
    <h3>Come back to homepage</h3>
    <p><input type='submit' value='Homepage'/></p>
    </form>");
    if(!empty($diagnosis)){
      $sqls = $connection->prepare("INSERT into consult_diagnosis values(:code,:name,:VAT_owner,:date_timestamp);"); //ver se é possível preparar por partes, acho que não
        foreach ($diagnosis as $code) {
          $sqls->execute([':name' => $name,
                          ':VAT_owner'=>$VAT_owner,
                          ':date_timestamp'=>$date_timestamp,
                          ':code'=>$code]);
        } // fazer o check
    }




  }

$connection = null;
  ?>
</body>
</html>
