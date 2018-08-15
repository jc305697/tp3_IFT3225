$(document).ready(function () {
    $("#usernameInscr").blur(verifUsername);
//$("#submit").click(afficheFormClient);
//$("#formGerant").submit(afficheFormGerant);
//$("#dateAnnul").change(convertiDateAnnul);
/*$("#dateReserv").on("load",function (event) {
        var date = new Date();//date d'aujourd'hui
        date.setDate(date.getDate()+1);
        //$(this).val(creeDate(date));
        document.getElementById('dateReserv').valueAsDate = date;//https://stackoverflow.com/questions/12346381/set-date-in-input-type-date
        $(this).attr("readonly","readonly");

    });//.change(convertiDateReserv).*/

$("#inscriptionForm").submit(verificationChamps);
//$("#faitReserv").submit(verifieDateVeille);
//$("#dateReserv").change(verifieDateVeille);
});
/*function convertiDateAnnul(event) {
    var date = new Date($("#dateAnnul").val());
    $("#dateAnnul").val(creeDate(date));
    var date = new Date($("#dateReserv").val());
    $("#dateReserv").val(creeDate(date));


}*/
/*function convertiDateReserv(event) {
    var date = new Date($("#dateReserv").val());
    $("#dateReserv").val(creeDate(date));


}*/
/*function creeDate(date) {
    //https://stackoverflow.com/questions/12346381/set-date-in-input-type-date
    var jour = ("0" + date.getDate()).slice(-2);
    var mois = ("0" + (date.getMonth() + 1)).slice(-2);
    return date.getFullYear()+"-"+mois+"-"+jour;
}*/

function verifieDateVeille(event) {
    var date = new Date();//date d'aujourd'hui
    date.setDate(date.getDate()+1);
    alert(date);
    alert(""+new Date($("#dateReserv").val()));
    var dateInput = new Date($("#dateReserv").val());
    if (date<dateInput || date>dateInput){
        alert("date invalide la date doit être celle de demain");
        event.preventDefault(event);
    }
}

function verifUsername() {
    var usernameStr = $("#usernameInscr").val();
    $.post("verifUsername.php",{username:usernameStr}).done(function (data) {
        if (data=="true"){

        }
        else if (data=="false"){
            alert("Le username est déja pris. Veuillez en choisir un autre");
        }
        else {
            alert("erreur système problème avec AJAX");
        }
    });
}



function verificationChamps(event) {

    if($("#passwordConf").val()==$("#password").val()){
        return true;
    }

    else{
        alert("Les mots de passe ne concordent pas");
        event.preventDefault(event);
    }
}
