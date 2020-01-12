<!DOCTYPE html>

<html lang="de">

<?php
include '../Services/dbconn.php';


    echo "<br>Termin: " . $_POST['termin'];
    $terminId = $_POST['termin'];
    echo "<br>Mitglied: " . $_POST['mitgliedId'];
    echo "<br>Auswahl: " . $_POST['auswahl'];
   // echo "<br>Bemerkung: " . $_POST['bemerkungen'];

    // gibt es schon einen Eintrag? 
    // direkt löschen..
    $sql01 = "DELETE FROM terminkontrolle WHERE KontrolleMitgliedId = " . $_POST['mitgliedId'] . " AND KontrolleTerminId = " . $_POST['termin'] . " AND KontrolleArt='Anwesenheit'";
    $result01 = mysqli_query($conn, $sql01);

    if($_POST['auswahl']>0) {
    $sql02 = "INSERT INTO terminkontrolle (KontrolleId, KontrolleMitgliedId, KontrolleTerminId, KontrolleArt, KontrolleWert, KontrolleBemerkungen, KontrolleZeitstempel, KontrolleBenutzer) VALUES (NULL, '" . $_POST['mitgliedId'] . "', '" . $_POST['termin'] . "', 'Anwesenheit', '" . $_POST['auswahl'] . "', '" . $_POST['bemerkungen'] . "', CURRENT_TIMESTAMP, NULL);";
    $result02 = mysqli_query($conn, $sql02);
    echo "<br>" . $sql02;
    echo "<br>Result: " . $result02;
    }

    
   header("Location: ../Kontrolle/kontrolle.php?TerminId=" . $terminId);
    
    ?>



