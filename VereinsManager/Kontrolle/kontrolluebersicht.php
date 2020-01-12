<!DOCTYPE html>

<html lang="de">

<?php
include '../Services/dbconn.php';

?>
<head>
    <title>Vereinsmanager</title>
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

<h1>VereinsManagerPro Anwesenheitskontrolle (Übersicht)</h1>
  

    <table>
        <thead>
            <tr><th>&#x270e;</th><th>Termin</th><th>Zeit</th><th class='verticalTableHeader'>angemeldet</th><th>evtl.</th><th>abgemeldet</th><th>anwesend</th><th>entschuldigt</th><th>unentschuldigt</th></tr>
        </thead>
        <tbody>
            <?php
                // Termine auflisten..
                if(!empty($_GET['Jahr'])){
                  $sql02 = "SELECT * FROM termin WHERE YEAR(TerminDatum) = " . $_GET['Jahr'] . " ORDER BY TerminDatum ASC, TerminZeit ASC";                  
                } else {
                  $sql02 = "SELECT * FROM termin WHERE YEAR(TerminDatum) = YEAR(CURRENT_DATE) ORDER BY TerminDatum ASC, TerminZeit ASC";                  
                }

                //$sql02 = "SELECT * FROM termin ORDER BY TerminDatum ASC, TerminZeit ASC";
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
                                       echo "<td><a href='https://cloud4b.space/VereinsManager/Kontrolle/kontrolle.php?TerminId=" . $row02['TerminId'] . "'>&#x270e;</a></td>";
                    echo "<td>" . date("D. d.m.Y",strtotime($row02['TerminDatum']))  . "<br>" . $terminText . "</td>";
                    echo "<td>" . $zeitText . "</td>";
                     
                    $sql04 = "SELECT COUNT(*) AS AnzAnmeldungen FROM terminkontrolle WHERE KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anmeldung' AND KontrolleWert=1";
                    $result04 = mysqli_query($conn, $sql04);
                    $row04 = $result04->fetch_assoc();
                    echo "<td class='Anzahl'>" . $row04['AnzAnmeldungen'] . "</td>";
 
                    $sql04 = "SELECT COUNT(*) AS AnzEvtl FROM terminkontrolle WHERE KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anmeldung' AND KontrolleWert=3";
                    $result04 = mysqli_query($conn, $sql04);
                    $row04 = $result04->fetch_assoc();
                    echo "<td  class='Anzahl'>" . $row04['AnzEvtl'] . "</td>";
                    
                    $sql04 = "SELECT COUNT(*) AS AnzAbmeldungen FROM terminkontrolle WHERE KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anmeldung' AND KontrolleWert=2";
                    $result04 = mysqli_query($conn, $sql04);
                    $row04 = $result04->fetch_assoc();
                    echo "<td class='Anzahl'>" . $row04['AnzAbmeldungen'] . "</td>";
                
                    
                    $sql04 = "SELECT COUNT(*) AS AnzAnwesende FROM terminkontrolle WHERE KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anwesenheit' AND KontrolleWert=1";
                    $result04 = mysqli_query($conn, $sql04);
                    $row04 = $result04->fetch_assoc();
                    echo "<td class='Anzahl'>" . $row04['AnzAnwesende'] . "</td>";
                    
                    $sql04 = "SELECT COUNT(*) AS AnzEntschuldigte FROM terminkontrolle WHERE KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anwesenheit' AND KontrolleWert=2";
                    $result04 = mysqli_query($conn, $sql04);
                    $row04 = $result04->fetch_assoc();
                    echo "<td class='Anzahl'>" . $row04['AnzEntschuldigte'] . "</td>";
                    
                    $sql04 = "SELECT COUNT(*) AS AnzUnentschuldigte FROM terminkontrolle WHERE KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anwesenheit' AND KontrolleWert=3";
                    $result04 = mysqli_query($conn, $sql04);
                    $row04 = $result04->fetch_assoc();
                    echo "<td class='Anzahl'>" . $row04['AnzUnentschuldigte'] . "</td>";               
   
                    echo "</tr>";
                }
                ?>
        </tbody>
    </table>
</body>
</html>


