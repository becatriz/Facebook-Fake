<?php 
// Add active notifications if enabled
function notifications($identifier,$path,$path2) {
	global $TEXT;	
	$echo = '<script>
			function dfsaafe45e(p) {	
				var installation = $(\'#installation_select\').val();
				$.ajax({
					type: "POST",
					url: installation + "%s",
					data: "pre=" + p,
					cache: false,
					success: function(data) {
						if(data == 0) {
							$("#dfbhne78342jsdf").empty();
						} else {
							x = $("#367u8sdfv55ysdfg");
							if(x.hasClass(\'brz-dfertwesdfsdfdf\')) {
								$("#43if89").prepend(\''.showBox($TEXT['_uni-Notifications_pending']).'\');
								x.removeClass("brz-dfertwesdfsdfdf");}$("#dfbhne78342jsdf").html(\'<span style="padding:2px;">\'+data+\'</span>\');
						}
						
						dfsaafe45e(data);
						
					}
				});

			};
			
			dfsaafe45e(0);
			
			function sadasdsasd(p) {
				var installation = $(\'#installation_select\').val();
				$.ajax({
					type: "POST",
					url: installation + "%s",
					data: "pre=" + p,
					cache: false,
					success: function(data) {
						if(data == 0) {
							$("#INBOX_COUNTER").empty();
						} else {
							$("#INBOX_COUNTER").html(\'<span style="padding:2px;">\'+data+\'</span>\');
						}
						sadasdsasd(data);
						
					}
				});
				
			};
			
			sadasdsasd(0);
		    </script>';
				
	return ($identifier == 1) ? sprintf($echo,$path,$path2) : ''; 
}
?>