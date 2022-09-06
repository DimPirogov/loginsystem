<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LyckadInloggningDelProv</title>
</head>
<body>

<?php   // sidan för lyckad inloggning
    // här kontrolleras det om sessionen är aktiv genom att
    // ta fram data ur variabeln SESSION['namn']
    // om den inte finns då omdirigeras man tillbaka till
    // inlogg.php
    ini_set('session.gc_maxlifetime', 10);  // lade in session radering om 10 sekunder
    // men detta fungerar inte
    session_start();

    if(isset($_SESSION['namn'])){
        // omvandlar specialtecken till htmlkod
        $id = htmlspecialchars($_SESSION['tabelID']);
        $name = htmlspecialchars($_SESSION['namn']);
        // lägger in session förstöring om man laddar om sidan
        destroy_session_and_data();

        echo "Welcome back $name.<br>
              Your full name is not know $name but your id is $id.<br>";
        echo ini_get('session.gc_maxlifetime');
    }
    // om det finns ingen session vektor så får man en länk
    // till inloggningsida
    else echo "Please <a href='inlogg.php'>click here</a> to log in.";
    // funktionen för session radering genom att ange en tom vektor
    // och setta cookie till 
    
    function destroy_session_and_data(){
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
    }
?>

</body></html>