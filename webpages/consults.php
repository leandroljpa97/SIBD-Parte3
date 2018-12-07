<html>
<body>
  <h3>Consults</h3>
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
  $VAT_client = $_REQUEST['VAT_client'];
  $date_timestamp = date('Y-m-d H:i:s', time());

  $sql = $connection->prepare("SELECT date_timestamp FROM consult
    WHERE name = :name AND VAT_owner = :VAT_owner;");
  if($sql == FALSE){
      $info = $connection->errorInfo();
      echo("<p>Error: {$info[2]}</p>");
      exit();
  }

  $sql->execute([':name' => $name, ':VAT_owner'=>$VAT_owner]);
  $result=$sql->fetchAll();

  echo("<table border=\"0\" cellspacing=\"5\">\n");
  foreach($result as $row)
  {
    $date = $row['date_timestamp'];
    echo("<tr>\n");
    echo("<td>{$row['date_timestamp']}</td>\n");
    echo("<td><a href=\"consultdetails.php?name=$name &VAT_owner=$VAT_owner&date_timestamp=$date");
    echo("\">View consult</a></td>\n");
    echo("<td><a href=\"test.php?name=$name &VAT_owner=$VAT_owner&date_timestamp=$date");
    echo("\">Add blood test</a></td>\n");
    echo("</tr>\n");
  }
  echo("</table>\n");
  ?>
  <fieldset style='max-width:300px'><legend> Add another consult </legend>
  <p>Name: <?=$name?></p>
  <p>Owner VAT: <?=$VAT_owner?></p>
  <p>Date and time: <?=$date_timestamp?></p>
  <form action='addconsult.php' method='post'>
    <p>VAT veterinary doctor:
      <select name="VAT_vet">
        <?php
        $sql = "SELECT VAT FROM veterinary ORDER BY VAT";
        $result = $connection->query($sql);
        if ($result == FALSE)
        {
          $info = $connection->errorInfo();
          echo("<p>Error: {$info[2]}</p>");
          exit();
        }
        foreach($result as $row)
        {
          $VAT_vet = $row['VAT'];
          echo("<option value=\"$VAT_vet\">$VAT_vet</option>");
        }
        ?>
      </select>
    </p>
    <input type='hidden' name='name' value='<?=$name?>' />
    <input type='hidden' name='date_timestamp' value='<?=$date_timestamp?>' />
    <input type='hidden' name='VAT_owner' value='<?=$VAT_owner?>' />
    <input type='hidden' name='VAT_client' value='<?=$VAT_client?>' />
    <p>Weight: <input type='number' min="0.01" step="0.01" name='weight' style="width:60px;" required /> kg</p>
    <p>Subjective:</p>
    <p><textarea type='text' style="width:250px;height:100px;" name='s'></textarea></p>
    <p>Objective:</p>
    <p><textarea type='text' style="width:250px;height:100px;" name='o'></textarea></p>
    <p>Assessment:</p>
    <p><textarea type='text' style="width:250px;height:100px;" name='a'></textarea></p>
    <p>Plan:</p>
    <p><textarea type='text' style="width:250px;height:100px;" name='p'></textarea></p>
    <p>Assistants:</p>
      <?php
      $sql = "SELECT * FROM assistant";
      $result = $connection->query($sql);
      if ($result == FALSE)
      {
        $info = $connection->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
      }

      foreach($result as $row)
      {
        $vat = $row['VAT'];
        echo("<input type='checkbox' name='assistants[]' value='$vat'/>$vat<br/>");
      }
      ?>
    <p>Diagnosis:</p>
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
        $dname = $row['name'];
        echo("<input type='checkbox' name='diagnosis[]' value='$code'/>$dname<br/>");
      }

      $connection = null;
      ?>
    <p><input type='submit' value='Submit'/></p>
  </fieldset>
  </form>
  <form action='check.php' method='post'>
  <h3>Go back to homepage</h3>
  <p><input type='submit' value='Homepage'/></p>
</body>
</html>
