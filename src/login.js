<html>
// alert("email: " +email+ " haslo: " +haslo);
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<script>
$(function() {
// document.addEventListener("DOMContentLoaded", function() {
    var subm = document.getElementById("exit").parentElement;

    // function funcSuccess(data){
    //     alert("Sukces: " + data);
    // }
    subm.addEventListener('submit', function(event) {
        var email = document.getElementById("email").value;
        var haslo = document.getElementById("haslo").value;
        // alert("email (raz): " +email+ " haslo: " +haslo);
        if (email.length == 0 || haslo.length == 0) {
            alert("email (pustka): " + email + " haslo: " + haslo);
            event.preventDefault();
            var newPara = document.createElement('p');
            newPara.innerText = "Niestety nie udało się";
            subm.appendChild(newPara);
        } else {
            // alert('Będzie Ajax..');
            $.ajax({
                type: "POST",
                url: "test.php",
                dataType: "html",
                data: {
                    email: email,
                    haslo: haslo
                },
                success: function (data)  {
                    alert("Sukces: " + data);
                }
            });
            // }).done(function (response) {
            //     alert("response: " + response);
            //     if (response == "no") {
            //         event.preventDefault();
            //         alert("email (brak w bazie): " + email + " haslo: " + haslo);
            //     } else {
            //         alert("Znalazłem w bazie: " + response);
            //     }
            //     alert("...był Ajax -- " + response + " no i tak...");
            // }).fail(function(a,b, c) {
            //     event.preventDefault();
            //     alert ("nie zwrócono poprawnej wartości, " +a+ ' ' +b+ ' ' +c);
            // }).always(function (xhr, status) {
            //     alert ("zawsze chodzi o: " + xhr + ' ' +status);
            // });
        }
    });
});
</script>
</html>