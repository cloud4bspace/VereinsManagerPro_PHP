<!DOCTYPE html>

<html lang="de">

<?php
include '../Services/dbconn.php';

?>
<head>
    <title>VereinsManagerPro (Doodle)</title>
	<meta charset="utf-8">
    <meta name="description" content="Formular zur Ermittlung der Teilnehmer">
    <meta name="author" content="Bernhard Kämpf & Serge Kaulitz">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="../CSS/stylesheetV01.css?v=<?=time();?>" type="text/css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Bangers|Cabin+Sketch|Codystar|Open+Sans|Open+Sans+Condensed:300|Passion+One|Raleway|Ribeye+Marrow|Special+Elite|Zilla+Slab+Highlight" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Anton|Dosis:800|Exo:800|Maven+Pro:900|Montserrat:700|Nunito:700|Open+Sans:700|Podkova:700|Russo+One|Slackey" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bungee|Bungee+Hairline|Bungee+Inline|Bungee+Outline|Keania+One" rel="stylesheet">
</head>
    <body>
    <h1>Vereinsmanager Doodle (Terminplaner)</h1>
        <?php
        echo "<table>";
        echo "<tr>";
        echo "<td></td><td colspan='3'>Anmeldestatus</td><td colspan='3'>Anwesenheitsstatus</td>";
        echo "</tr><tr>";
        echo "<td>&#x276f; für Mitglied nicht relevant</td>";
            echo "<td class='OK'>angemeldet</td>";
        echo "<td class='NOK'>abgemeldet</td>";
        echo "<td class='EVT'>evtl.</td>";
        echo "<td>&#x2714; anwesend</td><td>&#x26f1; entschuldigt</td><td>&#x2718; unentschuldigt</td>";
        echo "</tr>";
        echo "</table>";
        ?>
        <table>
            <thead>
                <tr><th colspan='4'>Mitglied</th>
                <?php
                    // Termine auflisten..
                    $sql02 = "SELECT * FROM termin WHERE TerminDatum >= CURRENT_DATE ORDER BY TerminDatum ASC, TerminZeit ASC";
                    //$sql02 = "SELECT * FROM termin ORDER BY TerminDatum ASC, TerminZeit ASC";
                    $result02 = mysqli_query($conn, $sql02);
                    //$row02 = mysqli_fetch_assoc($result02);
                    while($row02 = $result02->fetch_assoc()) {
                        $terminText = $row02['TerminText'];
                        if(!empty($row02['TerminOrt'])) {
                            $terminText .= ", " . $row02['TerminOrt'];
                        }
                        echo "<th>" . date("D. d.m.Y",strtotime($row02['TerminDatum']))  . "<br>" . $terminText . "</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
    <?php
        $sql01 ="SELECT * FROM kontakt ORDER BY KontaktNachname, KontaktVorname";
        $result01 = mysqli_query($conn, $sql01);
                    while($row01 = $result01->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a href='https://cloud4b.space/VereinsManager/Doodle/doodlechange.php?MitgliedId=" . $row01['KontaktId'] . "'>&#x270e;</a></td>";
                        echo "<td>#" . $row01['KontaktId'] . "</td>";
                        echo "<td>" . $row01['KontaktNachname'] . "</td>";
                        echo "<td>" . $row01['KontaktVorname'] . "</td>";

                        // Termine nochmals durchlaufen pro Person
                        $result02 = mysqli_query($conn, $sql02);
                        // $row02 = mysqli_fetch_assoc($result02);
                        while($row02 = $result02->fetch_assoc()) {
                        $terminText = $row02['TerminText'];

                        if($row02['TerminDatum'] >= $row01['KontaktEintrittsdatum']) {
                            $sql03 = "SELECT * FROM terminkontrolle WHERE KontrolleMitgliedId = " . $row01['KontaktId'] . " AND KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anmeldung'";
                            $result03 = mysqli_query($conn, $sql03);
                            $row03 = mysqli_fetch_assoc($result03);  
                            // 1 = angemeldet
                            // 2 = abgemeldet
                            // 3 = vielleicht
                            $sql04 = "SELECT * FROM terminkontrolle WHERE KontrolleMitgliedId = " . $row01['KontaktId'] . " AND KontrolleTerminId = " . $row02['TerminId'] . " AND KontrolleArt='Anwesenheit'";
                            $result04 = mysqli_query($conn, $sql04);
                            $row04 = mysqli_fetch_assoc($result04);  
                            // 1 = angemeldet
                            // 2 = abgemeldet
                            // 3 = vielleicht
                            switch($row03['KontrolleWert']){
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
                                    echo "<td class='NA'>";
                                    break;
                            }
                            switch($row04['KontrolleWert']) {
                                case 1:
                                    echo "&#x2714;</td>";
                                    break;
                                case 2:
                                    echo "&#x26F1;</td>";
                                    break;
                                case 3:
                                    echo "&#x2718;</td>";
                                    break;
                                default:
                                    echo "</td>";
                                    break;
                            }
                        } else {
                        echo "<td class='NA'>&#x276f;</td>";
                        }
                    }
                        echo "</tr>";
                    }
        ?>
            </tbody>
        </table>
    </body>
</html>
