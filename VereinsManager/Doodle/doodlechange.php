<!DOCTYPE html>

<html lang="de">

<?php
include '../Services/dbconn.php';

?>
<head>
    <title>Vereinsmanager</title>
	<meta charset="utf-8">
    <meta name="description" content="Hilfsmittel und Tools für die Verwaltung von Vereinen">
    <meta name="author" content="Bernhard Kämpf (cloud4b.space)">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="../CSS/stylesheetV01.css?v=<?=time();?>" type="text/css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Bangers|Cabin+Sketch|Codystar|Open+Sans|Open+Sans+Condensed:300|Passion+One|Raleway|Ribeye+Marrow|Special+Elite|Zilla+Slab+Highlight" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Anton|Dosis:800|Exo:800|Maven+Pro:900|Montserrat:700|Nunito:700|Open+Sans:700|Podkova:700|Russo+One|Slackey" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bungee|Bungee+Hairline|Bungee+Inline|Bungee+Outline|Keania+One" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="72x72" href="/files/icn/apple-touch-icon-72x72.png" />
</head>
<body>

    <h1>Vereinsmanager Doodle (Terminplaner)</h1>
        <?php
        $sql01 ="SELECT * FROM kontakt WHERE KontaktId=" .$_GET['MitgliedId'] ;
        $result01 = mysqli_query($conn, $sql01);
        $row01 =mysqli_fetch_assoc($result01);
        echo "<h2>Termine für Mitglied #" . $row01['KontaktId'] . " " . $row01['KontaktNachname'] . " " .  $row01['KontaktVorname'] . "</h2>";
        ?>
        <p><a href='https://www.cloud4b.space/VereinsManager/Doodle/doodle.php'>zurück zur Übersicht</a></p> 
        <table>
            <thead>
                <tr><th>Termin</th><th>Zeit</th><th>Anwesenheit</th><th>Bemerkungen</th></tr>
            </thead>
            <tbody>
                <?php
                    // Termine auflisten..
                    if(empty($row01['KontaktAustrittsdatum'])) {
                            $sql02 = "SELECT * FROM termin WHERE TerminDatum >= '" . $row01['KontaktEintrittsdatum']. "' ORDER BY TerminDatum ASC";            
                    } else {
                    $sql02 = "SELECT * FROM termin WHERE TerminDatum >= '" . $row01['KontaktEintrittsdatum']. "' AND TerminDatum < '" . $row01['KontaktAustrittsdatum'] . "' ORDER BY TerminDatum ASC";
                    }
                    $result02 = mysqli_query($conn, $sql02);
                    while($row02 = $result02->fetch_assoc()) {
                        if(!empty($row02['TerminZeit']) && $row02['TerminZeit'] != '00:00:00') {
                            $zeitText = substr($row02['TerminZeit'],0,5);
                            if(!empty($row02['TerminZeitBis']) && $row02['TerminZeit'] != '00:00:00'){
                                $zeitText .= " - " . substr($row02['TerminZeitBis'],0,5);
                            }
                        }
                        echo "<tr>";
                        $terminText = $row02['TerminText'];
                        if(!empty($row02['TerminOrt'])) {
                            $terminText .= ", " . $row02['TerminOrt'];
                        }

                        echo "<td>" . date("D. d.m.Y",strtotime($row02['TerminDatum']))  . "<br>" . $terminText . "</td>";
                        echo "<td>" . $zeitText . "</td>";
                        $sql04 = "SELECT * FROM terminkontrolle WHERE KontrolleMitgliedId = " . $_GET['MitgliedId'] . " AND KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anmeldung'";
                        $result04 = mysqli_query($conn, $sql04);
                        $row04 = $result04->fetch_assoc();
                        switch($row04['KontrolleWert']){
                            case 1:
                                echo "<td class='OK'>";
                                break;
                            case 2:
                                echo "<td class='NOK'>";
                                break;
                            case 3:
                                echo "<td class='EVT'>";
                                break;
                            default:
                                echo "<td>";
                                break;
                        }
                        echo "<form action='/VereinsManager/Doodle/doodleSave.php' method='POST'>";
                        echo "<input type='hidden' name='termin' value='" . $row02['TerminId'] . "'>";
                        echo "<input type='hidden' name='mitgliedId' value='" . $_GET['MitgliedId'] . "'>";
                        echo "<select name='auswahl' onchange='this.form.submit()'>";
                        $sql03 = "SELECT * FROM statusElement WHERE StatusId = 5 ORDER BY StatusElementKey ASC";
                        $result03 = mysqli_query($conn, $sql03);
                        echo "<option value='0'>..bitte wählen</option>";
                        while($row03 = $result03->fetch_assoc()) {   
                            if($row03['StatusElementKey'] == $row04['KontrolleWert']){
                            echo "<option selected value='" . $row03['StatusElementKey'] . "'>" . $row03['StatusElementSelectionTxt']. "</option>";
                            } else {
                             echo "<option value='" . $row03['StatusElementKey'] . "'>" . $row03['StatusElementSelectionTxt']. "</option>";            
                            }
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "<td><input value='" . $row04['KontrolleBemerkungen']. "' name='bemerkungen' type='text' onchange='this.form.submit()'></input></td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                    ?>
            </tbody>
        </table>
</body>
</html>


