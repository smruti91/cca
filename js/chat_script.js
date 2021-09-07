$(document).ready(function(){
     
	 var arr = []; // List of users	
	
	$(document).on('click', '.msg_head', function() {	
		var chatbox = $(this).parents().attr("rel") ;
		$('[rel="'+chatbox+'"] .msg_wrap').slideToggle('slow');
		return false;
	});
	
	
	$(document).on('click', '.close', function() {	
		var chatbox = $(this).parents().parents().attr("rel") ;
		$('[rel="'+chatbox+'"]').hide();
		arr.splice($.inArray(chatbox, arr), 1);
		displayChatBox();
		return false;
	});
	
	$(document).on('click', '#sidebar-user-box', function() {
	
	 var userID = $(this).attr("class");
	 var username = $(this).children().text() ;
	 var sender = $(this).children('span').attr('class') ;
	 alert(sender);
	 if ($.inArray(userID, arr) != -1)
	 {
      arr.splice($.inArray(userID, arr), 1);
     }
	 
	 arr.unshift(userID);
	 chatPopup =  '<div class="msg_box" style="right:300px" rel="'+ userID+'">'+
					'<div class="msg_head">'+username +
					'<div class="close">x</div> </div>'+
					'<div class="msg_wrap"> <div class="msg_body"><div class="msg_push"></div> </div>'+
					'<div class="msg_footer"><input type="hidden" value="'+sender+'" /><textarea class="msg_input" rows="4"></textarea></div> </div> </div>' ;					
				
     $("body").append(  chatPopup  );
	 displayChatBox(userID);
	});
	
	
	$(document).on('keypress', 'textarea' , function(e) {       
        if (e.keyCode == 13 ) { 		
        var msg = $(this).val();		
		$(this).val('');
		if(msg.trim().length != 0){				
		var chatbox = $(this).parents().parents().parents().attr("rel") ;
		var sender=$(this).siblings("input").val();
		// alert(chatbox);
		// alert(sender);
		$.post("../auditor/chat_index.php",{rec: chatbox,sender: sender,msg: msg,action: 'insert_chat'},function(res){
			
			if(res=="success"){
				console.log(chatbox);
				$('<div class="msg-right">'+msg+'</div>').insertBefore($('[rel="'+chatbox+'"] .msg_push').last());
				// $('.msg_body').scrollTop($('.msg_body')[0].scrollHeight);

				$(".msg_body").stop().animate({ scrollTop: $(".msg_body")[0].scrollHeight}, 1000);

			}
		});
		}
        }
    });
									
		
    
	function displayChatBox(userID){ 
	    i = 300 ; // start position
		j = 310;  //next position
		
		$.each( arr, function( index, value ) {  
		   if(index < 4){
	         $('[rel="'+value+'"]').css("right",i);
			 $('[rel="'+value+'"]').show();

			 // $('<div class="msg-left">'+"msg"+'</div>').insertBefore('[rel="'+value+'"] .msg_push');
			 // $('.msg_body').scrollTop($('.msg_body')[0].scrollHeight);

		     i = i+j;			 
		   }
		   else{
			 $('[rel="'+value+'"]').hide();
		   }
        });	
      // console.log($('[rel="'+userID+'"]').children('.msg_wrap').children(".msg_body"));
        $.post("../auditor/chat_index.php",{rec: userID,find_chat: 'find_chat'},function(res){
			 	$('[rel="'+userID+'"]').children('.msg_wrap').children('.msg_body').html(res);
			 	($('[rel="'+userID+'"]').children('.msg_wrap').children(".msg_body")).stop().animate({ scrollTop: $(".msg_body")[0].scrollHeight}, 1000);
			 });
	}
});