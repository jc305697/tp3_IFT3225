$(document).ready(function () {
    $("#usernameInscr").blur(verifUsername);
//$("#submit").click(afficheFormClient);
$("#formGerant").submit(afficheFormGerant);
$("#dateAnnul").change(convertiDate);
$("#inscriptionForm").submit(verificationChamps);
//$("#faitReserv").submit(verifieDateVeille)
//$("#dateReserv").change(verifieDateVeille);
    $("#dateReserv").val(creeDate(new Date()));
    $("#dateReserv").attr("readonly","readonly");
});
function convertiDate(event) {
    var date = new Date($("#dateAnnul").val());
    $("#dateAnnul").val(creeDate(date));
}
function creeDate(date) {
    return date.getFullYear()+"-"+date.getMonth()+"-"+date.getDate();
}

function verifieDateVeille(event) {
    var date = new Date();//date d'aujourd'hui
    date.setDate(date.getDate()+1);
    var dateInput = new Date($("#dateReserv").val());
    if (date<dateInput || date>dateInput){
        alert("date invalide la date doit être celle de demain");
        event.preventDefault(event);
    }
}

function afficheFormGerant(event) {
    var $span = $("#spanGerant");
    var $form;

    if($("#ListeTerrainDispo").prop("checked")){
        $form =  $("<form action=\"listeTerrainDispo.php\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   " +
            "<label> Heure <input type=\"time\" name=\"heureDispo\" id=\"heureDispo\" step='3600' min=\"06:00:00\" max=\"21:00:00\" value='06:00:00'/> </label>   " +
            "</form>");
        event.preventDefault(event);
        return false;
    }
    else {
        return true;
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
