<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>InloggningDelProv</title>
</head>
<body>

<?php   // lägger in php kod här
    require_once 'login.php';   // använder igen inloggningen
    // för mysql databas från boken
    // lägger in felhantering för uppkopplingen till DB
    try{
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch(\PDOException $e){
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    // kollar upp om rutorna är ifyllda och sedan lägger in data i respektive
    // variabel efter rensning av speciella tecken skapar en query för att plocka
    // träffen på namnet
    if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
        $un_temp = sanitize($pdo, $_SERVER['PHP_AUTH_USER']);
        $pw_temp = sanitize($pdo, $_SERVER['PHP_AUTH_PW']);
        $query = "SELECT * FROM inloggare WHERE name=$un_temp";
        $result = $pdo->query($query);
        // om det blev inga träffar då avslutas allt
        if(!$result->rowCount()) die ("User not found");
        // läser i rad för rad från träffen i tabellen och lagrar det
        // i nya variabler
        $row = $result->fetch();
        $id = $row['id'];
        $name = $row['name'];
        $pw = $row['password'];
        // kontrollerar att inskrivna lösenordet överensstämmer med den sparade
        // genom att använda password_verify som "unhashar?"
        // och startar en session och anger till session array id nummret och namnet
        // om det överenstämmer då är inloggningen lyckad och användaren slussas vidare
        // till ny sida där programmer avlustas
        // i fall det är fel lösenord då avslutas programmet med texten
        // att anger username och password
        if(password_verify(str_replace("'", "", $pw_temp), $pw)){
            session_start();

            $_SESSION['tabelID'] = $id;
            $_SESSION['namn'] = $name;

            echo htmlspecialchars("$id, $name : Hi $name,
                you are now logged in as '$id' and '$name'");
            die ("<p><a href='lyckad.php'>Click here to continue</a></p>");
        }
        else die("Invalid name/password combination");
    }
    else{
        header('WWW-authenticate: Basic realm="Restricted Area"');
        header('HTTP/1.1 401 Unauthorised');
        die("Please enter your name and password");
    }
    //  funktionen för att rensa inskrivningen från sidan/användare
    //  efter rensningen så returneras allt inne i citationstecken 
    function sanitize($pdo, $str){
        $str = htmlentities($str);
        return $pdo->quote($str);
    }
?>

</body>
</html>