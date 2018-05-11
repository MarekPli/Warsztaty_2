<?php
include_once(__DIR__ . '/../Classes/Tweet.php');
include_once(__DIR__ . '/../Classes/User.php');
include_once(__DIR__ . '/../DB_open.php');
include_once(__DIR__ . '/form_testTweet.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

define(MAX_TXT, 140);

$faker = \Faker\Factory::create('pl');

function createRandomTweet (PDO $base) {
    global $faker;

    $newTweet = new Tweet();
    $random_length = $faker->numberBetween(10,MAX_TXT);
    $newTweet->setTweet($faker->realText($random_length) );
    $newTweet->setCreationDate($faker->date);
    $arr = User::indexes($base);
    $i = $faker->numberBetween(0,count($arr)-1);
    $newTweet->setUserId($arr[$i]);
    return $newTweet;
}

function showTweet ($obj, $last_id=0) {
    if ($obj) {
        if ($last_id==0) {
            $last_id = $obj->getId();
        }
        echo '[ '. $last_id . " ]\t od użytkownika " .
            $obj->getUserId() . ":<br>" .
            $obj->getTweet() . ' (' .
            $obj->getCreationDate() . ")<br><br>";
    } else {
        echo "Nie ma takiego tweeta!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pdo = myOpenDatabase('Warsztaty_2');

    if (isset($_POST['create_tweet'])) {
        echo 'Wybrano dodanie tweeta<br>';
        $tt = createRandomTweet($pdo);
        $last = $tt->saveToDB($pdo);
        showTweet($tt,$last);
        $tt = null;

    } else if (isset($_POST['modify_tweet'])) {
        echo 'Wybrano redakcję treści tweeta<br>';
        $tt = Tweet::loadTweetById($pdo, $_POST['modify_tweet']);
        if (!$tt) {
            echo "Nie ma takiego tweeta";
        }
        else {
            echo "Dawna treść: " . $tt->getTweet() . "<br>";
            $random_length = $faker->numberBetween(10,MAX_TXT);
            $tt->setTweet($faker->realText($random_length));
            $tt->saveToDB($pdo);
            showTweet($tt);
            $tt = null;
        }

    } else if (isset($_POST['delete_tweet'])) {
        echo 'Wybrano usunięcie tweeta nr ' . $_POST['delete_tweet'] . ".<br>";
        $tt = Tweet::loadTweetById($pdo, $_POST['delete_tweet']);
        $result = false;
        if ($tt && $tt->getId() != -1) {
            showTweet($tt);
            $result = $tt->delete($pdo);
        }
        if ($result === true) {
            echo 'Usunięto wskazany tweet.<br>';
        } else {
            echo "Nie udało się usunąć wskazanego tweetu.<br>";
        }

    } else if (isset($_POST['all'])) {
        echo 'Wybrano wyświetlenie wszystkich tweetów<br>';
        $arr = Tweet::loadTweets($pdo);
        for ($i = 0; $i < count($arr); $i++) {
            showTweet($arr[$i]);
        }

    } else {
        echo 'Błąd w programie<br>';
    }
}

