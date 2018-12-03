<!-- IST MySQL Connection Test -- 2015.09.19 -->

<html>
<body>
<?php
	//VARIAVEIS DE ESTADO
	$vatowner= (string) "00000005";
	$name= (string) "Goofy";
	$date= "2018-1-10 12:30:00.75"; //TODO
	$host="db.ist.utl.pt";	// MySQL is hosted in this machine
	//$user="ist425486";	// <== replace istxxx by your IST identity
	//$password="itgh2083";	// <== paste here the password assigned by mysql_reset
	$user="ist425496";
	$password="abjq7123";
	$dbname = $user;	// Do nothing here, your database has the same name as your username.
	$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	$sql="SELECT * FROM assistant";
	$result=$connection->query($sql);
	$assistants=$result->fetchAll();
	
	echo ("<h3> BLOOD TESTS RESULTS </h3>");
	echo ("<br>");
	echo ("<form action=\"act.php\" method=\"post\">");
	echo ("<input type=\"hidden\" id=\"vatowner\" name=\"vatowner\" value=\"$vatowner\">");
	echo ("<input type=\"hidden\" id=\"name\" name=\"name\" value=\"$name\">");
	echo ("<input type=\"hidden\" id=\"date\" name=\"date\" value=\"$date\">");
	echo ("<fieldset><legend> Results of the Blood Tests </legend>");
	echo ("<label for=\"vatassistant\"> VAT of the assistant:</label>");
	echo ("<select id=\"vatassistant\" name=\"vatassistant\">");
	echo("<option value=\"escolha\">Escolha</option>");
	foreach($assistants as $row){
		$vat=$row["VAT"];
		echo("<option value=\"$vat\">$vat</option>");	
	}
	echo ("</select><br><br>");
	echo ("<label for=\"whitebloodcells\"> White Blood Cells Count: </label>");
	echo ("<input type=\"number\" min=\"1\" id=\"whitebloodcells\" name=\"whitebloodcells\"><br><br>");
	//echo ("<label for=\"neutrophils\"> Number of Neutrophils:</label>");
	//echo ("<input type=\"number\" min=\"1\" id=\"neutrophils\" name=\"neutrophils\"><br><br>");
	//echo ("<label for=\"lymphocytes\"> Number of Lymphocytes:</label>");
	//echo ("<input type=\"number\" min=\"1\" id=\"lymphocytes\" name=\"lymphocytes\"><br><br>");
	//echo ("<label for=\"monocytes\"> Number of Monocytes:</label>");
	//echo ("<input type=\"number\" min=\"1\" id=\"monocytes\" name=\"monocytes\"><br><br>");
	echo ("<label for=\"redbloodcells\"> Red Blood Cells Count:</label>");
	echo ("<input type=\"number\" min=\"1\" id=\"redbloodcells\" name=\"redbloodcells\"><br><br>");
	echo ("<input type=\"submit\" value=\"Submit\">");
	echo ("</fieldset>");
	
	echo ("</form>");
			
    $connection = null;
?>
</body>
</html>
