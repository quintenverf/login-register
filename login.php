<?php
session_start();
if(array_key_exists('ingelogd', $_SESSION) && $_SESSION['ingelogd'] === true){
    //ROL UITZOEKEN
}

require_once('./db_cred.php');
require_once('./functions.php');

if(array_key_exists('submit', $_POST) && $_POST['submit'] === "true"){
    $query = "SELECT * FROM user WHERE email = :email AND wachtwoord = :password";
    $username = filterSanitizeInput($_POST['email']);
    $password = filterSanitizeInput($_POST['password']);
    $statement = $conn->prepare($query);
    $statement->bindParam('email', $username);
    $statement->bindParam('password', $password);

    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if(count($result) === 1) {

        //functie maken
        $_SESSION['ingelogd'] = true;
        $_SESSION['voornaam'] = $result[0]['voornaam'];
        $_SESSION['achternaam'] = $result[0]['achternaam'];
        $_SESSION['id'] = $result[0]['id'];
        qbv($result);
        //WELKE ROL
        //QUERY AFVUREN NAAR DB => WELK NUMMER BIJ WELKE ROL HOORT
        if($result[0]['user_rol_id'] == 1){//dynamscih gedaan worden
            echo "correct"; 
            header('Location: ./index.php');
        } else if ($result[0]['user_rol_id'] == 2){
            header('Location: ./index.php');
        }
        die();
    }
}

function findRole(){
    $role;
    return $role;
}
function filterSanitizeInput($input){
    return htmlentities($input);
}

?>

<!DOCTYPE html>
<html>
    <head>
       
        <style>
            .body {
                display: grid;
                grid-template-columns: 1fr;
            }
            .container--registreren {
                display: grid;
                grid-template-columns: 1fr;
            }
            .form--register {
                display: grid;
                grid-template-columns: 1fr;
                place-items: center;
                grid-row-gap: .75rem;
            }
        </style>
    </head>
    <body class="body">
        <div class="container container--inloggen">
            <div class="title">Inloggen</div>
            <form class="form form--register" method="post" action="./login.php">
                <input type="email" name="email" placeholder="email" class="input input--email" required>
                <input type="password" name="password" placeholder="password" class="input input--text" required>
                <button type="submit" name="submit" value="true" class="button button--submit">Inloggen</button>
                <button onclick="window.location.href = 'registreer.php';" type="submit" name="submit" value="true" class="button button--submit">registreren</button>
                
            </form>
        </div>
    </body>
</html>