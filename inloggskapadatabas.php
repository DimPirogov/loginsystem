<?php   // skapar tabellen
    // använder login filen med information om
    // redan skapade databasen och användarnamn, inloggningen som skapades
    // med hjälp av boken där man kopplar upp sig till localhost eller
    // 127.0.0.1
    require_once 'login.php';
    // skapar en try catch felhantering för kopplingen till mysql
    try{
        $pdo = new PDO($attr, $user, $pass, $opts);
    }
    catch(\PDOException $e){
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    // skapar kommandot för tabellen med id som utökas och är primary key
    $query = "CREATE TABLE inloggare (
        id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(32) NOT NULL,
        password VARCHAR(64) NOT NULL)";
    // skapar tabellen
    $result = $pdo->query($query);
    // lägger in första användare
    $namn = 'Lars';
    $password = 'hemlig1';
    // vi hashar lösenordet med password_hash
    $hash = password_hash($password, PASSWORD_DEFAULT);
    // lägger in användaren ovan
    add_user($pdo, $namn, $hash);
    
    $namn = 'Peter';
    $password = 'hemlig2';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    add_user($pdo, $namn, $hash);
    
    $namn = 'Lars';
    $password = 'hemlig3';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    add_user($pdo, $namn, $hash);
    // funktion för att lägga in användarna med hjälp av
    // placehorlders eller statements, jag lade in första
    // parameter som NULL för att "id" ska själv skapa nummer
    function add_user($pdo, $fn, $pw){
        $stmt = $pdo->prepare('INSERT INTO inloggare VALUES(?,?,?)');

        $stmt->bindParam(2, $fn, PDO::PARAM_STR, 32);
        $stmt->bindParam(3, $pw, PDO::PARAM_STR, 64);
        
        $stmt->execute([ NULL ,$fn, $pw]);
    }
?>