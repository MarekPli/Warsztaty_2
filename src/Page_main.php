<!DOCTYPE html>
<table>
    <td>
        <p id="username"></p>
    </td><td> </td><td>
        <p id="datename"></p>
    </td>
    <td>
        <form action="test1.php" method="post" >
            <input type="submit" name ="logout" value="Wyloguj się">
        </form>
            </td>
</table>
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
    <label>Do strony edycji użytkownika:
        <input type="text" id="user" name ="user">
        <input type="submit">
    </label>
</form>
<form action="Page_user_msg.php" method="post" >
    <label>Do strony wiadomości:
        <input type="text" id="user" name ="user">
        <input type="submit">
    </label>
</form>

<h1>Strona główna</h1>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<form action="test1.php" method="post">
    <label>Stwórz nowy wpis:
        <input type="text" id="tweet" name ="new_tweet">
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
        url: "test1.php",
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

function ajaxianGetSesssionUsername(prompt) {
    var result;
    $.ajax({
        type: "POST",
        url: "test1.php",
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
function ajaxianGetDatename(prompt) {
    var result;
    $.ajax({
        type: "POST",
        url: "test1.php",
        dataType: "html",
        async: false, // ----------- !!!
        data: {
            option: "datename"
        }
    }).done(function (response) {
        result = response;
        $("#datename").html(prompt + response);
    }) ;
    return result;
}

$(function() {
    ajaxianGetSesssionUsername("Użytkownik: <br>");
    ajaxianGetDatename("Dzisiejszy dzień to: <br>");
    var p = document.getElementById('#user');
    var arrTweets = ajaxianCollectTweets();
    $("#prompt").html(arrTweets);

    // for (var i=0; i<arrTweets.length; i++) {
    //     var newTweet = document.createElement('div');
    //     // newTweet.appendChild();
    //     alert("ddd " +arrTweets);
    // }
});


</script>
</html>
