<!DOCTYPE html>

<html lang="de">

<?php
include '../Services/dbconn.php';

?>
<head>
    <title>VereinsManagerPro (Kontrolle)</title>
	<meta charset="utf-8">
    <meta name="description" content="Hilfsmittel und Tools für die Verwaltung von Vereinen">
    <meta name="author" content="Bernhard Kämpf & Serge Kaulitz">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="../CSS/stylesheetV01.css?v=<?=time();?>" type="text/css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Bangers|Cabin+Sketch|Codystar|Open+Sans|Open+Sans+Condensed:300|Passion+One|Raleway|Ribeye+Marrow|Special+Elite|Zilla+Slab+Highlight" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Anton|Dosis:800|Exo:800|Maven+Pro:900|Montserrat:700|Nunito:700|Open+Sans:700|Podkova:700|Russo+One|Slackey" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bungee|Bungee+Hairline|Bungee+Inline|Bungee+Outline|Keania+One" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="72x72" href="/files/icn/apple-touch-icon-72x72.png" />	
</head>
<body>

<h1>VereinsManagerPro (Anwesenheitskontrolle)</h1>
    <?php
    $sql02 = "SELECT * FROM termin WHERE TerminId=" . $_GET['TerminId'];                  
    $result02 = mysqli_query($conn, $sql02);
    $row02 = $result02->fetch_assoc();
     if(!empty($row02['TerminZeit']) && $row02['TerminZeit'] != '00:00:00') {
                        $zeitText = substr($row02['TerminZeit'],0,5);
                        if(!empty($row02['TerminZeitBis']) && $row02['TerminZeit'] != '00:00:00'){
                            $zeitText .= " - " . substr($row02['TerminZeitBis'],0,5);
                        }
     }
                    $terminText = $row02['TerminText'];
                    if(!empty($row02['TerminOrt'])) {
                        $terminText .= ", " . $row02['TerminOrt'];
                    }
echo "<h2>" . date("D. d.m.Y",strtotime($row02['TerminDatum'])) . " " . $terminText . " | "  . $zeitText . " </h2>";

    ?>
<p><a href='https://www.cloud4b.space/VereinsManager/Kontrolle/kontrolluebersicht.php'>zurück zur Übersicht</a></p> 
    <table>
        <thead>
            <tr><th colspan='3'>Mitglied</th><th>Anwesenheitskontrolle</th>
            </tr>
        </thead>
        <tbody>
<?php     
    $sql01 ="SELECT * FROM kontakt WHERE KontaktEintrittsdatum <= '" . $row02['TerminDatum'] . "' ORDER BY KontaktNachname, KontaktVorname";

    $result01 = mysqli_query($conn, $sql01);
				while($row01 = $result01->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>#" . $row01['KontaktId'] . "</td>";
					echo "<td>" . $row01['KontaktNachname'] . "</td>";
                    echo "<td>" . $row01['KontaktVorname'] . "</td>";
                    echo "<td style='text-align:center;'>";
                          $sql04 = "SELECT * FROM terminkontrolle WHERE KontrolleArt = 'Anwesenheit' AND KontrolleMitgliedId = " . $row01['KontaktId'] . " AND KontrolleTerminId = " . $_GET['TerminId'];
    $result04 = mysqli_query($conn, $sql04);
    $row04 = $result04->fetch_assoc();
                    echo "<form action='/VereinsManager/Kontrolle/kontrolleSave.php' method='POST'>";
                    echo "<input type='hidden' name='termin' value='" . $row02['TerminId'] . "'>";
                    echo "<input type='hidden' name='mitgliedId' value='" . $row01['KontaktId'] . "'>";
                   echo "<select name='auswahl' onchange='this.form.submit()'>";
                    $sql03 = "SELECT * FROM statusElement WHERE StatusId = 6 ORDER BY StatusElementKey ASC";
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
                    echo "</form>";
                    echo "</td>"; 
                    echo "</tr>";
                }
    ?>
        </tbody>
    </table>
</body>
</html>


