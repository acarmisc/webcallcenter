$(document).ready(function() {
   $('select').after('<input type="text" size="30" class="hide-me" />');
   
//   $('input[name=giorno]').datepicker({dataFormat:'d-m-Y'});


});

function test_storecheck(){
    $.ajax({
        url: "?c=gestioneCliente&m=test_storecheck",
        context: document.body,
        success: function(responseText){
            $('#core_space').append(responseText);
            }
    });
}

function show_services(storecheck_id){
    $.ajax({
    	url: "https://pharma3d.csopharmitalia.it/index.php/gestioneCliente/test_form/id/"+storecheck_id,
        context: document.body,
        success: function(responseText){
            $('#servizi').html(responseText);
            }
    });
}
