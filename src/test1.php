<?php
session_start();

include_once(__DIR__ . '/User.php');
include_once(__DIR__ . '/Tweet.php');
include_once(__DIR__ . '/Comment.php');
include_once(__DIR__ . '/DB_open.php');


function createNewTweet($pdo, $textTweet){
    $tt = new Tweet();
    $user = User::is_username($pdo, $_SESSION['username']);
    $tt->setUserId($user->getId());
    $tt->setTweet($textTweet);
    $tt->setCreationDate(makeTodayDate());
    $result  = $tt->saveToDB($pdo);
    return $result;
}

function stringTweet ($pdo, $tweet) {
    if ($tweet) {
     $user = User::loadUserById($pdo, $tweet->getUserId());
    return "nr " .$tweet->getId(). " [od: " .
            $user->getUsername() . "]:<br>" .
            $tweet->getTweet() . ' (' .
            $tweet->getCreationDate() . ")<br>";
    } else {
        return null;
    }
}
function makeTodayDate(){
    $todayDate = new DateTime();
    return $todayDate->format('Y-m-d');
}

function authorTorC($pdo, $obj) {
    // pasuje do obiektu Tweet i Comment, mają taki sam geter
    $i = $obj->getUserId();
    $user = User::loadUserById($pdo, $i);
    return $user->getUsername();
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["new_tweet"]) && !empty($_POST["new_tweet"])) {
        $_POST['option'] = 'new_tweet';
    }
    if (isset($_POST["logout"]) && !empty($_POST["logout"])) {
        $_POST['option'] = 'logout';
    }
    if (isset($_POST["full_tweet"]) && !empty($_POST["full_tweet"])) {
        $_POST['option'] = 'full_tweet';
    }

    if (isset($_POST["option"]) && !empty($_POST["option"])) {

        $pdo = myOpenDatabase('Warsztaty_2');

        switch ($_POST["option"]) {

            case 'login':
                if (isset($_POST["haslo"]) && !empty($_POST["haslo"])
                    && isset($_POST["email"]) && !empty($_POST["email"])
                ) {
                    $result = User::is_email($pdo, $_POST["email"]);
                    if ($result) { // zwracam wartość tekstową, nie udaje się odczytać wartości binarnej
                        $_SESSION["username"] = $result->getUsername();
                        echo "yes";
                    } else {
                        echo "no";
                    }
                } else {
                    echo "ERROR in login";
                }
                break;

            case 'logout':
                session_destroy();
                header('Location: Page_login1.php');
                break;

            case 'create_user':
                if (isset($_POST["haslo"]) && !empty($_POST["haslo"])
                    && isset($_POST["username"]) && !empty($_POST["username"])
                    && isset($_POST["email"]) && !empty($_POST["email"])
                ) {
                    $result = User::is_email($pdo, $_POST["email"]);
                    if ($result) {
                        echo "duplicated mail";
                    } else {
                        $result = User::is_username($pdo, $_POST["username"]);
                        if ($result) {
                            echo "duplicated user";
                        } else {
                            $newUser = new User();
                            $newUser->setUsername($_POST['username']);
                            $newUser->setEmail($_POST['email']);
                            $newUser->setPassword($_POST['haslo']);
                            $newUser->saveToDB($pdo);
                            $_SESSION["username"] = $newUser->getUsername();

                            echo 'ok';
                        }
                    }
                } else {
                    echo "ERROR in creating new user";
                }
                break;

            case 'username':
                echo $_SESSION["username"];
//            header('Location: Page_main.php');
                break;

            case 'datename':
                echo makeTodayDate();
                break;

            case 'tweets':
                $arr = Tweet::loadTweets($pdo);
                $result = [];

                for ($i = 0; $i < count($arr); $i++) {
                    $result[] = stringTweet($pdo, $arr[$i]) . "<br>";
                }
                echo json_encode($result);
                break;

            case 'new_tweet':
                $tt = createNewTweet($pdo, $_POST['new_tweet']);
                header('Location: Page_main.php');
                break;

            case 'user_tweets':
                $user = User::is_username($pdo, $_SESSION['user']);
                if (!$user) {
                    echo json_encode("Nie ma takiego użytkownika!");
                    break;
                }
                $arr = Tweet::loadAllTweetsByUserId($pdo, $user->getId());
                $result = [];
//            echo "Tablica " . count($arr);
                for ($i = 0; $i < count($arr); $i++) {
                    $numComm = Comment::loadAllCommentsByPostId($pdo, $arr[$i]->getId());
                    $result[] = stringTweet($pdo, $arr[$i]) 
                        . 'Liczba komentarzy: ' . count ($numComm) . "<br><br>";
//                    for ($j=0; $j<count($numComm); $j++) {
//                        $result[] = '[KOMENTARZ]' . $numComm[$j]->getText(). "<br>";
//                    }
                }
                echo json_encode($result);
                break;

            case 'full_tweet':
                $tt = Tweet::loadTweetById($pdo, $_SESSION['tweet']);
                if (!$tt) {
                    echo json_encode("Nie ma takiego wpisu");
                    break;
                }
                $result = $tt->getTweet();
                $result .= "<br><br>Autor: " . authorTorC($pdo, $tt) ."<br>";
                $numComm = Comment::loadAllCommentsByPostId($pdo, $tt->getId());
                for ($i=0; $i<count($numComm); $i++){
                    $result .= "<br>Kom. od "
                        . authorTorC($pdo, $numComm[$i])
                        . ': ' . $numComm[$i]->getText();
                }
                echo json_encode($result);
                break;

            default:
                echo "ERROR: Not a standard option";
        }
    }
}

