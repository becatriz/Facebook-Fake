
// Open side navigation
function sidenav_open() {
	$("#mySidenav").fadeIn(),$("#myOverlay").fadeIn()
}

// Close side navigation
function sidenav_close() {
	var e=$("#mySidenav");
	if($(window).width()<980) {e.removeClass("brz-43wsd"),e.fadeOut()}
}

// Used to toggle the menu on small screens when clicking on the menu button
function nav_controller() {
    var x = $("#tos_nav");
    x.toggleClass("brz-hide");
}

// Post form 
function alterForm(x,y) {
    if($("#youtube-76efh").is(':visible') && x.valueOf() !== String("#youtube-76efh").valueOf()){$("#movie_text-2134").removeClass('brz-text-active');$("#youtube-76efh").slideUp(200,function () {$(x).slideDown();});}   
    if($("#photo-84u78").is(':visible') && x.valueOf() !== String("#photo-84u78").valueOf()){$("#photo_text-2134").removeClass('brz-text-active');$("#photo-84u78").slideUp(200,function () {$(x).slideDown();});}   
    if($("#status-t4njhsdf").is(':visible') && x.valueOf() !== String("#status-t4njhsdf").valueOf()){$("#status_text-2134").removeClass('brz-text-active');$("#status-t4njhsdf").slideUp(200,function () {$(x).slideDown();});}   
	$(y).addClass('brz-text-active');
}

// Toggle navigation for browsable sections
function toggle_side_nav() {
	var e=$("#mySidenav");
	
	e.hasClass("brz-43wsd") ? (e.removeClass("brz-43wsd").fadeOut() ): (e.addClass("brz-43wsd").fadeIn()) 
}
	
// Toggle profile navigation
function showProfileNav(e) {
	var a=document.getElementById(e);
	
	-1==a.className.indexOf("brz-show") ? a.className+=" brz-show" : a.className=a.className.replace(" brz-show","") 
}

// User logout
function user_logOut(){
	var e=$("#installation_select").val();
    
	$.ajax({type:"POST",url:e+"/require/requests/actions/user_logout.php",cache:!1,
	
		beforeSend:function(){
			$("#user_logout_a").empty(),
			$("#user_logout_a").after('<div style="margin-top:1px;" class="brz-margin-left" ><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" width="22" height="22"></img></div>') 
		},
		
		success:function(e){$("body").append(e);clearInterval(again);}
		
	})
}
	
// Administration logout
function admin_logOut(){
	var e=$("#installation_select").val();
	$.ajax({type:"POST",url:e+"/require/requests/actions/admin_logout.php",cache:!1,
	   
    	beforeSend:function(){
			$("#user_logout_a").empty(),
			$("#user_logout_a").after('<div style="margin-top:1px;" class="brz-margin-left" ><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" width="22" height="22"></img></div>')
		},
			
		success:function(e){$("body").append(e)}
	})
}