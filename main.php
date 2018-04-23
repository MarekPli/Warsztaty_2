<?php
include_once (__DIR__ . '/scr/User.php');
include_once (__DIR__ . '/scr/DB_open.php');
include_once (__DIR__ . '/scr/form_asking.html');
require_once('vendor/autoload.php');
require_once('vendor/autoload.php');

$faker = \Faker\Factory::create('pl');

function createRandomUser (PDO $base) {
    global $faker;

    $newUser = new User();

    $newUser->setUsername($faker->firstName() . ' '
        . $faker->lastName());
    $newUser->setEmail($faker->email);
    $newUser->setPassword($faker->domainWord);

    return $newUser;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    print_r($_POST);
    $pdo = myOpenDatabase('Warsztaty_2');

// addRandomUser($pdo);
    if (isset($_POST['add_user'])) {
        echo 'Wybrano dodanie użytkownika<br>';
        $user = createRandomUser($pdo);
        $user->saveToDB($pdo);
        echo $user->getId() . ' ' .
            $user->getUsername() . '<br>';
    } elseif (isset($_POST['nr_index'])) {
        echo 'Wybrano wczytanie użytkownika nr ' . $_POST['nr_index'] . "<br>";
        $user = User::loadUserById($pdo, $_POST['nr_index']);
        if ($user) {
            echo $user->getUsername() . '<br>';
            echo $user->getEmail() . '<br>';
        } else {
            echo 'Takiego użytkownika nie ma<br>';
        }
    } elseif (isset($_POST['add_all'])) {
        echo 'Wybrano wczytanie wszystkich użytkowników' . "<br>";
        $arrUsers = User::loadAll($pdo);
        if (empty($arrUsers)) {
            echo 'Nie udało się wczytać żadnych użytkowników<br>';
        } else {
            foreach ($arrUsers as $user) {
                echo $user->getId() . ' ' .
                    $user->getUsername() . '<br>';
            }
        }
    } elseif (isset($_POST['modify_index'])) {
        echo 'Wybrano modyfikację użytkownika' . $_POST['modify_index'] . "<br>";
        $user = User::loadUserById($pdo, $_POST['modify_index']);
        if ($user !== false) {
            echo 'Poprzednio: ' . $user->getId() . ' ' .
                $user->getUsername() . '<br>';
            $user1 = createRandomUser($pdo);
//            $user = clone $user1;
            // better than below, there is no simple setPassword:
        $user->setUsername($user1->getUsername());
        $user->setEmail($user1->getEmail());
            $user1 = null;
            $user->saveToDB($pdo);

            echo 'Obecnie: ' . $user->getId() . ' ' .
                $user->getUsername() . '<br>';

        } else {
            echo 'Nie zmodyfikowano użytkownika id ' . $_POST['modify_index'];
        }
    } elseif (isset($_POST['delete_index'])) {
        echo 'Wybrano usunięcie użytkownika ' . $_POST['delete_index'] . "<br>";
        $user = User::loadUserById($pdo, $_POST['delete_index']);
        $result = false;
        if ($user !== false) {
            echo 'Identyfikator w obiekcie: ' . $user->getId() . '<br>';
            echo 'Pożegnamy się z: ' . $user->getUsername() . '<br>';
            $result = $user->delete($pdo);
            if ($result === true) {
                echo 'Usunięto wskazany element.<br>';
            }
        }
        if ($result === false) {
            echo "Nie udało się usunąć wskazanego elementu.<br>";
        }
    } else {
        echo 'Błąd w programie<br>';
    }

}



?>

