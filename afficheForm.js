$(document).ready(function () {
    //$("#choixFormJoueur").submit(afficheForm);
$("#submit").click(afficheForm);
});

function afficheForm(event) {

    var $span = $("#spanForm");
    var $form;
    var nom = $("#nom").val();
    var prenom = $("#prenom").val();
    if ($("#dispoTerrain").prop("checked")) {
         $form =  $("<form action=\"\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   " +
            "<label>Date <input type=\"date\" name=\"dateDispo\" id=\"dateDispo\" />  </label>   " +
            "<label> Heure <input type=\"number\" name=\"heureDispo\" id=\"heureDispo\" value=\"1\" min=\"6\" max=\"21\" /> </label>   " +
            "<label>   <input type=\"submit\" name=\"submitDispoTerrain\" /> </label> " +
            "</form>");

    }
    else if ($("#listeReserv").prop("checked")){
         $form =  $("<form action=\"\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   " +
            "<label>Date <input type=\"date\" name=\"dateDispo\" id=\"dateDispo\" />  </label>   " +
            "<label>   <input type=\"submit\" name=\"submitDispoTerrain\" /> </label> " +
            "</form>");

    }
    else if ($("#annuleReserv").prop("checked")){

        $form = $("<form action=\"\" name=\"\" id=\"\" method=\"post\" accept-charset=\"utf-8\">   " +
            "<label>Date <input type=\"date\" name=\"dateDispo\" id=\"dateDispo\" />  </label>   " +
            "<label> Heure <input type=\"number\" name=\"heureDispo\" id=\"heureDispo\" value=\"1\" min=\"6\" max=\"21\" /> </label>   " +
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
            "<label> Heure <input type=\"number\" name=\"heureDispo\" id=\"heureDispo\" value=\"1\" min=\"6\" max=\"21\" /> </label> " +
            "<label>   <input type=\"submit\" name=\"submitDispoTerrain\" /> </label> " +
            "<input type='hidden' value=''"+ nom+" id='nom' name='nom'/>"+
            "<input type='hidden' value=''"+ prenom+" id='prenom' name='prenom'/>"+
            "</form>");
    }
    else {
        return;
    }
    $span.empty().append($form);
}
