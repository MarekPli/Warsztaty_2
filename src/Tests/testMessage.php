<?php
include_once(__DIR__ . '/../Classes/Message.php');
include_once(__DIR__ . '/../Classes/User.php');
include_once(__DIR__ . '/../DB_open.php');
include_once(__DIR__ . '/form_testMessage.php');
require_once(__DIR__ . '/../../vendor/autoload.php');



$faker = \Faker\Factory::create('pl');

function createRandomMessage (PDO $base) {
    global $faker;
    $arr = User::indexes($base);
    if (count($arr) <= 1 ) {
        // samotny użytkownik musiałby pisać sam do siebie
        return null;
    }
    $newMessage = new Message();
    $sizeOfMessage = $faker->numberBetween(10,255);
    $newMessage->setText($faker->realText($sizeOfMessage) );
    $newMessage->setCreationDate($faker->date);
    $i = $j = $faker->numberBetween(0,count($arr)-1);
    while ($j === $i) {  // Don't want to send message to oneself
        $j = $faker->numberBetween(0, count($arr) - 1);
    }
    $newMessage->setSenderId($arr[$i]);
    $newMessage->setReceiverId($arr[$j]);
    return $newMessage;
}

function showMessage ($msg, $last_id = 0) {
    if ($msg) {
        if ($last_id == 0) {
            $last_id = $msg->getId();
        }
        echo "            [ " . $last_id. " ] ".
            "Wiadomość od " . $msg->getSenderId() . ' do ' .
            $msg->getReceiverId() .
            " z dnia " .$msg->getCreationDate() . '<br>' .
            "TREŚĆ: " . $msg->getText() .
'<br><br />';
    } else {
        echo "Nie ma takiej wiadomości!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo = myOpenDatabase('Warsztaty_2');

    if (isset($_POST['create_msg'])) {
        echo 'Wybrano dodanie wiadomości<br>';
        $msg = createRandomMessage($pdo);
        if ($msg === null) {
            echo "Nie udało się utworzyć wiadomości. ";
        } else {
            $i=$msg->saveToDB($pdo);
            showMessage($msg,$i);
            $msg = null;
        }
    } else if (isset($_POST['modify_msg'])) {
            echo 'Wybrano redakcję (zmianę treści) wiadomości<br>';
            $msg = Message::loadMessageById($pdo, $_POST['modify_msg']);
            if ($msg === null) {
                echo "Nie ma takiej wiadomości. ";
            }
            else {
                echo "Dawna treść: " . $msg->getText() . "<br>";
                $sizeOfMessage = $faker->numberBetween(10,255);
                $msg->setText($faker->realText($sizeOfMessage) );
                $msg->saveToDB($pdo);
                showMessage($msg);
                $msg = null;
            }

    } else if (isset($_POST['delete_msg'])) {
        echo 'Wybrano usunięcie wiadomości nr ' . $_POST['delete_msg'] . ".<br>";
        $msg = Message::loadMessageById($pdo, $_POST['delete_msg']);
        $result = false;
        if ($msg && $msg->getId() != -1) {
            showMessage($msg);
            $result = $msg->delete($pdo);
        }
        if ($result === true) {
            echo 'Usunięto wskazaną wiadomość.<br>';
        } else {
            echo "Nie udało się usunąć wskazanej wiadomości.<br>";
        }

    } else if (isset($_POST['all_msg'])) {
        echo 'Wybrano wyświetlenie wszystkich wiadomości<br><br />';
        $arr = Message::loadMessages($pdo);
        for ($i = 0; $i < count($arr); $i++) {
            showMessage($arr[$i]);
        }

    } else {
        echo 'Błąd w programie<br>';
    }
}

