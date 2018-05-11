<?php
session_start();
//print_r($_SESSION);
?>
<!DOCTYPE html>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

<?php require_once(__DIR__ . '/../header_js.php') ?>


<form action="Page_user.php" method="post" >
    <label>Do strony wyświetlania użytkownika:
        <input type="text" id="user" name ="user">
        <input type="submit">
    </label>
</form>
<form action="Page_tweet.php" method="post" >
    <label>Do strony wyświetlania wpisu (tweeta) nr:
        <input type="text" id="user" size="16" name ="tweet">
        <input type="submit">
    </label>
</form>
<form action="Page_user_edit.php" method="post" >
<!--    <label>Do strony edycji użytkownika:-->
<!--        <input type="text" id="user" name ="user">-->
        <input type="submit" name ="user" value="Do strony edycji użytkownika">
<!--        <input type="submit">-->
<!--    </label>-->
</form>
<form action="Page_msg.php" method="post" >
<!--    <label>Do strony wiadomości:-->
<!--        <input type="text" id="user" name ="user">-->
        <input type="submit" name ="user" value="Do strony wiadomości">
<!--        <input type="submit">-->
<!--    </label>-->
</form>

<h1>Strona główna</h1>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<form action="../Json_commands.php" method="post">
    <label>Stwórz nowy wpis:
        <input type="text" id="tweet" name ="new_tweet" size="45">
    </label>
    <br>
    </label>
    <input type="submit" id="exit" value ="Dodaj wpis">
</form>
<p id="prompt"></p>
<script>

function ajaxianCollectTweets() {
    var result;
    $.ajax({
        type: "POST",
        url: "../Json_commands.php",
        dataType: "json",
        async: false, // ----------- !!!
        data: {
            option: "tweets"
        }
    }).done(function (response) {
        result = response;
        // $("#prompt").html(response);
        // nie: tym razem przeniesione do programu głównego
    }) ;
    return result;
}

function ajaxianNewTweet(prompt) {
    if (prompt != 0) {
        $.ajax({
            type: "POST",
            url: "../Json_commands.php",
            dataType: "json",
            async: false, // ----------- !!!
            data: {
                option:
                    "new_tweet"
            }
        }).done(function (response) {
            prompt = 0;
        });
    }
}

function ajaxianGetSesssionUsername(prompt) {
    var result;
    $.ajax({
        type: "POST",
        url: "../Json_commands.php",
        dataType: "html",
        async: false, // ----------- !!!
        data: {
            option: "username"
        }
    }).done(function (response) {
        result = response;
        $("#username").html(prompt + response);
        // alert(response); // no pewnie że ciągle sprawdzałem!
    }) ;
    return result;
}

$(function() {
    ajaxianGetSesssionUsername("Użytkownik: <br>");
    ajaxianGetDatename("Dzisiejszy dzień to: <br>");
    var p = document.getElementById('#user');
    var arrTweets = ajaxianCollectTweets();
    $("#prompt").html(arrTweets);
    ajaxianNewTweet(<?php echo $newTweet ?>);


    // for (var i=0; i<arrTweets.length; i++) {
    //     var newTweet = document.createElement('div');
    //     // newTweet.appendChild();
    //     alert("ddd " +arrTweets);
    // }
});


</script>
</html>

<?php
$newTweet = 0;
if ( $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new_tweet'])
        && !empty($_POST['new_tweet'])
    ) {
        $newTweet = 1;
    }
}

?>