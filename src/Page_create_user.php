<!DOCTYPE html>
<h1>Strona tworzenia użytkownika</h1>
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<form action="Page_main.php" method="post" name="page_login">
    <label>Podaj imię i nazwisko:
        <input type="text" id="username" name ="new_username">
    </label>
    <br>
    <label>Podaj e-mail:
        <input type="text" id="email" name ="new_email">
    </label>
    <label>Podaj hasło:
        <input type="text" id="password" name ="new_password">
    </label>
    <input type="submit" id="exit" value ="Zarejestruj się">
</form>
<p id="prompt"></p>
<script>

function ajaxianCreateUser(username, email, haslo) {
    var result;
    $.ajax({
        type: "POST",
        url: "test1.php",
        dataType: "html", // oczekuje tekstu, wartość logiczna się źle przekazuje
        async: false, // ----------- !!!
        data: {
            username: username,
            email: email,
            haslo: haslo,
            option: "create_user"
        }
    }).done(function (response) {
        result = response;
    }) ;
    return result;
}


$(function() {
    var subm = $("#exit").parent();
    $('#prompt').text("");
    subm.on('submit', function(event) {
        var username = $("#username").val();
        var email = $("#email").val();
        var haslo = $("#password").val();
        if (username.length == 0
            || email.length == 0
            || haslo.length == 0 ) {
                event.preventDefault();
                $('#prompt').text("Podaj wszystkie dane");
            // alert("Podaj wszystkie dane");
        } else  {
            alert('jestem: ' + email + ' ' + username + ' ' + haslo);
            var answer = ajaxianCreateUser(username,email, haslo);
            switch(answer) {
                case "duplicated mail":
                    event.preventDefault();
                    $('#prompt').text("Adres mailowy się powtarza");
                    break;
                case "duplicated user":
                    event.preventDefault();
                    $('#prompt').text("Nazwa użytkownika się powtarza");
                    break;
                case "ok":
                    // nothing to do
                    break;
            }
        }
    });
});
</script>

</html>
