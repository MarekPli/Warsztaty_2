<?php
include_once(__DIR__ . '/User.php');
include_once(__DIR__ . '/DB_open.php');
/**
 * Created by PhpStorm.
 * User: marek
 * Date: 24.04.2018
 * Time: 21:47
 */
$marek = 3;
if ($marek == 4) {
    header('Location: Page_login.php');
}

$pdo = myOpenDatabase('Warsztaty_2');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // logowanie ---------------------------------------
    if (isset($_POST['email'])) {
//        echo "Jestem tutaj.<br>";
        $arrUsers = User::loadAll($pdo);
        $found = false;
        $found_user = null;
        foreach ($arrUsers as $user) {
            if ($user->getEmail() == $_POST['email']) {
//                echo $user->getEmail() . "<br>";
                $found_user = $user;
                $found = true;
                break;
            }
        }
        if ($found) {
//            echo "Użytkownik " . $_POST['email'] . ' został znaleziony';
            echo "Witaj, " . $found_user->getUsername().
            '. Gratuluję pomyślnego zalogowania się do bazy!';
        } else {
//            echo "Użytkownik " . $_POST['email'] . 'nie został znaleziony';

            header('Location: Page_login.php');
        }

    // nowy użytkownik ---------------------------------------
    } elseif (isset($_POST['new_email']) AND !empty($_POST['new_email'])
        AND isset($_POST['new_password'])
        AND !empty($_POST['new_password'])
        AND isset($_POST['new_username'])
        AND !empty($_POST['new_username'])
    ) {

        if (User::is_email($pdo, $_POST['new_email'])) {
            echo "Adres mailowy: " . $_POST['new_email']
                . ' już występuje w bazie!';
        } elseif (User::is_username($pdo, $_POST['new_username'])) {
            echo "Użytkownik: " . $_POST['new_username']
                . ' już występuje w bazie!';
        } else {
            $newUser = new User();

            $newUser->setUsername($_POST['new_username']);
            $newUser->setEmail($_POST['new_email']);
            $newUser->setPassword($_POST['new_password']);

            $newUser->saveToDB($pdo);

            echo 'Dodano nowego użytkownika';
        }
    } else {
        echo 'Podano niepełne dane';
    }
}
