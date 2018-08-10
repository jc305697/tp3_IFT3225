$(document).ready(function () {
    //$("#choixFormJoueur").submit(afficheFormClient);
    $("#inscriptionForm").submit(verificationChamps);
    $("#usernameInscr").blur(verifUsername);
$("#submit").click(afficheFormClient);
$("#formGerant").submit(afficheFormGerant);
});

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

function afficheFormClient(event) {

    var $span = $("#spanForm");
    var $form;
    var nom = $("#nom").val();
    var prenom = $("#prenom").val();
    if ($("#dispoTerrain").prop("checked")) {
         $form =  $("<form action=\"\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   " +
            "<label>Date <input type=\"date\" name=\"dateDispo\" id=\"dateDispo\" />  </label>   " +
             "<label> Heure <input type=\"time\" name=\"heureDispo\" id=\"heureDispo\" step='3600' min=\"06:00:00\" max=\"21:00:00\" value='06:00:00'/> </label>   " +
            "</form>");

    }
    else if ($("#listeReserv").prop("checked")){
         $form =  $("<form action=\"\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   " +
            "<label>Date <input type=\"date\" name=\"dateDispo\" id=\"dateDispo\" />  </label>   " +
            "<label>   <input type=\"submit\" name=\"submitDispoTerrain\" /> </label> " +
             "<input type='hidden' value=''"+ nom+" id='nom' name='nom'/>"+
             "<input type='hidden' value=''"+ prenom+" id='prenom' name='prenom'/>"+
            "</form>");

    }
    else if ($("#annuleReserv").prop("checked")){

        $form = $("<form action=\"\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   " +
            "<label>Date <input type=\"date\" name=\"dateDispo\" id=\"dateDispo\" />  </label>   " +
            "<label> Heure <input type=\"time\" name=\"heureDispo\" id=\"heureDispo\" step='3600' min=\"06:00:00\" max=\"21:00:00\" value='06:00:00'/> </label>   " +
            "<label>   <input type=\"submit\" name=\"submitDispoTerrain\" /> </label> " +
            "<input type='hidden' value=''"+ nom+" id='nom' name='nom'/>"+
            "<input type='hidden' value=''"+ prenom+" id='prenom' name='prenom'/>"+
            "</form>");
        //pas besoin de numero de terrain puisque
    }
    else if($("#faitReserv").prop("checked")){
        $form = $("<form action=\"\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   " +
            "<label>Date <input type=\"date\" name=\"dateDispo\" id=\"dateDispo\" />  </label>   " +
            "<label>Numero de terrain <input type='number' name='numTerrain' min='1' max='5'/></label>" +
            "<label> Heure <input type=\"time\" name=\"heureDispo\" id=\"heureDispo\" step='3600' min=\"06:00:00\" max=\"21:00:00\" value='06:00:00'/> </label>   " +
            "<input type='hidden' value=''"+ nom+" id='nom' name='nom'/>"+
            "<input type='hidden' value=''"+ prenom+" id='prenom' name='prenom'/>"+
            "</form>");
    }
    else {
        return;
    }
    $span.empty().append($form);
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



function verificationChamps() {

    if($("#passwordConf").val()==$("#password").val()){
        return true;
    }

    else{
        alert("Les mots de passe ne concordent pas");
    }
}
