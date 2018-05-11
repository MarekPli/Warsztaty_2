<?php
function myOpenDatabase ($dbase) {
    //zwraca obiekt PDO - otwarta baza

    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'coderslab');
    define('DB_CHARSET', 'utf8');
    define('DB_PORT_NUMBER',3306); //default port is 3306
//    //define('DB_DATABASE_NAME', $database);
    //                        . ';dbname=' .  DB_DATABASE_NAME

    $dataSourceName =   'mysql:host='. DB_HOST
//        . ';port=' .    DB_PORT_NUMBER // nie trzeba jeśli default, czyli 3306
        . ';charset=' . DB_CHARSET ;
    ;
    //echo 'Connecting to database: <br>';
    $conn = new PDO($dataSourceName, DB_USER, DB_PASSWORD);
    if ($conn->errorCode() !== null) {
        die ('Połączenie nieudane: błąd '. $conn->errorInfo()[2]);
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec('use ' . $dbase);
    return $conn;
}
?>