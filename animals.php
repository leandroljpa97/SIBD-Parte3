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
  if(isset($_REQUEST['VAT_client'])){
    $_SESSION['VAT_client'] = $_REQUEST['VAT_client'];
    $_SESSION['animal_name'] = $_REQUEST['animal_name'];
    $_SESSION['owner_name'] = $_REQUEST['owner_name'];
  }
  
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

  else 
  {

        $sqls = $connection->prepare("SELECT distinct animal.name as an_name, animal.VAT,person.name as name_owner
        FROM animal inner join person on animal.VAT=person.VAT
        WHERE animal.name=:animal_name and person.name like CONCAT('%',:owner_name,'%')");
      $sqls->execute([':animal_name' => $_SESSION['animal_name'],
      ':owner_name' => $_SESSION['owner_name']]);
      $result_f=$sqls->fetchAll();
      if ($result_f == 0) {
        $info = $sqls->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
      }
      $nrows_an = $sqls->rowCount();
      if ($nrows_an == 0)
      {
        //-----------------MOSTRAR A INFORMAÇÃO SÓ DO CLIENTE --------------------------------------------------


        $sqly = $connection->prepare("SELECT distinct animal.name as an_name, animal.VAT, person.name as name_owner,species_name, colour,gender,birth_year,age FROM animal inner join consult on animal.name=consult.name and animal.VAT=consult.VAT_owner inner join person on animal.VAT=person.VAT  WHERE VAT_client= :VAT_client");

         $sqly->execute([':VAT_client' => $_SESSION['VAT_client'] ]);
      $result_y=$sqly->fetchAll();
      if ($result_y == 0) {
          $info = $sqly->errorInfo();
          echo("<p>Error: {$info[2]}</p>");
          exit();
      }
         
        echo("<p>Results with all animals that have consults associated with this client </p>");
        echo("<table border=\"1\" cellpadding=\"4\">");
        echo("<tr><td>Owner name</td><td>VAT</td><td>animal name</td><td>Species name</td><td>Colour</td><td>Gender</td><td>Birthday</td><td>Age</td></tr>");
        foreach($result_y as $row)
        {
          echo("<tr><td>");
          echo($row['name_owner']);
          echo("</td><td>");
          echo($row['VAT']);
          echo("</td><td>");
          echo($row['an_name']);
          echo("</td><td>");
          echo($row['species_name']);
          echo("</td><td>");
          echo($row['colour']);
          echo("</td><td>");
          echo($row['gender']);
          echo("</td><td>");
          echo($row['birth_year']);
          echo("</td><td>");
          echo($row['age']);
          echo("</td></td>");
          echo("<td><a href=\"consults.php?name=");
          echo($row['an_name']);
          echo("&VAT_owner=");
          echo($row['VAT']);
          echo("\">View animal</a></td></tr>\n");
        }
        echo("</table>");
      

        //para sacar todos os specie names que existem



        //--------------------------------------------------------------------------------------------------------
        echo("<p>-------------------------------------------------------------</p>");
        echo("<p>No animal found. Please fullfill the animal charactheristics </p>");

    ?>

         <form action='insertanimal.php' method='post'>
        <h3>Animal information</h3>
        <p>Colour: <input type='text' name='colour'/></p>
        <p>Gender: <input type='text' name='gender'/></p>
        <p>Birth year: <input type='date' name='birth_year'/></p>
        <p>Age: <input type='text' name='age'/></p>
        <p> Species name: <select name="species_name"> 

  <?php

          $sql_aux = "SELECT distinct species_name FROM animal order by species_name";
          $result_aux = $connection->query($sql_aux);
          if ($result_aux == FALSE)
          {
            $info = $connection->errorInfo();
            echo("<p>Error: {$info[2]}</p>");
            exit();
          }

          foreach($result_aux as $row)
          {
            $species_name=$row['species_name'];
            echo("<option value= \"$species_name\">$species_name</option>");
          }

  ?>
        </select> </p>
        <p><input type='submit' value='Submit'/></p>
      </form>

<?php
      }
      else
      {

         $sqlx = $connection->prepare("SELECT distinct animal.name as an_name, animal.VAT,person.name as name_owner,species_name, colour,gender,birth_year,age
        FROM animal inner join person on animal.VAT=person.VAT
        WHERE animal.name=:animal_name and person.name like CONCAT('%',:owner_name,'%') UNION SELECT distinct animal.name as an_name, animal.VAT, person.name as name_owner,species_name, colour,gender,birth_year,age FROM animal inner join consult on animal.name=consult.name and animal.VAT=consult.VAT_owner inner join person on animal.VAT=person.VAT  WHERE VAT_client= :VAT_client");

         $sqlx->execute([':animal_name' => $_SESSION['animal_name'],
      ':owner_name' => $_SESSION['owner_name'],':VAT_client' => $_SESSION['VAT_client'] ]);
      $result_x=$sqlx->fetchAll();
      if ($result_x == 0) {
          $info = $sqlx->errorInfo();
          echo("<p>Error: {$info[2]}</p>");
          exit();
      }
         

        echo("<p>Results found<p>");
        echo("<table border=\"1\" cellpadding=\"4\">");
        echo("<tr><td>Owner name</td><td>VAT</td><td>animal name</td><td>Species name</td><td>Colour</td><td>Gender</td><td>Birthday</td><td>Age</td></tr>");
        foreach($result_x as $row)
        {
          echo("<tr><td>");
          echo($row['name_owner']);
          echo("</td><td>");
          echo($row['VAT']);
          echo("</td><td>");
          echo($row['an_name']);
          echo("</td><td>");
          echo($row['species_name']);
          echo("</td><td>");
          echo($row['colour']);
          echo("</td><td>");
          echo($row['gender']);
          echo("</td><td>");
          echo($row['birth_year']);
          echo("</td><td>");
          echo($row['age']);
          echo("</td></td>");
          echo("<td><a href=\"consults.php?name=");
          echo($row['an_name']);
          echo("&VAT_owner=");
          echo($row['VAT']);
          echo("\">View animal</a></td></tr>\n");
        }
        echo("</table>");
      }
  }
  
  echo(" <form action='check.php' method='post'>
  <h3>Come back to homepage</h3>
  <p><input type='submit' value='Homepage'/></p>
  </form>");
  $connection = null;
  ?>
</body>
</html>