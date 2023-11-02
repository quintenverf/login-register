<?php

function checkLoggedIn(){
    if(array_key_exists('ingelogd', $_SESSION) && $_SESSION['ingelogd'] === true){
        redirectBasedOnRole($_SESSION['id']);
    }
}

function redirectBasedOnRole($id){
    global $conn;
    $query = "SELECT rol_naam FROM user_rol WHERE id = (SELECT user_rol_id FROM user WHERE id = " . $id . ")";
    $role = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC)[0]['rol_naam'];
    switch($role){
        case 'admin':
            header('Location: ./index.php');
            die();
        case 'bezoeker':
            header('Location: ./index.php');
            die();
    }
}

function qbv($input){
    echo "<pre>";
    print_r($input);
    echo "</pre>";
}