<?php
session_start();

require_once('./db_cred.php');
require_once('./functions.php');

checkLoggedIn();

if(array_key_exists('submit', $_POST) && $_POST['submit'] === "true"){
    $query = "INSERT INTO user (voornaam, achternaam, geboortedatum, wachtwoord, email, user_rol_id) VALUES (:voornaam, :achternaam, :geboortedatum, :wachtwoord, :email, :user_rol)";
    $voornaam = filterSanitizeInput($_POST['voornaam']);
    $achternaam = filterSanitizeInput($_POST['achternaam']);
    $email = filterSanitizeInput($_POST['email']);
    $geboortedatum = filterSanitizeInput($_POST['geboortedatum']);
    $passwordOne = filterSanitizeInput($_POST['password_one']);
    $passwordTwo = filterSanitizeInput($_POST['password_two']);
    $password = ($passwordOne === $passwordTwo) ? $passwordOne : false;
    $user_role = filterSanitizeInput($_POST['user_rol']);//
    
    // if( $user_role == 1){
    //     $query = "SELECT id FROM user WHERE user_rol_id = (SELECT id FROM user_rol WHERE rol_naam = instructeur)ORDER BY RAND() LIMIT 1";
    //     //voer query uit
    //     // bewaar result
    //     //plak result in bind param instructeur
 
    // }

if(!$password) die('DOE HIER FOUTMELING'); 
    $statement = $conn->prepare($query);
    
    $statement->bindParam('voornaam', $voornaam);
    $statement->bindParam('achternaam', $achternaam);
    $statement->bindParam('email', $email);
    $statement->bindParam('geboortedatum', $geboortedatum);
    $statement->bindParam('wachtwoord', $password); 
    $statement->bindParam('user_rol', $user_role);   
    // $statement->bindParam('instructeur',);

    $result = $statement->execute(); 

    $sql = "SELECT id FROM user WHERE email = '" . $email .  "'";
    $id = $conn->query($sql)->fetch(PDO::FETCH_ASSOC)['id'];

    if($result == 1) {
        $_SESSION['ingelogd'] = true;
        $_SESSION['voornaam'] = $voornaam;
        $_SESSION['achternaam'] = $achternaam;
        $_SESSION['id'] = $id;
        //SUCCESMELDING

        header('Location: ./index.php');
        die();
    } else {
        //FOUTMELDING
    }

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
        <div class="container container--registreren">
            <div class="title">Registreer</div>
            <form class="form form--register" method="post" action="./registreer.php">
                <input type="text" name="voornaam" placeholder="voornaam" class="input input--text" required>
                <input type="text" name="achternaam" placeholder="achternaam" class="input input--text" required>
                <input type="email" name="email" placeholder="email" class="input input--email" required>
                <input type="date" name="geboortedatum" placeholder="geboortedatum" class="input input--date" required>
                <input type="password" name="password_one" placeholder="password" class="input input--text" required>
                <input type="password" name="password_two" placeholder="password" class="input input--text" required>
        
<select name="user_rol" id="user_rol">
  <option value="1">admin</option>
  <option value="2">bezoeker</option>
</select>
<button type="submit" name="submit" value="true" class="button button--submit">Registreer</button>
            </form>
        </div>
    </body>
</html>