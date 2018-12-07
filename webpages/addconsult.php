<html>
<body>
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
  $assistants= $_REQUEST['assistants'];

  $sql = $connection->prepare("INSERT into consult values(:name, :VAT_owner, :date_timestamp, :s, :o, :a, :p, :VAT_client, :VAT_vet, :weight);");
  if($sql == FALSE){
    $info = $connection->errorInfo();
    echo("<p>Error: {$info[2]}</p>");
    exit();
  }

  $test = $sql->execute([':name' => $name,
                         ':VAT_owner'=>$VAT_owner,
                         ':date_timestamp'=>$date_timestamp,
                         ':s'=>$s,
                         ':o'=>$o,
                         ':a'=>$a,
                         ':p'=>$p,
                         ':VAT_client'=>$VAT_client,
                         ':VAT_vet'=>$VAT_vet,
                         ':weight'=>$weight]);

  if($test == FALSE){
     $info = $connection->errorInfo();
     echo("<p>Error: {$info[2]}</p>");
     exit();
  }

  if(!empty($assistants)){

    $sql = $connection->prepare("INSERT into participation values(:name, :VAT_owner, :date_timestamp, :VAT_assistant);");
    if($sql == FALSE){
        $info = $connection->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
    }

    foreach ($assistants as $vat) {
      $test = $sql->execute([':name' => $name,
                             ':VAT_owner'=>$VAT_owner,
                             ':date_timestamp'=>$date_timestamp,
                             ':VAT_assistant'=>$vat]);
      if($test == FALSE){
        $info = $connection->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
      }
    }
  }

  if(!empty($diagnosis)){

    $sql = $connection->prepare("INSERT into consult_diagnosis values(:code,:name,:VAT_owner,:date_timestamp);");
    if($sql == FALSE){
        $info = $connection->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
    }

    foreach ($diagnosis as $code) {
      $test = $sql->execute([':name' => $name,
                             ':VAT_owner'=>$VAT_owner,
                             ':date_timestamp'=>$date_timestamp,
                             ':code'=>$code]);
      if($test == FALSE){
        $info = $connection->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
      }
    }
  }

  if($sql == TRUE && $test == TRUE){
    header("Location: consults.php?name=$name&VAT_owner=$VAT_owner&VAT_client=$VAT_client");
  }
  $connection = null;
  ?>
</body>
</html>
