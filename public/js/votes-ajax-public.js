(function( $ ) {
	'use strict';
	
	$( document ).ready(function() {
		$( ".vote-buttons" ).on( "click", function() {
			var nonce = $(".voting-wrap").attr('sec');
			var post_id = $(".voting-wrap").attr('post-id');
			var voteYesNo = $(this).attr('id');
			var voteIP = $(".voting-wrap").attr('address');
            var buttonClicked = $(this);

			jQuery.ajax({
				type : "post",
				dataType : "json",
				url : myAjax.ajaxurl,
				data : {action: "user_vote", post_id : post_id, vote: voteYesNo, nonce: nonce},
				success: function(response) {
				   if(response.type == "success") {
                        document.cookie = 'userVotedIP' + "=" + voteIP + "; path=/";
                        document.cookie = 'userVotedValue' + "=" + voteYesNo + "; path=/";
                        document.cookie = 'userVotedArticleID' + "=" + post_id + "; path=/";
                        $(".voting-wrap button").attr('disabled', 'disabled');
                        $(".voting-wrap button .btn-yes-text").html(response.vote_percentage_yes+'%');
                        $(".voting-wrap button .btn-no-text").html(response.vote_percentage_no+'%');
					    buttonClicked.addClass('clicked');
				   }
				   else {
					    alert("Your vote could not be added")
				   }
				}
			 });

		});
	});
	

})( jQuery );
