// Linked files
var login_file = installation + '/require/requests/actions/log.php',                               // {LOG_IN} main method
    signup_file = installation + '/require/requests/actions/sign.php',                             // {SIGN_UP} main method
    extras_file = installation + '/require/requests/actions/extras.php',                           // {EXTRAS} main method
    admin_file  = installation + '/require/requests/actions/admin_log.php';                        // Administration LOG_IN method

// Clear Captcha
function captchaClear(t) {
	if(t == 0) {
		switchPage('#password-page','#captcha-page');
	} else {
		mainLoaders(1);removeCaptcha();
	}
}

function submitLogin(e,t) {
	
	// Prevent default behaviour
	e.preventDefault();
	
	// Switch to processing page
	switchPage('#login-page','#animated-loader-page');
	
	// Start pre loaders
	mainLoaders(1);
	
	// Perform login
	logIn(t);
	
	// Prevent default behaviour	
	return false;
	
}

function submitRegistration(e) {
	
	// Prevent default behaviour
	e.preventDefault();
	
	// Switch to processing page
	switchPage('#login-page','#animated-loader-page');
	
	// Start pre loaders
	mainLoaders(1);
	
	// Perform login
	signUp();
	
	// Prevent default behaviour	
	return false;
	
}

function clearLogin(id) {
	
	// Delete recent login
	var clear_login = ajaxProtocol(extras_file,0,0,1,id,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
	if(!$("#RECENT_LOGINS > div").length) {
		$('#RECENT_LOGINS_TITLE').hide();
		addContent(1);
	}		
}

function reLogin() {
	
	// FadeOut
	$('#login-modal-recent').fadeOut(200);
	
	// Switch to processing page
	switchPage('#login-page','#animated-loader-page');
	
	// Start pre loaders
	mainLoaders(1);
	
	// Perform login
	var performed = ajaxProtocol(login_file,0,0,1,$("#login-modal-recent-add-name").val(),$("#login-modal-recent-add-pass").val(),0,0,0,0,0,0,0,0,0,0,0,1);
	
}

function loadLogin(username,name) {

	$('#login-modal-recent-img').attr('src',$('#R_LOGIN_IMG_NAME_'+username).attr('src'));
	$('#login-modal-recent-name').html(name);
	$('#login-modal-recent-add-name').val(username);
	$('#login-modal-recent-add-pass').val('');
	$('#login-modal-recent').fadeIn();
	
}

function addContent(t) {
	if(t == 1) {
		$('#CONTENT_MAIN_DISPLAY').show();
		
	}
}

function submitAdmin(e) {
	
	// Prevent default behaviour
	e.preventDefault();
	
	// perform login
	directAdmin();
	
	// Prevent default behaviour	
	return false;
	
}

function directAdmin() {
	
	$("#admin-modal").fadeOut();
	
	$("#response").html('<img src="'+installation+'/themes/'+theme+'/img/icons/loader.gif" width="40"></img>');

    // Scroll to top
	$("html,body").animate({scrollTop: 0},"slow");
	
	// Perform search
	admin();
		
}

// Send password recovery
function resetPassword() {
	window.location.href = installation + '/index.php?respond=3425sd&type=startrecovery&for=' + $("#forgot-password-page-username").val() ;
}

// Login user
function logIn(t){
	
	if(t == 1) {
		var performed = ajaxProtocol(login_file,0,0,1,$("#login-name-dr").val(),$("#login-pass-dr").val(),0,0,0,0,0,0,0,0,0,0,0,1);
	} else {
		var performed = ajaxProtocol(login_file,0,0,1,$("#login-page-name").val(),$("#login-page-password").val(),0,0,0,0,0,0,0,0,0,0,0,1);
	}
	
}

// Sign up user
function signUp(){
	var pass = $("#signup-page-password").val();
	var performed = ajaxProtocol(signup_file,0,0,1,$("#signup-page-name").val(),$("#signup-page-email").val(),pass,pass,$("#signup-page-cap").val(),$("#signup-page-fname").val(),$("#signup-page-lname").val(),$("input[name='gender_radio']:checked").val(),0,0,0,0,0,1);
}

// Login administration
function admin(){
	var performed = ajaxProtocol(admin_file,0,0,1,$("#admin-page-name").val(),$("#admin-page-password").val(),0,0,0,0,0,0,0,0,0,0,0,1);
}

function switchPage(fr,to) {
    
	// Slide loaders
	if(to == "#animated-loader-page"){

		$("#response").html('<img src="'+installation+'/themes/'+theme+'/img/icons/loader.gif" width="40"></img><style>.brz-add-preloader{animation: beatload 1.2s infinite;}</style>');
		
		// Scroll to top
	    $("html,body").animate({scrollTop: 0},"slow");	
		
	}

	// Scroll to top
	$("html,body").animate({scrollTop: 0},200);
	
}

function removeCaptcha() {
	$("#element_captcha").fadeOut(0);
}

// Message receiver
function switchMessage(message,type) {
	$("#response").html('<div class="brz-error-new brz-padding">'+message+'</div>');
	
}

function refreshElements() {                                          // Refresh PLUGINS
    return true;
}

// Request captcha image
function captcha(){

    var string = "",
    character_set = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	// Generate captcha
    for( var i=0; i < 5; i++ )
        string += character_set.charAt(Math.floor(Math.random() * character_set.length));

	$("#element_captcha").empty();
	$('#element_captcha').html('<input id="signup-page-cap" class="brz-input brz-round-xlarge brz-border brz-hover-light-grey brz-margin-top captcha" type="text" placeholder="Captcha" style="background-image: url(./require/requests/content/captcha.php?capt='+ string +'&dark=blue)">'); 

};

// JS pre loaders
function mainLoaders(t) {                                           
	var el = ".mainloaders-class",
	el2 = "#mainloaders-id" ,
	lod = "mainloaders-bar" 
	lod2 = "#mainloaders-bar";
	$(el2).html('');		
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
}

// Animation
function rotate(load,el){                                            
    $(load).animate( { left: $(el).width() }, 1300, function(){
    $(load).css("left", -($(load).width()) + "px");
    rotate(load,el);
    });
}

// AJAX request
function ajaxProtocol(fl,p,f,t,v1,v2,v3,v4,v5,v6,v7,v8,v9,v10,ff,i,req,body) {  
		$.ajax({
		    type: "POST",
		    url: fl,
		    data: "p=" + p + "&f=" + f + "&t=" + t + "&ff=" + ff + "&i=" + i + "&v1=" + v1 + "&v2=" + v2 + "&v3=" + v3 + "&v4=" + v4 + "&v5=" + v5 + "&v6=" + v6 + "&v7=" + v7 + "&v8=" + v8 + "&v9=" + v9 + "&v10=" + v10,
		    cache: false,
		    success: function(data) {
                return handover(v9,v10,data,body);   
		    }
	    });
}

// AJAX response handler
function handover(v9,v10,data,body) {
	if(body == 1) {      // Universal pop up managing component
		$("#attach-response").append(data);
	}
}
