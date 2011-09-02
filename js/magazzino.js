$(document).ready(function() {


	$('.mydates').live('focus',function(){
		$(this).datepicker({dateFormat:'d-m-yy'});
	});
});



function openIt(t,u){

	$.ajax({  
		 type: 'GET',  
		 url: u,
		 success: function(responseText) { 
			$('#'+t).html(responseText);
		}
	});	

}


function editIt(id,t,v){
	$.ajax({  
		 type: 'GET',  
		 url: '?c=magazzino&m=update_order&id='+id+'&t='+t+'&v='+v,
		 success: function(responseText) { 
			$('#spedizioni-window').html(responseText);
		}
		});	
}


function deleteIt(id){

if (confirm("Procedere con la cancellazione?")) {
    	$.ajax({  
		 type: 'GET',  
		 url: '?c=magazzino&m=delete_order&id='+id,
		 success: function(responseText) { 
			$('#magazzino-listwrapper').html(responseText);
		}
		});	
  }

}

function itArrives(id){
if (confirm("Vuoi registrare la spedizione come arrivata?")) {
    	$.ajax({  
		 type: 'GET',  
		 url: '?c=magazzino&m=it_arrives&id='+id,
		 success: function(responseText) { 
			$('#magazzino-listwrapper').html(responseText);
		}
		});	
  }

}

function manageIt(id){
	$('#ship-'+id).after('<div id="" class="intable-dialog"></div>');
	$.ajax({  
		 type: 'GET',  
		 url: '?c=magazzino&m=newone&id='+id,
		 success: function(responseText) { 
			$('.intable-dialog').html(responseText);
		}
		});	
}