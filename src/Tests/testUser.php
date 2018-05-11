<?php
include_once(__DIR__ . '/../Classes/User.php');
include_once(__DIR__ . '/.././DB_open.php');
include_once(__DIR__ . '/form_testUser.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

$faker = \Faker\Factory::create('pl');

function createRandomUser () {
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
        $user = createRandomUser();
        $i = $user->saveToDB($pdo);
        echo "[ " . $i . ' ] ' .
            $user->getUsername() . ' ' .
            $user->getEmail() .'<br>';
    } elseif (isset($_POST['nr_index'])) {
        echo 'Wybrano wczytanie użytkownika nr ' . $_POST['nr_index'] . "<br>";
        $user = User::loadUserById($pdo, $_POST['nr_index']);
        if ($user) {
            echo $user->getUsername() . '<br>';
            echo $user->getEmail() . '<br>';
        } else {
            echo 'Takiego użytkownika nie ma<br>';
        }
    } elseif (isset($_POST['add_all']) || isset($_POST['admin']) ) {
        echo 'Wybrano wczytanie wszystkich użytkowników' . "<br>";
        $arrUsers = User::loadAll($pdo);
        if (empty($arrUsers)) {
            echo 'Nie udało się wczytać żadnych użytkowników<br>';
        } else {
            foreach ($arrUsers as $user) {
                $s = $_POST['admin'];
                if (isset($_POST['admin'])) {
                    $s = ' < ' . $user->betrayPassword($s) . ' > ';
                    echo $user->getId() . ' ' .
                        $user->getUsername() . $s .
                        '<br>';
                }
                else {
                    echo "[ " . $user->getId() . ' ] ' .
                        $user->getUsername() . "\t\t---\t" .
                        $user->getEmail() .
                        '<br>';
                }
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
            // but cloning destroys id and setter for id is forbiden by conditions
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
            echo 'Pożegnamy się z: ' . $user->getUsername() . '<br>';
            $result = $user->delete($pdo);
            if ($result === true) {
                echo 'Usunięto wskazany element.<br>';
            }
        }
        if ($result === false) {
            echo "Nie udało się usunąć wskazanego elementu.<br>";
        }
    } elseif (isset($_POST['is_email'])) {
        $result = User::is_email($pdo, $_POST['is_email']);
        // echo $_POST['is_email'] . ' występuje ' . $result . ' razy<br>';
        if ($result) {
            echo $_POST['is_email']
                . ' występuje w bazie jako adres mailowy.<br>';
            echo "I co: " . $result->getEmail();
        } else {
            echo 'Nie znaleziono adresu mailowego '
                . $_POST['is_email'] . '<br>';
        }

    } elseif (isset($_POST['is_username'])) {
        $result = User::is_username($pdo, $_POST['is_username']);
        // echo $_POST['is_email'] . ' występuje ' . $result . ' razy<br>';
        if ($result) {
            echo "Użytkownik " . $_POST['is_username']
                . ' występuje w bazie.<br>';
        } else {
            echo 'Nie znaleziono użytkownika: '
                . $_POST['is_username'] . '<br>';
        }

    } else {
        echo 'Błąd w programie<br>';
    }

}

?>

