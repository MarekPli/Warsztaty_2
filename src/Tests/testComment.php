<?php
include_once(__DIR__ . '/../Classes/Comment.php');
include_once(__DIR__ . '/../Classes/Tweet.php');
include_once(__DIR__ . '/../Classes/User.php');
include_once(__DIR__ . '/form_testComment.php');
include_once(__DIR__ . '/../DB_open.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

define(MAX_TXT, 60);

$faker = \Faker\Factory::create('pl');

function createRandomComment (PDO $base) {
    global $faker;
    $newComment = new Comment();
    $newComment->setText($faker->text(MAX_TXT) );
    // wybieram losowo Tweeta którego skomentuję
    $arr = Tweet::loadTweets($base);
    $i = $faker->numberBetween(0,count($arr)-1);
    $newComment->setPostId($arr[$i]->getId());
    // wybieram losowo autora komentarza spośród użytkowników
    $arr = User::loadAll($base);
    $i = $faker->numberBetween(0,count($arr)-1);
    $newComment->setUserId($arr[$i]->getId());

    $newComment->setCreationDate($faker->date);
    return $newComment;
}

function showComment ($obj, $last =0 ) {
    if ($obj) {
        if ($last == 0) {
            $last = $obj->getId();
        }
        echo '[ ' . $last . " ]\t" .
            ' (użytkownika  ' .
            $obj->getUserId() . '  do tweeta ' .
            $obj->getPostId(). ') ' .
            $obj->getText() . ' (' .
            $obj->getCreationDate() . ") <br>";
    } else {
        echo "Nie ma takiego komentarza!";
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = myOpenDatabase('Warsztaty_2');

    if (isset($_POST['create_comment'])) {
        echo 'Wybrano dodanie komentarza<br>';
//        sleep(3);
        $cc = createRandomComment($pdo);
        $last = $cc->saveToDB($pdo);
        showComment($cc, $last);
        $cc = null;

    } else if (isset($_POST['all_comments'])) {
        echo 'Wybrano wyświetlenie wszystkich komentarzy<br>';
        $arr = Comment::loadAllComments($pdo);
        for ($i = 0; $i < count($arr); $i++) {
            showComment($arr[$i]);
        }


    } else if (isset($_POST['modify_comment'])) {
            echo 'Wybrano redakcję (zmianę treści) komentarza nr ' .
                $_POST['modify_comment'] .'<br>';
            $cc = Comment::loadCommentById($pdo, $_POST['modify_comment']);
            if (!$cc) {
                echo "Nie ma takiego komentarza";
            } else {
                echo "Dawna treść: " . $cc->getText() . "<br>";
                $cc->setText($faker->text(MAX_TXT));
                echo "Obecna treść: " . $cc->getText() . "<br>";
                $cc->saveToDB($pdo);
                $cc = null;
            }

        } else if (isset($_POST['delete_comment'])) {
        echo 'Wybrano usunięcie komentarza nr ' . $_POST['delete_comment'] . ".<br>";
        $cc = Comment::loadCommentById($pdo, $_POST['delete_comment']);
        $result = false;
        if ($cc && $cc->getId() != -1) {
            showComment($cc);
            $result = $cc->delete($pdo);
        }
        if ($result === true) {
            echo 'Usunięto wskazany komentarz.<br>';
        } else {
            echo "Nie udało się usunąć wskazanego komentarza.<br>";
        }

    } else {
        echo 'Błąd w programie<br>';
    }
}

