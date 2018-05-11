<?php
session_start();

include_once(__DIR__ . '/Classes/User.php');
include_once(__DIR__ . '/Classes/Tweet.php');
include_once(__DIR__ . '/Classes/Comment.php');
include_once(__DIR__ . '/Classes/Message.php');
include_once(__DIR__ . '/DB_open.php');


function createNewTweet($pdo, $textTweet){
    if (empty($textTweet)) {
//         nie wiem jak inaczej uniknąć dodawania pustych wpisów
        return;
    }
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

function preparePOSToption($option){
    if (isset($_POST[$option]) && !empty($_POST[$option])) {
        $_POST['option'] = $option;
    }
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST') {

    preparePOSToption('new_tweet');
    preparePOSToption('logout');
    preparePOSToption('full_tweet');
    preparePOSToption('messages');
    preparePOSToption('msg_single');

    if (empty($_POST['option'])) {
        header('Location: Pages/Page_main.php');
        // to gdy przekazano puty nowy tweet
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
                        $passVerified = $result->isTheSamePass($_POST["haslo"]);
                        if ($passVerified) {
                            $_SESSION["username"] = $result->getUsername();
                            $_SESSION["email"] = $result->getEmail();
                            echo "yes";
                        } else  {
                            echo "no";
                        }
                    } else {
                        echo "no";
                    }
                } else {
                    echo "ERROR in login";
                }
                break;

            case 'logout':
                $_SESSION = [];
                session_unset();
                session_destroy();
                header('Location: Page_login.php');
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
                $result = createNewTweet($pdo, $_POST['new_tweet']);
                header('Location: Pages/Page_main.php');
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

            case 'messages':
                $user = User::is_username($pdo, $_SESSION['username']);
                $id = $user->getId();
                $result = [];
                $arr = Message::loadMessagesIfId($pdo, $id);

                if (count($arr) == 0) {
                    echo json_encode("nie ma żadnych wiadomości");
                    break;
                }
                $result = [];
                for ($i=0; $i < count($arr); $i++){
                    $s =  '[ ' .$arr[$i]->getId() . ' ] ';
                    if ($id == $arr[$i]->getSenderId()) {
                        $s .= "Do " . User::loadUserById($pdo, $arr[$i]->getReceiverId() )->getUsername();
//                        $s .= "Do " .$arr[$i]->getReceiverId();
                    } else {
                        $s .= "Od " . User::loadUserById($pdo, $arr[$i]->getSenderId() )->getUsername();
                    }
                    $s .= ' z ' . $arr[$i]->getCreationDate() . ': ';
                    $text = $arr[$i]->getText();
                    if (mb_strlen($text) > 30 ) {
                        $text = substr($text,0,30) . '...';
                    }
                    $s .= " " . $text;
//                    bez sensu jest oznaczanie wiadomości
//                    jako nieprzeczytanej przez nadawcę
                    if ($arr[$i]->getWasRead() == 0
                        && $id == $arr[$i]->getReceiverId() ) {
                        $s = "<b>" . $s . "</b>";
                    }

                    $result[] = $s . "<br><br>";
                }
                echo json_encode($result);
                break;

            case 'msg_single':
                $mm = Message::loadMessageById($pdo, $_SESSION['msg_single']);

                $result = ""
                  .  " Nadawca: " . User::loadUserById($pdo, $mm->getSenderId() )->getUsername() . "<br>"
                  .  " Odbiorca: " . User::loadUserById($pdo, $mm->getReceiverId() )->getUsername() . "<br>"
                  . "Treść: " . $mm->getText() ;

                echo json_encode($result);
                break;

            default:
                echo json_encode("ERROR: Not a standard option");
        }
    }
}

