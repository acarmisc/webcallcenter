$(document).ready(function() {


	$('.edit_locally').live('mouseenter', function(){

	});

	$('.edit_locally').live('mouseleave', function(){

	});
	
	$('.edit_locally').live('click', function(){
		$('.pot-edit').attr('disabled',false);
		$('.update-b').fadeIn();
	});

	$('.pot-edit').live('focusin', function(){
		$(this).animate({'width':300}, 400);
	});

	$('.pot-edit').live('focusout', function(){
		$(this).animate({'width':250}, 800);
	});

	$('select[name=esito]').live('change',function(){
		var e = $(this).val();
		if (e == 'confermato') {
			
			openComposeMail($('input[name=act_id]').val());
			
		}else{
			if($('.send-mail-box').length > 0){
				$('.send-mail-box').remove();
			}
		}
	});

	
	$('#top-dialog').live('mouseleave',function(){
		$(this).delay(1000).animate({opacity:0.3},400);
	});

	$('#top-dialog').live('mouseenter',function(){
		$(this).animate({opacity:1},400);
	});


});

function openComposeMail(aid){

	$('body').after('<div id="" class="overlay">.</div>');

	$('.overlay').before('<div id="" class="send-mail-box" style="display:none"><h4>Invia e-mail alla Farmacia</h4></div>');
	$.ajax({
		type: 'GET',
		url: '?c=callcenter&m=prepare_email&aid='+aid,
		data: $('#act-details').serialize(),  
		success: function(responseText){
			$('.send-mail-box').html(responseText);		
		}
	});
	$('.send-mail-box').fadeIn();


}


function updateFarma(){
	$.ajax({  
		 type: 'POST',  
		 url: '?c=callcenter&m=updateFarma',  
		 data: $('#farma-det').serialize(),  
		 success: function(responseText) { 
			$('.update-b').after('<div id="update-res" class=""></div>');
			$('#update-res').html(responseText);
			$('.pot-edit').attr('disabled',true);
			$('.update-b').fadeOut('slow');
			$('#update-res').fadeOut('slow').remove();
	    	},
	    failure: function(){
	    	$('.update-b').after('<div id="update-res" class=""></div>');
			$('#update-res').html('<span class="negative-msg">Errore.</span>');
	    }
	});
}


function emailConfirm(){
	$.ajax({  
		 type: 'POST',  
		 url: '?c=callcenter&m=emailConfirm',  
		 data: $('#prepare_email').serialize(),  
		 success: function(responseText) { 
			$('.send-mail-box').html(responseText);
		}
	});
}


function more_mail(aid){
	$.ajax({  
		 type: 'GET',  
		 url: '?c=callcenter&m=more_mail&aid='+aid,  
		 data: $('#prepare_email').serialize(), 		 
		 success: function(responseText) { 
		 	$('.send-mail-box').animate({width:400},800);
			$('#more_mail_space').html(responseText);
			$('#more_mail_space').slideDown();
		}
	});
}

function emailCancel(){
	$('.send-mail-box').fadeOut().remove();
	$('.overlay').fadeOut().remove();
}

function openQ(cid){
	if($('#top-dialog').length > 0){
		$('#top-dialog').slideDown();
	}else{
		$('body').after('<div id="top-dialog" class="top-dialog"></div>');
		$('.top-dialog').animate({height:600},{duration:600,easing:'easeOutExpo'});
	}
	$.ajax({  
		 type: 'GET',  
		 url: '?c=callcenter&m=openq&cid='+cid,  
		 success: function(responseText) { 
			$('#top-dialog').html(responseText);
		}
	});	
	
}

function completeQ(){

}

function cancelQ(){
	$('#top-dialog').slideUp();
}


function updateCollaboratore(id,v){
	$.ajax({  
		 type: 'GET',  
		 url: '?c=callcenter&m=updateCollaboratore&id='+id+'&new='+v,  
		 success: function(responseText) { 
			$('#update-collaboratore').html(responseText);
		}
	});
}