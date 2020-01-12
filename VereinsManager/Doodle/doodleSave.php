<!DOCTYPE html>

<html lang="de">

<?php
include '../Services/dbconn.php';

    echo "<br>Termin: " . $_POST['termin'];
    echo "<br>Mitglied: " . $_POST['mitgliedId'];
    echo "<br>Auswahl: " . $_POST['auswahl'];
    echo "<br>Bemerkung: " . $_POST['bemerkungen'];
    echo "<br>Herkunft: " . $_POST['einzeltermin'];

    // gibt es schon einen Eintrag? 
    // direkt löschen..
    $sql01 = "DELETE FROM terminkontrolle WHERE KontrolleMitgliedId = " . $_POST['mitgliedId'] . " AND KontrolleTerminId = " . $_POST['termin'] . " AND KontrolleArt='Anmeldung'";
    $result01 = mysqli_query($conn, $sql01);

    if($_POST['auswahl']>0) {
    $sql02 = "INSERT INTO terminkontrolle (KontrolleId, KontrolleMitgliedId, KontrolleTerminId, KontrolleArt, KontrolleWert, KontrolleBemerkungen, KontrolleZeitstempel, KontrolleBenutzer) VALUES (NULL, '" . $_POST['mitgliedId'] . "', '" . $_POST['termin'] . "', 'Anmeldung', '" . $_POST['auswahl'] . "', '" . $_POST['bemerkungen'] . "', CURRENT_TIMESTAMP, NULL);";
    $result02 = mysqli_query($conn, $sql02);
    echo "<br>" . $sql02;
    echo "<br>Result: " . $result02;
    }

    if($_POST['einzeltermin'] == 1) {
        header("Location: ../Doodle/doodleTermin.php?TerminId=" . $_POST['termin']);
    } else {
   header("Location: ../Doodle/doodlechange.php?MitgliedId=" . $_POST['mitgliedId']);
    }    
    ?>



