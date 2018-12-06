<html>
<body>
  <h3>Animals</h3>
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

  $VAT_client = $_REQUEST['VAT_client'];
  $name = $_REQUEST['animal_name'];
  $owner_name = $_REQUEST['owner_name'];

  $sqls = $connection->prepare("SELECT VAT FROM client WHERE VAT= :VAT_client");
  if ($sqls == 0) {
    $info = $sqls->errorInfo();
    echo("<p>Error: {$info[2]}</p>");
    exit();
  }

  $sqls->execute([':VAT_client'=> $VAT_client]);

  $nrows = $sqls->rowCount();
  if ($nrows == 0)
  {
    echo("<p>There is no client with this VAT.</p>");
  }
  else
  {

    echo("<p>You chose VAT Client= "$VAT_client", Animal Name="$name", Owner Name="$owner_name" </p>");
    $sqlw = $connection->prepare("SELECT distinct animal.name as an_name,
      animal.VAT, person.name as name_owner, species_name, colour, gender, birth_year, age
      FROM animal inner join consult on animal.name=consult.name
      and animal.VAT=consult.VAT_owner inner join person using(VAT)
      WHERE VAT_client = :VAT_client and animal.name = :animal_name and person.name like CONCAT('%',:owner_name,'%')");
      if ($sqlw == FALSE) {
        $info = $sqlw->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
      }

      $test = $sqlw->execute([':animal_name' => $name,
                              ':owner_name' => $owner_name,
                              ':VAT_client' => $VAT_client]);
      if ($test == FALSE) {
        $info = $sqlw->errorInfo();
        echo("<p>Error: {$info[2]}</p>");
        exit();
      }

      $result_w = $sqlw->fetchAll();

      $nrows_an = $sqlw->rowCount();
      if ($nrows_an == 0)
      {
        ?>
        <p>The intersection of the 3 parameters is empty!</p>
        <form action='insertanimal.php' method='post'>
          <fieldset style='max-width:400px'><legend> Input the Animal information </legend>
          <input type='hidden' name='name' value='<?=$name?>' />
          <input type='hidden' name='VAT_client' value='<?=$VAT_client?>' />
          <p>VAT owner = <?= $VAT_client?> />
          <p>Colour: <input type='text' name='colour' required/></p>
          <p>Gender: <input type='text' name='gender' required/></p>
          <p>Birth year: <input type='date' name='birth_year' max='<?=date('Y-m-d')?>' required/></p>
          <p>Species name: <select name="species_name">
          <option value="---">---</option>
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
        </fieldset>
        </form>

        <?php


        $sqlx = $connection->prepare("SELECT distinct animal.name as an_name,
            animal.VAT, person.name as name_owner, species_name, colour, gender, birth_year, age
          FROM animal inner join person using(VAT)
          WHERE animal.name = :animal_name and person.name like CONCAT('%',:owner_name,'%')
          UNION
          SELECT distinct animal.name as an_name, animal.VAT,
            person.name as name_owner, species_name, colour, gender, birth_year, age
          FROM animal
          inner join consult on animal.name = consult.name and animal.VAT = consult.VAT_owner
          inner join person on animal.VAT = person.VAT WHERE VAT_client = :VAT_client");
          if ($sqlx == FALSE) {
            $info = $sqlx->errorInfo();
            echo("<p>Error: {$info[2]}</p>");
            exit();
          }

          $sqlx->execute([':animal_name' => $name,
                          ':owner_name' => $owner_name,
                          ':VAT_client' => $VAT_client ]);

          $result_w=$sqlx->fetchAll();
          echo("<p>Here are some alternatives where the animal and owner match the input or animals this client took to consults.</p>");
        }
        else
        {
          echo("<p>Results of the intersection of the 3 parameters:</p>");
        }

        echo("<table border=\"1\" cellpadding=\"4\">");
        echo("<tr><td>Owner name</td><td>VAT Owner</td><td>animal name</td><td>Species name</td><td>Colour</td><td>Gender</td><td>Birthday</td><td>Age</td></tr>");
        foreach($result_w as $row)
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
          echo("&VAT_client=");
          echo($VAT_client);
          echo("\">View animal</a></td></tr>\n");
        }
        echo("</table>");
      }
      $connection = null;
      ?>
      <form action='check.php' method='post'>
        <h3>Go back to homepage</h3>
        <p><input type='submit' value='Homepage'/></p>
      </form>
    </body>
    </html>
