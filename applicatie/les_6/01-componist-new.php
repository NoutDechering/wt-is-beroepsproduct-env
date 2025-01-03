<?php

require_once '../db_connectie.php';

$db = maakVerbinding();

$fouten = [];
$melding = 'Gegevens worden nog niet verwerkt';

if (isset($post['opslaan'])){

// post variabele omzetten naar locale variabelen
$naam = $_POST['naam']??'';
$geboortedatum = $_POST['geboortedatum']??'';
$schoolId = $_POST['schoolId']??'';

if (empty($schoolId)){
    $schoolId = null;
}

if (empty($geboorteDatum)){
    $geboorteDatum = null;
}

if (empty($naam)){
    $fouten[] = 'Naam van componist moet ingevult worden';
}

if (is_numeric($naam)){
    $fouten[] = 'Naam mag geen numerieke naam zijn';
}

// opzoeken wat de laatst ingevoerde Id is
$latestIdSql = 'SELECT TOP(1) componistId FROM Componist ORDER BY componistId DESC';
$latestIdQuery = $db->prepare($latestIdSql);
$latestIdQuery->execute();
$row = $latestIdQuery->fetch();
// laatst gevonden Id + 1 voor nieuwe insert statement
$latestComponistId = $row['componistId'] + 1;

// insert query maken van de opgegeven data
$sql = 'INSERT INTO componist (componistId, naam, geboortedatum, schoolId)
        VALUES (:componistId,--placeholder tegen injectie sql 
        :naam, :geboorteDatum, :schoolId);';


$query = $db->prepare($sql);// query alvast opsturen
$query->execute([// query uitvoeren die eerder is opegeven
    ':componistId' =>$newComponistId,
    ':naam' =>$naam,
    ':geboorteDatum' =>$geboorteDatum,
    ':schoolId' =>$schoolId,
]);

}
//voorgetypte melding


?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Componinst - nieuw</title>
    <link href="css/normalize.css" rel="stylesheet" >
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?= $melding ?>
    <form action="" method="post">
        <label for="naam">naam</label>
        <input type="text" id="naam" name="naam"><br>

        <label for="geboortedatum">geboortedatum</label>
        <input type="date" id="geboortedatum" name="geboortedatum"><br>

        <label for="schoolId">schoolId</label>
        <input type="text" id="schoolId" name="schoolId"><br>

        <input type="reset" id="reset" name="reset" value="wissen">
        <input type="submit" id="opslaan" name="opslaan" value="opslaan">    
    </form>
</body>
</html>