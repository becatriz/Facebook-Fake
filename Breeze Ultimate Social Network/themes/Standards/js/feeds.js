/*--------------------------------------------------------*/
/* Breeze Ultimate Social networking platform                    */
/* MAIN JAVASCRIPT FILE - Standards theme                 */
/*                                                        */
/* Copyright © Breeze developers - All rights reserved.   */
/*--------------------------------------------------------*/
 
// Linked Files
var installation = $('#installation_select').val(),                                 // Installation URL
    post_love_file = '/require/requests/actions/love.php',                          // Like | Love post
    chat_description_file = '/require/requests/load/chat_description.php',          // Load chat description
    save_edit_file = '/require/requests/actions/save_edits.php',                    // Save edited posts or chats
    send_message_file = '/require/requests/actions/quick_message.php',              // Send message from profile page
    add_comment_file = '/require/requests/actions/comment.php',                     // Post comment
    add_message_file = '/require/requests/actions/message.php',                     // Post message
	load_tabs_file = '/require/requests/load/settings_tabs.php',                    // Load settings tabs
    load_tab_content_file = '/require/requests/load/settings_wizards.php',          // Load tab settings
    save_tabs_file = '/require/requests/update/tab_settings.php',                   // Save settings
    edit_member_file = '/require/requests/actions/edit_member.php',                 // Add or remove chat member 
    edit_chat_member_request = '/require/requests/actions/request_member.php',      // Add or remove chat member request
    report_submit_file = '/require/requests/actions/report.php',                    // Report content
    users_file = '/require/requests/update/users.php',                              // User relations updater
    settings_file = '/require/requests/update/settings.php',                        // User settings updater
    more_photos_file = '/require/requests/more/photos.php',                         // Show more gallery photos
    more_search_file = '/require/requests/more/search.php',                         // Show more search results
    more_results_file = '/require/requests/more/results.php',                       // Show more relatives (profile page)
    more_relatives_file = '/require/requests/more/relatives.php',                   // Show more relatives
    more_feeds_file = '/require/requests/more/feeds.php',                           // Show more feeds
    more_notifications_file = '/require/requests/more/notifications.php',           // Show more notifications
    load_settings_file = '/require/requests/load/settings.php',                     // Load settings full page
    load_comments_file = '/require/requests/load/comments.php',                     // Load post comments
    load_lovers_file = '/require/requests/load/lovers.php',                         // Load post lovers
    load_post_file = '/require/requests/load/post.php',                             // Load post related data
    load_timeline_file = '/require/requests/load/timeline.php',                     // Load profile Time line
    load_results_file = '/require/requests/load/results.php',                       // Load relatives (profile page)
    load_relatives_file = '/require/requests/load/relatives.php',                   // Load relatives
    load_home_file = '/require/requests/load/home.php',                             // Load user home
    load_trending_file = '/require/requests/load/trending.php',                     // Load trending posts
    load_gallery_file = '/require/requests/load/gallery.php',                       // Load profile gallery
	load_chats_file = '/require/requests/load/chats.php',                           // Load chats page
    load_edit_chat_file = '/require/requests/load/edit_chat.php',                   // Load edit chat page
    load_chat_form = '/require/requests/load/chat_window.php',                      // Load chat form
    load_about_file = '/require/requests/load/about.php',                           // Load user's about section
    load_notifications_file = '/require/requests/load/notifications.php',           // Load notifications
    load_photos_file = '/require/requests/load/photos.php',                         // Load photos
    load_messages_file = '/require/requests/load/messages.php',                     // Load chat messages
    live_chat_file = '/require/requests/content/active_chat.php',                   // Live chat
    live_cont_file = '/require/requests/content/html.php',                          // Get basic conent file
    load_profile_file = '/require/requests/load/profile.php',                       // Load profile
    load_group_file = '/require/requests/load/group.php',                           // Load group processor
    load_page_file = '/require/requests/load/page.php',                             // Load page processor
    create_page_file = '/main/pages/create_page.php',                               // Create new page
    load_search_file = '/require/requests/load/search.php',                         // Load search results
    load_searchadmin_file = '/require/requests/load/admin_search.php',              // Load search results for administration
    load_search_p_file = '/require/requests/load/search_profile.php',               // Load search results from profile
    load_search_m_file = '/require/requests/load/search_members.php',               // Load search results from chats
    load_edit_profile_file = '/require/requests/load/settings_page.php',            // Load edit profile
    delete_content_file = '/require/requests/delete/content.php' ,                  // Delete Post | Comment
    execute_report_file = '/require/requests/actions/execute_report.php',           // Accept report and delete content
    execute_temp_file = '/require/requests/actions/execute_temp.php',               // Clear temp data
    load_admin_content_file = '/require/requests/load/admin_content.php',           // Load content for administration
    admin_settings_file = '/require/requests/update/admin_settings.php',            // Administration settings updater
	update_web_settings_file = '/require/requests/update/admin_websettings.php',    // Update web settings administration
	update_adds_settings_file = '/require/requests/update/admin_addsettings.php',   // Update adds settings administration
    update_users_settings_file = '/require/requests/update/admin_user_settings.php',// Update user settings administration
    update_profile_admin_file = '/require/requests/update/admin_user_profile.php';  // Update user profile administration

$(document).ready(function() {                                        // Document ready functions
	
	// Reload All Plugins
	refreshElements();
	
	// Manage window on scroll
	$(window).scroll(function() {
	    onScroll();
	});
	
	// Manage responsive window on resize
	$(window).resize(function() {
	    sizeElements();
	});

	// Manage on pop state
	$(window).on('popstate', function(ev) {                             // Display previous page
    
		// Fix pop state bug for old browsers and mobile browsers
		if (navigator.userAgent.match(/AppleWebKit/) && !navigator.userAgent.match(/Chrome/) && !window.history.ready && !ev.originalEvent.state) {
			return; 
		}
		// For apple users
		if (navigator.userAgent.match(/(iPad|iPhone|iPod|Android)/g) && !window.history.ready && !ev.originalEvent.state) {
			return; 
		}
		
		// Load from history
		location.reload();
		
	});
	
	// Universal search typing timer
	var typingTimer;
	
	// Time to wait for user typing response
    var typingInterval = 1000;
	

	// Live search on chat page
	$(document).on('keyup', '#add-members-search', function() { searchMembers(1); });
	$(document).on('keyup', '#remove-members-search', function() { searchMembers(0); });
	
	// Live search General 
	$(document).on('keyup', '#swsef89u3hj89sd', function(event) { 
		
		// Clear timer
		clearTimeout(typingTimer);
		
		// If some typing response from user found
		if ($('#swsef89u3hj89sd').val()) {
			
			// Add preloader
			$("#swsef89u3hj89sd").addClass("search-icon-loading").removeClass("search-icon");
            
			// Add timer to trigger for results
			typingTimer = setTimeout(function() {detachResults(); search(0,1,0);}, typingInterval);
			
		} else {
			
			// Clear preloader and timer if no letters typed
			$("#swsef89u3hj89sd").addClass("search-icon").removeClass("search-icon-loading");
		
		}
	});
	
	// Live search for admin and profile search for users (Combined)
	$(document).on('keyup', '#w2rsdf', function() {
	    
		clearTimeout(typingTimer);
	    
		// Admin search
	    if ($('#w2rsdf').val() && $('#w2rsdf').hasClass('admin-search')) {
		    typingTimer = setTimeout(function() {searchAdmin(0,0,1);}, typingInterval);
		}
		
		// User profile search
	    if ($('#w2rsdf').val() && !$('#w2rsdf').hasClass('admin-search')) {
		    typingTimer = setTimeout(function() {searchProfile();}, typingInterval);		
		}
		
	});
		
	// Manage Modals close
	$('body').on('click', 'div.brz-modal-close', function() {
	    $(this).parent().parent().parent().parent().remove();
	});
	
	// Manage Intelligent dropdowns
	$('body').on('click', '.brz-dropdown-click', function() {
	    
		// Convert to clickable dropdown once accessed
		$(this).removeClass('brz-dropdown-click').addClass('brz-dropdown-clicked');
		
		// Handle hovering effect on desktops
		if($(this).find('.brz-dropdown-content').hasClass('brz-show') && ($(window).width() > 993 || $(window).width() == 993)) {
			$(this).find('.brz-dropdown-content').removeClass('brz-show');
		}
		
	});
	
});

function store(url) {                                                 // Dynamically update history
	
	// Set URL
	var add = $('#installation_select').val() + url;

	// Return if user has reloaded page
	if(add == window.location.href) {
		return true;
	}	
    
    if (isIE()) {
	   
	    // Workout for old browsers( < IE9)	
		locate(add);	
	
    } else {
        window.history.pushState({path:add}, '', add);
    }
}

function isIE() {                                                     // Detect Internet Explorer < 10 
    if ($('html').hasClass('ie')){
        return true;
    }else{
        return false;
    }
}

function copyToClipboard(ee,t) {                                      // Copy content to clipboard 
	var $var = $("<input>");
    $("body").append($var);
    $var.val(ee).select();
    document.execCommand("copy");
    $var.remove();
	alert(t);
}

$.fn.isOnScreen = function(){                                         // Check whether element is on screen
	
	var win = $(window);
	
	var viewport = {
		top : win.scrollTop(),
		left : win.scrollLeft()
	};
	
	if(!$(this).length) {return false;}
	
	viewport.right = viewport.left + win.width();
	viewport.bottom = viewport.top + win.height();
	
	var bounds = this.offset();
	
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
	
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
	
};

function getSettings(t) {                                             // Load settings page
	
	// Update sidebar navigation activate->Edit profile	
	updateNavigation('e');
	
	// Add animation to left body	
	bodyLoader('content-body');
	
	updateMainTab('NULL');
	
	// Perform AJAX request to get requested settings page  
	var performed = ajaxProtocol(load_edit_profile_file,0,0,t,0,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
	// Add history 
	store('/user/edit');
	
}

function loadSettings(type) {                                         // Load settings type
	
	// Update settings nav
	setSettings(type);
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Remove older part
	if($(window).width() < 600) { 
	   $("#settings-nav").remove();
	}
	
	// Perform AJAX request to get requested settings page  
	var performed = ajaxProtocol(load_settings_file,0,0,type,0,0,0,0,0,0,0,0,0,0,0,0,1,25);
}

function profileLoadFollowings(p,f,t) {                               // Load people which are followed by user
    
	// Update inPAGE navigation (Profile navigation which includes about, followings, followers, time line ..)	
	updateProfileTab('4');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user followings	
	var performed = ajaxProtocol(load_results_file,p,f,t,0,0,0,0,0,0,0,0,0,0,0,0,1,5);

    // Update other stuff
	updateExpress();updateFriends();
}

function profileLoadFollowers(p,f,t) {                                // Load followers
 	
	// Update inPAGE navigation (Profile navigation which includes about, followings, followers, time line ..)	    
	updateProfileTab('3');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user followers	
	var performed = ajaxProtocol(load_results_file,p,f,t,0,0,0,0,0,0,0,0,0,0,0,0,1,5);

	// Update other stuff
	updateExpress();updateFriends();	
}

function profileLoadTimeline(p) {                                     // Load profile time line
    
	// Update inPAGE navigation 
	updateProfileTab('1');
	
	// Add animation to left body
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user posts	
	var performed = ajaxProtocol(load_timeline_file,p,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,5);
	
	// Update other stuff
	updateExpress();updateFriends();	
}

function profileLoadGallery(p) {                                      // Load profile gallery
    
	// Update inPAGE navigation (Profile navigation which includes about, followings, followers, time line ..)	     
	updateProfileTab('2');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user gallery	
	var performed = ajaxProtocol(load_gallery_file,p,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,5);

	// Update other stuff
	updateExpress();updateFriends();	
}

function profileLoadAbout(p) {                                        // Load profile about
    
	// Update inPAGE navigation (Profile navigation which includes about, followings, followers, time line ..)	     
	updateProfileTab('5');
	
	// Add animation to left body	
	bodyLoader('threequarter');
	
	// Perform AJAX request to fetch user about section	
	var performed = ajaxProtocol(load_about_file,p,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,5);
	
	// Update other stuff
	updateExpress();updateFriends();	
}

function load_more_results(p,f,t) {                                   // Load More ( Followers | Followings ) profile page
	
	// Add animation after last result (Actually used for users)
	lastPostLoads(1);
	
	// Perform AJAX request to get more users (Followers || Followings on profile page)	
	var performed = ajaxProtocol(more_results_file,p,f,t,0,0,0,0,0,0,0,0,0,0,0,0,1,23);
	
}

function load_more_profile_photos(p,f) {                              // Load More gallery photos
	
	// Add animation after last result
    lastPostLoads(1);
	
	// Perform AJAX request to fetch more photos (Profile page)
	var performed = ajaxProtocol(more_photos_file,p,f,0,0,0,0,0,0,0,0,0,0,0,0,0,1,20);
	
}

function load_more_search(f,v) {                                      // Load More search results
	
	// Add animation after last result HTML content
	lastPostLoads(1);
	
	// Perform AJAX request to get more search results
	var performed = ajaxProtocol(more_search_file,0,f,0,v,0,0,0,0,0,0,0,0,0,0,0,1,6);
	
}

function load_more_feeds(f,p) {                                       // Load More home feeds
	
	// Add animation after last result HTML content
    lastPostLoads(1);
 	
	// Perform AJAX request to get more news feeds
	var performed = ajaxProtocol(more_feeds_file,p,f,0,0,0,0,0,0,0,0,0,0,0,0,0,1,6);
	
	// Update other stuff
	updateExpress();updateFriends();
}

function load_more_profile_feeds(f,p) {                               // Load More feeds on profile page
	
	// Add animation after last result HTML content
    lastPostLoads(1);
	
	// Perform AJAX request to get more feeds (On profile page)
    var performed = ajaxProtocol(more_feeds_file,p,f,0,0,0,0,0,0,0,0,0,0,1,0,0,1,6);
	
}

function load_Lovers(id) {                                            // Load post lovers 
	
	// Add animation after last result HTML content
	lastPostLoads(1);
	
	// Perform AJAX request to get list of post lovers || LiKers
	var performed = ajaxProtocol(load_lovers_file,0,0,0,id,id,id,0,0,0,0,0,0,0,0,0,1,8);
	
}

function load_more_lovers(el,f,id) {                                  // Load More post lovers 
	
	// Add animation
	$("#"+el).find('div.preloader').append('<img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img>');
	
	// Perform AJAX request to fetch more post lovers
	var performed = ajaxProtocol(load_lovers_file,0,f,0,id,0,0,0,0,0,0,0,1,"#"+el,0,0,1,66);
	
}

function load_more_pages(el,f,id) {                                  // Load More pages
	
	// Add animation
	$("#"+el).find('div.preloader').append('<img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img>');
	
	// Perform AJAX request to fetch more pages
	var performed = ajaxProtocol(load_page_file,0,f,0,id,0,0,0,0,0,0,0,1,"#"+el,2,0,1,66);
	
}

function load_more_groups(el,f,id) {                                  // Load More groups 
	
	// Add animation
	$("#"+el).find('div.preloader').append('<img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img>');
	
	// Perform AJAX request to fetch more groups
	var performed = ajaxProtocol(load_group_file,0,f,0,id,0,0,0,0,0,0,0,1,"#"+el,17,0,1,66);
	
}

function load_more_relatives(f,t) {                                   // Load more followers or followings 

	// Add animation after last result HTML content
	lastPostLoads(1);
	
	// Perform AJAX request to fetch more relatives ( followers || followings )
	var performed = ajaxProtocol(load_relatives_file,0,f,t,0,0,0,0,0,0,0,0,0,0,0,0,1,23);
	
}

function ggj4wdf(v,t) {                                               // Notifications #1
	
	// Add animation after last result HTML content
	lastPostLoads(1);
	
	// Perform AJAX request to get notifications
	var performed = ajaxProtocol(more_notifications_file,0,v,0,0,0,0,0,0,0,0,0,0,0,t,0,1,6); 
	
}

function loadNotifications() {                                        // Load notifications from side bar full page mode
	
	// Add history 
	store('/user/notifications');

	// Update top navigation activate->NULL
	updateTopbar('NULL');
	
	// Update side navigation activate->Notifications
	updateNavigation('2');
    	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to load notifications
	var performed = ajaxProtocol(load_notifications_file,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,1,1);
	
	// Update other stuff
	updateExpress();updateFriends();	
}

function loadPhotos() {                                               // Load user gallery from side bar full page mode

	// Update side navigation
	updateNavigation('3');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to load photos 
	var performed = ajaxProtocol(load_photos_file,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,1,1);
	
	// Update other stuff
	updateExpress();updateFriends();	
}

function loadHome(t) {                                                 // Load Main page
	
	// Add history 
	if(t==1) {
		var z = 1;
	} else {
		store('/user/feeds');
		var z = 0;
	};
	
	// Close Notifications widget if opened
	s23u89dssh();

	// Update top navigation activate->Home
	updateTopbar('brz-class-home');

	// Update side navigation activate->Home
	updateNavigation('1');

	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load home
	var performed = ajaxProtocol(load_home_file,0,z,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1);

	// Update other stuff
	updateExpress();updateFriends();
}

function quickMessage(id,t) {                                         // Send a qucik message from profile page
	if(t == 0) {
		$("#quick-message-launcher").fadeOut(0);
		$("#quick-message").fadeIn();
	}
	if(t == 3) {
		$("#quick-message-launcher").fadeIn();
		$("#quick-message").fadeOut(0);
	} else {
		
		if(t == 1) {	
			var message = $("#quick-message-text").val();	

            // fade out text boxes and cancel button			
			$("#quick-message-text").fadeOut(0);
		    $("#quick-message-cancel").fadeOut(0);
			
			// Disable on click event
			$("#quick-message-trigger").attr('onclick','');
			
			// Do rest
			$("#quick-message-trigger").attr('disabled','yes').removeClass('brz-light-grey brz-hover-green').html('<img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" width="20" height="20"></img>');
            
			// Perform AJAX request to send message
	        var performed = ajaxProtocol(send_message_file,0,0,0,id,message,0,0,0,0,0,0,0,0,0,0,0,15);
			
		}
	}
}  

function loadGallery(p_id,i_id,n) {                                   // Load image gallery preview
	
	// If gallery is visile
	if($("#gallery-modal").is(':visible')) {
		
		$('#gallery-modal-image-inner-img').find('img').addClass('brz-hide');
		
		// Add required image on preview set
		if($("#GALLERY-INNER-IMAGE-FINAL-"+n).length) {
			$("#GALLERY-INNER-IMAGE-FINAL-"+n).removeClass('brz-hide');
		} else {			
			$("#gallery-modal-image-inner-img").append('<img id="GALLERY-INNER-IMAGE-FINAL-'+n+'" style="height:auto;max-width:100%;" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif">');
		    $("#GALLERY-INNER-IMAGE-FINAL-"+n).css('max-height',$("#gallery-modal-view").height());
			loadImage(i_id,"#GALLERY-INNER-IMAGE-FINAL-"+n);
		}
	
	// Else open gallery and add post content
	} else {

        $("#POST_IMAGE_"+p_id).remove();
        
		$("#DROP_POST_FUCS"+p_id).css('bottom','');
		
        $("#gallery-post-view").html($("#post_view_"+p_id).html());		       
	    
		$("#GALLERY-BUTTON-CLOSE").attr('onclick','$(\'#gallery-post-view\').html(\'\');ajaxProtocol(load_post_file,0,0,1,'+p_id+',0,0,0,0,0,0,0,0,'+p_id+',3,0,0,31);$(\'#gallery-modal\').fadeOut()');
		
		$("#gallery-modal-image-inner-img").html('<img id="GALLERY-INNER-IMAGE-FINAL-'+n+'" style="height:auto;max-width:100%;" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif">');

		$("#gallery-modal").fadeIn(500,function(){$("#post_view_"+p_id).html('')});

	    $("#GALLERY-INNER-IMAGE-FINAL-"+n).css('max-height',$("#gallery-modal-view").height());
	    
		loadImage(i_id,"#GALLERY-INNER-IMAGE-FINAL-"+n);
	
	}
	
	var gallery = $("#GALLERY_LOAD_"+p_id);
	
	// Toggle next image button
	if(gallery.find('input').length > n) {
		
		var onlcick = '$(\'#post_gallery_image_'+(n+1)+'_'+p_id+'\').click();';
		
		$("#GALLERY-INNER-BUTTON-NEXT").fadeIn().attr('onclick',onlcick);	
		
	} else {
		$("#GALLERY-INNER-BUTTON-NEXT").fadeOut().attr('onclick','');
	}
	
	// Toggle prev image button
	if(n !== 1) {

	    var onlcick = '$(\'#post_gallery_image_'+(n-1)+'_'+p_id+'\').click();';
		
	    $("#GALLERY-INNER-BUTTON-PREV").fadeIn().attr('onclick',onlcick);
	
	}else {
		$("#GALLERY-INNER-BUTTON-PREV").fadeOut().attr('onclick','');
	}
	
}
    
function liveChat() {                                                 // Live chatting
	
	// Get latest message ID
	var form_id = $("#active-form").val(),
	latest = $("#latest-message").val();

	// Generate token
	var token = generateToken(1);

	// Perform AJAX
	$.ajax({
		type: "POST",
	    url: $("#installation_select").val() + live_chat_file,
		data: "v1=" + form_id + "&v2=" + latest ,
		cache: false,
		success: function(data) {		

		    // Match token
			var valid_token = matchToken(1,token);
			
			if(valid_token && $("#messages-container-"+form_id).length) {
				$("#messages-container-"+form_id).append(data);
				refreshElements();
				
				var liveChatting = liveChat();
				
			}
			
		}
	});
	
}

function matchToken(t,token) {                                         // Token match
  
	// Chat token
    if(t == 1) {
        return($("#_TOKEN_CHAT").val() == token) ? true : false;	
    }
	
}

function generateToken(t) {                                           // Tokenizer for AJAX requests
  
    var token = "";
  
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    // Generate token
    for (var i = 0; i < 1060; i++) token += possible.charAt(Math.floor(Math.random() * possible.length));

	// Chat token
    if(t == 1) {
        $("#_TOKEN_CHAT").val(token);
    }

	return token;

}

function scrollToTop() {                                              // Scroll page to top
   $('html, body').animate({scrollTop: 0},"slow");
}

function scrollFull(el) {                                             // Scroll page to bottom
   $(el).animate({scrollTop: $(el)[0].scrollHeight},"slow");
}

function loadChats(f,v1,b) {                                          // Load all chats

	// Add history 
	store('/user/chats');
	
	// Close Notifications widget if opened
	s23u89dssh();
	
	if(b == 24) {

	    // Add animation after last post HTML content
	    lastPostLoads(1);
		
	} else {
		
		updateTopbar('brz-class-chats');
		
		// Update side navigation 
		updateNavigation('6');

	    // Add animation to full body	
	    bodyLoader('threequarter');
		
	}
	
	// Check for filters
	l = ($("#RIGHT_CHAT_TYPE").length && b == 2) ? 0 :1;
	
	// Perform AJAX request to get chats
	var performed = ajaxProtocol(load_chats_file,0,f,0,v1,l,0,0,0,0,0,0,0,0,0,0,1,b);
	
}

function loadEditChat(id) {                                           // Load edit chat page
	
	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load chat info
	var performed = ajaxProtocol(load_edit_chat_file,0,0,0,id,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
}

function loadChat(id) {                                               // Load live chatting window
	
	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load chat window
	var performed = ajaxProtocol(load_chat_form,0,0,0,id,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
}

function moreMessages(f,form) {                                       // Fetch old chat messages
	
	// Add animation after last message
	lastPostLoads(1);
	
	// Perform AJAX request to get more messages
	var performed = ajaxProtocol(load_messages_file,0,f,0,form,0,0,0,0,0,0,0,0,form,0,0,1,12);
	
}

function loadTrending(f,ff,b) {                                       // Load trending posts
	
	// Add history 
	store('/user/trends');
	
	if(b == 26) {

	    // Add animation after last post HTML content
	    lastPostLoads(1);
		
	} else {
		
		// Close Notifications widget if opened
	    s23u89dssh();
		
		// Update sidebar navigation activate->Edit profile	
	    updateNavigation('TREN');
		
		// Update top navigation
	    updateTopbar('brz-class-trending');

	    // Add animation to full body	
	    bodyLoader('threequarter');
		
	}

	// Perform AJAX request to get trending posts
	var performed = ajaxProtocol(load_trending_file,0,f,0,ff,0,0,0,0,0,0,0,0,0,0,0,1,b);
    
	// Update other stuff
	updateExpress();updateFriends();	
}

function loadPost(id) {                                               // Load post page	
 
	// Add history
	store('/view/'+id);
	
	// Update top navigation activate->NULL
	updateTopbar('NULL');
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to load post
	var performed = ajaxProtocol(load_post_file,0,0,0,id,0,0,0,0,0,0,0,0,0,1,0,1,1);
	
}

function search(f,b,t) {                                              // load search 
	
	// Search input box value
	var v = $.trim($('.swsef89u3hj89sd').val());
	
	switch(t) {
		
		// Pages search
		case 7:
		
			// Add history
			if(b==1){store('/search/page/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_PAGE_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			// Get filters
			var fil1 = $("input[name='filter-page-type']:checked").val();var fil2 = $("input[name='filter-people-liv']:checked").val();var fil3 = $("input[name='filter-people-edu']:checked").val();
			
			break;
			
		// Videos search
		case 6:
		
			// Add history
			if(b==1){store('/search/video/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_VID_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			break;
			
		// Photos search
		case 4:
		
			// Add history
			if(b==1){store('/search/at/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_AT_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			break;
        
        // Hashtag search
        case 3:

			if(b==1){store('/search/tag/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_TAG_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			// Get filters
			var fil1 = $("input[name='filter-hashtag-date']:checked").val();var fil2 = $("input[name='filter-hashtag-type']:checked").val();var fil3 = $("input[name='filter-hashtag-scope']:checked").val();
			
			break;
			
        // Group search
        case 2:

			if(b==1){store('/search/group/'+v)};
		
			// Check whether page is already fetched
	    	if($("#RIGHT_GRP_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
	
	        // Get filters
			var fil1 = $("input[name='filter-group-type']:checked").val();var fil2 = $("input[name='filter-people-liv']:checked").val();var fil3 = $("input[name='filter-people-edu']:checked").val();
			
			break;
			
        // People search(General)
		case 1:
			
			if(b==1){store('/search/people/'+v)};
			
			// Check whether page is already fetched
	    	if($("#RIGHT_PEP_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			// Get filters
			var fil1 = $("input[name='filter-people-home']:checked").val();var fil2 = $("input[name='filter-people-liv']:checked").val();var fil3 = $("input[name='filter-people-prof']:checked").val();var fil4 = $("input[name='filter-people-edu']:checked").val();
			
			break;
			
		case 0 :
		
			if(b==1){store('/search/top/'+v)};
			
			// Check whether page is already fetched
	    	if($("#RIGHT_TOP_SEARCHED").length) {var fetch = 1;} else {var fetch = 0;}
			
			break;
	}
	
	// Add preloader	
	if(b==1){
	    bodyLoader('content-body');
	} else {
	    if(b==25) {
	        bodyLoader('threequarter');
	    } else {
	        lastPostLoads(1);
	   }
	};
	
	// Remove typing preloader
	$("#swsef89u3hj89sd").addClass("search-icon").removeClass("search-icon-loading");
	
	// Perform AJAX request to get search results
	var performed = ajaxProtocol(load_search_file,0,f,t,v,fil1,fil2,fil3,fetch,fil4,0,0,0,0,0,0,0,b);
	
    // Update other stuff
	updateExpress();updateFriends();
}

function detachResults() {                                            // Detach search results
    $("#RIGHT_TAG_SEARCHED").remove();  // Detach Tags
    $("#RIGHT_AT_SEARCHED").remove();   // Detach Photos
    $("#RIGHT_PEP_SEARCHED").remove();  // Detach users
    $("#RIGHT_GRP_SEARCHED").remove();  // Detach groups
    $("#RIGHT_TOP_SEARCHED").remove();  // Detach top results
    $("#RIGHT_VID_SEARCHED").remove();  // Detach top results
    $("#RIGHT_PAGE_SEARCHED").remove();  // Detach top results
}

function moveSearch(t) {                                              // Change search type
    
	// Use profile search input if main input is empty
	if($('#swsef89u3hj89sd').val().length) {var v = $('#swsef89u3hj89sd').val();} else {var v = $('#w2rsdf').val();}
	
	detachResults();
	
	switch(t) {
		
		// Pages search
		case 7:
		$('.swsef89u3hj89sd').val(v);
		search(0,1,7);
        break;
		
		// Videos search
		case 6:
		$('.swsef89u3hj89sd').val(v);
		search(0,1,6);
        break;
        
		// Profile search
		case 5:
		$('#w2rsdf').val(v);
		searchProfile();
        break;	
		
		// Photos search
		case 4:
		$('.swsef89u3hj89sd').val(v);
		search(0,1,4);
		break;
		
		// Hashtag search
		case 3:
		$('.swsef89u3hj89sd').val(v);
		search(0,1,3);
		break;
		
		// Group search
		case 2:
		$('.swsef89u3hj89sd').val(v);
		search(0,1,2);
		break;
		
        // People search
		case 1:
		$('.swsef89u3hj89sd').val(v);
		search(0,1,1);
        break;
		
		// Top search
		default :
		$('.swsef89u3hj89sd').val(v);
		search(0,1,0);
		break;
	}
}

function searchProfile() {                                            // load search results (profile)

	// Search input box value
	var v = $('#w2rsdf').val();

	store('/search/me/'+v);

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to search profile
	var performed = ajaxProtocol(load_search_p_file,0,0,0,v,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
}

function editMember(form_id,id,t) {                                   // Add or remove chat member

    // Get type add or remove
    if(t == 1) {
		var box = "#addable-members";
	} else {
		var box = "#removable-members";
	}
	
	// Add pre loader
	$(box).html('<div align="center"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" style="margin:10%;" width="30" height="30"></img></div>')
	
	// Perform request
	var performed = ajaxProtocol(edit_member_file,0,0,t,form_id,id,0,0,0,0,0,0,0,box,0,0,0,13);
	
}

function editGroupMember(group_id,id,t,ss) {                           // Add or remove group member

	// Add pre loader
	var el = (ss) ? "#grp_sb_sl_sub" : "#new-modal-inner-content";
	
	$(el).html('<div align="center"><img class="brz-center" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" style="margin:10%;"></img></div>')
	
	// Work only for suggestions/sidebar add member wizards
	$('#messenger_dynamic_view_'+id+' ,#suggestions_dynamic_view_'+id).fadeOut(1000,
		function() {
			$('#messenger_dynamic_view_'+id+' ,suggestions_dynamic_view_'+id).remove();
			if($("#grp_sb_sl > div").length == 0) {$("#grp_sb_sl_ttl, #grp_sb_sl_cont").remove()};
		}
	);
	
	// Perform request
	var performed = ajaxProtocol(edit_member_file,0,0,t,group_id,id,0,0,0,0,0,0,0,el,1,0,0,13);
	
}

function inviteFriend(page_id,id) {                           // Send like invite

	// Add pre loader
	var el = "#grp_sb_sl_sub" ;
	
	$(el).html('<div align="center"><img class="brz-center" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" style="margin:10%;"></img></div>')
	
	// Work only for suggestions/sidebar add member wizards
	$('#messenger_dynamic_view_'+id+' ,#suggestions_dynamic_view_'+id).fadeOut(1000,
		function() {
			$('#messenger_dynamic_view_'+id+' ,suggestions_dynamic_view_'+id).remove();
			if($("#grp_sb_sl > div").length == 0) {$("#grp_sb_sl_ttl, #grp_sb_sl_cont").remove()};
		}
	);
	
	// Perform request
	var performed = ajaxProtocol(edit_member_file,0,0,0,page_id,id,0,0,0,0,0,0,0,el,2,0,0,13);
	
}
 
function requestMember(owner,form_id,user_id,type) {                  // Request a chat member

    // Get type 
    if(type == 1) {
		var box = "#addable-members";	
	} else {
		var box = "#removable-members";		
	}
	
	// Add pre loader
	$(box).html('<div align="center"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" style="margin:10%;" width="30" height="30"></img></div>')
	
	// Scroll to bottom
	if(/Mobi/.test(navigator.userAgent) == true) {
		$("html,body").animate({scrollTop: $(document).height()},2000);
	}
	
	// Send request
	var performed = ajaxProtocol(edit_chat_member_request,0,0,type,owner,form_id,user_id,0,0,0,0,0,0,box,0,0,0,13);
	
}

function searchMembers(t) {                                           // Search in chat members || friends

    // Get search type
	if(t == 1) {
		var val = $("#add-members-search").val(),
		    box = "#addable-members" ;	
		    box2 = "#removable-members" ;	
	} else {
		var val = $("#remove-members-search").val(),
			box = "#removable-members" ;
			box2 = "#addable-members" ;
	}
	
	// Add pre loader
	$(box).html('<div align="center"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" style="margin:10%;"></img></div>')
	
	// Create a view space
	$(box2).html('');
	
	// Scroll to bottom
	scrollFull("#CHAT_INFO");

	// Get results
	var performed = ajaxProtocol(load_search_m_file,0,0,t,val,$("#active-form").val(),0,0,0,0,0,0,0,box,0,0,0,13);
	
}

function loadRelatives(t) {                                           // Load followers or followings from side bar in full page mode 
	
	// Update side navigation whether followers or followings
	if(t == 1) {
		updateNavigation('5');
		
		// Add history 
	    store('/user/followings');
		
	} else {
		
		// Add history 
	    store('/user/followers');
	
		updateNavigation('4');
	}

	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request
	var performed = ajaxProtocol(load_relatives_file,0,0,t,0,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
}

function searchFollowings() {                                         // Load profile page 	

	var performed = ajaxProtocol(load_group_file,p,0,0,$("#new-modal-input").val(),0,0,0,0,0,0,0,0,"#new-modal-inner-content",3,0,1,13);
	
}

function pageFeeds(p,f,b,c) {                                          // Load group feeds

    var filter = (c !== undefined) ? c : 0;
	
    // Add animation to left body
	if(b == 5 || b == 1) {
    	
		if(c==1) {
			var gnav_up = 151;
		} else {
			if(c == 2) {
				var gnav_up = 152;
			} else {
				var gnav_up = 15;
			}
		}
		
		// Toggel G-Nav
		toggleGNav(gnav_up);
		
		if(p == 0) {
        	// Update side navigation activate->Home
			updateNavigation('PA');
            store('/user/pages');
        }
		if(b == 1) {bodyLoader('content-body');} else{ bodyLoader('threequarter');}
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_page_file,p,f,filter,0,0,0,0,0,0,0,0,0,0,15,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
	
}

function groupFeeds(p,f,b) {                                          // Load group feeds

    // Add animation to left body
	if(b == 5 || b == 1) {
    	
		// Toggel G-Nav
		toggleGNav(15);
		
		if(p == 0) {
        	// Update side navigation activate->Home
			updateNavigation('GR');
            store('/user/groups');
        }
		if(b == 1) {bodyLoader('content-body');} else{ bodyLoader('threequarter');}
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_group_file,p,f,0,0,0,0,0,0,0,0,0,0,0,15,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
	
}

function groupLog(p,f,b) {                                            // Load group log

    // Add animation to left body
	if(b == 5) {
	
	    bodyLoader('threequarter');

		// Toggel G-Nav
		toggleGNav(10);
		
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_group_file,p,f,0,0,0,0,0,0,0,0,0,0,0,10,0,1,b);
	
}

function pageLog(p,f,b) {                                            // Load page log

    // Add animation to left body
	if(b == 5) {
	
	    bodyLoader('threequarter');
		
		$("#page_bar_2").removeClass('brz-hvr-active').addClass('brz-hvr-active-1').siblings().removeClass('brz-hvr-active-1').addClass('brz-hvr-active');

		// Toggel G-Nav
		toggleGNav(10);
		
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request
	var performed = ajaxProtocol(load_page_file,p,f,0,0,0,0,0,0,0,0,0,0,0,10,0,1,b);
	
}

function groupMembers(p,f,b) {                                        // Load group members 

	// Add pre loader animation
	if(b == 5) {
		
		bodyLoader('threequarter');
		
		// Toggel G-Nav
		toggleGNav(11);
		
	} else {
		lastPostLoads(1);
	}

	// Perform AJAX request to fetch more relatives ( followers || followings )
	var performed = ajaxProtocol(load_group_file,p,f,0,0,0,0,0,0,0,0,0,0,0,11,0,1,b);
	
}
	
function groupRequests(p,f,t,uid,el,ex) {                             // Load group member requests	

	// Load requests
	if(t == 1) {
		
		// Toggel G-Nav
		toggleGNav(6);

		// Add animation to left body
		if(uid == 5) {bodyLoader('threequarter');}
		
		// Perform AJAX request
		var performed = ajaxProtocol(load_group_file,p,f,0,0,0,0,0,0,0,0,0,0,0,6,0,1,uid);
		
		// Update other stuff
	    updateExpress();updateFriends();
	
	// Process requests
	} else {
		
		// Fade out request
		$("#"+el).fadeOut();
 	
	    // Perform AJAX request to allow || remove user follow request
	    var performed = ajaxProtocol(load_group_file,p,0,t,uid,ex,0,0,0,0,0,0,0,0,7,0,0,0);
	}
	
}

function savePage(p,t) {                                               // Save page settings                           
    
	// Add wizard loader
	contentLoader(1,1);
 	
	// Perform AJAX request to allow || remove user follow request
	var performed = ajaxProtocol(load_page_file,p,0,0,t,$("#settings-page-1").val(),$("#settings-page-2").val(),$("#settings-page-3").val(),$("#settings-page-4").val(),$("#settings-page-5").val(),0,0,0,1,9,0,0,30);

}

function saveGroup(p) {                                               // Save group settings                           
    
	// Add wizard loader
	contentLoader(1,1);
 	
	// Perform AJAX request to allow || remove user follow request
	var performed = ajaxProtocol(load_group_file,p,0,0,$("#settings-group-u").val(),$("#settings-group-1").val(),$("#settings-group-2").val(),$("#settings-group-3").val(),$("#settings-group-4").val(),$("#settings-group-5").val(),$("input[name='group_approval_radio']:checked").val(),$("input[name='group_post_radio']:checked").val(),$("input[name='group_privacy_radio']:checked").val(),1,9,0,0,30);

}

function editGroupMemberPermissions(p,uid,t) {                        // Edit group user permissions

    // Load edit user wizard
    if(t == 0) {	
		// Load user permissions
	    loadModal(1);
		var performed = ajaxProtocol(load_group_file,p,0,0,uid,0,0,0,0,0,0,0,0,0,12,0,1,32);
	} else {
		contentLoader(1,2);
		var performed = ajaxProtocol(load_group_file,p,0,0,uid,$("input[name='group_post_radio']:checked").val(),$("input[name='group_cover_radio']:checked").val(),$("input[name='group_actvity_radio']:checked").val(),$("input[name='group_admin_radio']:checked").val(),0,0,0,0,2,13,0,1,30);
	}
	
}

function groupNav() {
	
	// Clear sub nav if any
	$("#S_NAV_LEFT").empty();
	
	// Add group nav
	$("#S_LEFT_CONTENT").detach().prependTo("#S_NAV_LEFT");
	
}

function toggleGNav(id) {
	
	// If togglable
	if($("#brz-add-GNav-"+id).length) {
		$(".brz-list2").removeClass("brz-hover-n_active").find("img.brz-padding").hide();
		$("#brz-add-GNav-"+id).addClass("brz-hover-n_active").find("img.brz-padding").show();
		$("#brz-add-GNav-"+id).find("img.brz-padding").show();
	}
	
}
	
function loadGroup(p,t,b) {                                           // Load group data 	
	
	// Close side navigation if user is on mobile
	sidenav_close();
	
	if(b == 1) {
	    
		// For IE < 9
		if (isIE()) {
			store('/'+p);
		}
	
		// Remove active state from side navigation
		updateNavigation(0);
	
		// Update top navigation activate->NULL
		updateTopbar('NULL');
	
		// Add animation to full body	
		bodyLoader('content-body');
		
	} 
	if(b == 5) {
	
		// Add animation to left body
		bodyLoader('threequarter');
	
	}
	
	// Toggel G-Nav
	toggleGNav(t);

	// Perform AJAX request
	var performed = ajaxProtocol(load_group_file,p,0,0,0,0,0,0,0,0,0,0,0,0,t,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
}

function loadPage(p,t,b,ex = 0,f = 0) {                                           // Load group data 	
	
	// Close side navigation if user is on mobile
	sidenav_close();

	if(b == 1) {
	    
		// For IE < 9
		if (isIE()) {
			store('/pages/'+p);
		}
	
		// Remove active state from side navigation
		updateNavigation(0);
	
		// Update top navigation activate->NULL
		updateTopbar('NULL');
	
		// Add animation to full body	
		bodyLoader('content-body');
		
	} 
	if(b == 5) {
	
		// Add animation to left body
		bodyLoader('threequarter');
	
	}
	if(b == 27) {
		lastPostLoads(1);
	}
	
	// Toggel G-Nav
	toggleGNav(t);
	
	// Perform AJAX request
	var performed = ajaxProtocol(load_page_file,p,f,ex,0,0,0,0,0,0,0,0,0,0,t,0,1,b);

	// Update other stuff
	updateExpress();updateFriends();
}

function createPage(t) {                                              // Create New page
	
	// Get page name
	var page_name = $("#page_name_"+t).val();
	
	// Get page category
	var page_category = (t == 1) ? $("#page_category").val() : $("#pag_cat_"+t).val() ;
	
	// Get page info : address
	var page_address = (t == 1) ? $("#page_address").val() : 0 ;

    // Add wizard loader
	contentLoader(1,t);
	
	// Load tab
	var performed = ajaxProtocol(create_page_file,0,0,t,page_name,page_category,page_address,0,0,0,0,0,0,t,0,0,1,30);	
	
}

function loadProfile(p) {                                             // Load profile page 	
	
	// Close side navigation if user is on mobile
	sidenav_close();
	
	// For IE < 9
	if (isIE()) {
		store('/'+p);
	}
	
	// Remove active state from side navigation
	updateNavigation(0);
	
	// Update top navigation activate->NULL
	updateTopbar('NULL');
	
	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request
	var performed = ajaxProtocol(load_profile_file,p,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1);
	
	// Update other stuff
	updateExpress();updateFriends();
}

function relBtn(el,t,i,h,o) {                                         // Follow | Request | undo follow | undo request | -> CSS styling 
	
	// Select button
	var x = $("#"+el);

	// Import strings
	importLang();
	
	// Add requested text
	var html = '<img class="nav-item-text-inverse-big brz-img-'+i+'" alt="" src="'+lang[8]+'">&nbsp;'+h;
	
	// Add requested on click event
	x.attr("onclick",o);
	
	// Update inner html content
	x.html(html);

	// Add requested title to button
	document.getElementById(el).title = t;
	
}

function relBtn2(el,t,i,h,o) {                                        // Request group                   
	
	// Select button
	var x = $("#"+el+"_inner"),y = $("#"+el);

	// Import strings
	importLang();
	
	// Swticher
	y.attr("onclick","");
	
	// Add requested text
	var html = '<img class="nav-item-text-inverse-big brz-img-'+i+'" alt="" src="'+lang[8]+'">&nbsp;'+h;
	
	// Update onclick events
	x.attr("onclick",o);
	y.attr("onclick","$('#"+el+"').next().removeClass('brz-hide');");
	
	// Update inner html content
	x.prop('title', t).html(t).parent().parent().addClass('brz-hide');;
	
	// Update button
	y.html(html);
	y.prop('title', t)

}

function follow(id,el,ta,tb,ia,ib,ha,hb) {                            // Follow user
	
	// Update button styles,on click events and titles
	relBtn(el,tb,ib,hb,"unfollow('"+id+"','"+el+"','"+tb+"','"+ta+"','"+ib+"','"+ia+"','"+hb+"','"+ha+"')");

	
	// Perform AJAX request to follow user
	var performed = ajaxProtocol(users_file,id,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function unfollow(id,el,ta,tb,ia,ib,ha,hb) {                          // Undo follow | request 

	// Update button styles,on click events and titles
	relBtn(el,tb,ib,hb,"follow('"+id+"','"+el+"','"+tb+"','"+ta+"','"+ib+"','"+ia+"','"+hb+"','"+ha+"')");
	
	// Perform AJAX request to UnFollow user
	var performed = ajaxProtocol(users_file,id,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);	
}

function joinGroup(id,el,ta,tb,ia,ib,ha,hb,perform,callback) {        // Update group relations 

	// Update button styles,on click events and titles
	relBtn2(el,tb,ib,hb,"joinGroup('"+id+"','"+el+"','"+tb+"','"+ta+"','"+ib+"','"+ia+"','"+hb+"','"+ha+"','"+callback+"','"+perform+"');");
	
	// Perform AJAX request to UnFollow user
	var performed = ajaxProtocol(users_file,id,0,perform,0,0,0,0,0,0,0,0,0,0,1,0,0,98);	
}

function likePage(id,el,ta,tb,ia,ib,ha,hb,perform,callback) {        // Update group relations 

	// Update button styles,on click events and titles
	relBtn2(el,tb,ib,hb,"likePage('"+id+"','"+el+"','"+tb+"','"+ta+"','"+ib+"','"+ia+"','"+hb+"','"+ha+"','"+callback+"','"+perform+"');");
	
	// Update like btn for mobiles
	if(perform == 1) {
        $("#page_like_"+el).find("span.brz-text-it").html("<i class=\"fa fa-check brz-text-blue-dark\"></i> " +hb);
	} else {
		$("#page_like_"+el).find("span.brz-text-it").html("<i class=\"fa fa-thumbs-up brz-text-grey\"></i> " +hb);
	}
	
	// Perform AJAX request to UnFollow user
	var performed = ajaxProtocol(users_file,id,0,perform,0,0,0,0,0,0,0,0,0,0,2,0,0,98);	
}

function request(id,el,ta,tb,ia,ib,ha,hb) {                           // Request user

	// Update button styles,on click events and titles
	relBtn(el,tb,ib,hb,"unrequest('"+id+"','"+el+"','"+tb+"','"+ta+"','"+ib+"','"+ia+"','"+hb+"','"+ha+"')");
	
	// Perform AJAX request to request user for following
	var performed = ajaxProtocol(users_file,id,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function unrequest(id,el,ta,tb,ia,ib,ha,hb) {                         // Undo request

	// Update button styles,on click events and titles
	relBtn(el,tb,ib,hb,"request('"+id+"','"+el+"','"+tb+"','"+ta+"','"+ib+"','"+ia+"','"+hb+"','"+ha+"')");

	// Perform AJAX request to UNDO request
	var performed = ajaxProtocol(users_file,id,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function doLove(el,id,call) {                                         // Love post
	
	// Import strings
	importLang();
	
	// Update like button styles
	$(el).html('<span class="">'+lang[3]+'</span>').attr("onclick","noLove('"+el+"','"+id+"','"+call+"')");
	
	$(el).addClass("brz-loved-it").removeClass("brz-love-it");
	
	// Slecet value
	if($('#post_view_number_likes_'+id).length === 0) {var count = lang[1]} else {var count = lang[0];}
	
	// Update like counter
	$('#post_view_is_like_'+id).html(count);

	// Perform AJAX request to love post
	var performed = ajaxProtocol(post_love_file,0,0,'1',id,call,0,0,0,0,0,0,0,0,0,0,0,98);
	
}

function noLove(el,id,call) {                                         // Undo love
	
	// Import strings
	importLang();
	
	// Update like button styles
	$(el).html('<span class="">'+lang[2]+'</span>').attr("onclick","doLove('"+el+"','"+id+"','"+call+"')");
	
	$(el).addClass("brz-love-it").removeClass("brz-loved-it");
	
	// Update like counter
	$('#post_view_is_like_'+id).html('');
	
	// Perform AJAX request to remove love from post
	var performed = ajaxProtocol(post_love_file,0,0,0,id,call,0,0,0,0,0,0,0,0,0,0,0,98);
	
}

function loadComments(id) {                                           // Load comments 

	// fade in comments
    $("#comments-view-"+id).fadeIn(0);
    
	// Add preloader
	csLoader(id,1,1);
	
	// Load post comments
	var performed = ajaxProtocol(load_comments_file,0,0,0,id,0,0,0,0,0,0,0,id,0,0,0,0,9);	
	
}

function loadPreviousComments(id,f) {                                 // Load more comments 
    
	// Remove value container
	csLoader(id,1,1);
	
	// Perform AJAX request to load more comments
	var performed = ajaxProtocol(load_comments_file,0,f,0,id,2,0,0,0,0,0,0,id,2,0,0,0,9);
	
}

function submitMessage(e,id) {                                        // Submit chat message
	
	// Prevent form submit and URL redirect
	e.preventDefault();
	
	// Call add Comment function 
	addMessage(id);
	
	// Return false also prevent default
	return false;
	
}

function addMessage(id) {                                             // Add chat message
	
	// Comment text
	var text = $("#add-message-text").val();
	
	resetForm("chat-form-window");
	
	// Add loader animations to comments container
	chatLoaders(1);
	
	// Perform AJAX request
	var performed = ajaxProtocol(add_message_file,0,0,0,id,text,0,0,0,0,0,0,0,0,0,0,0,98);	
	
}

function exitChat(el,id,t){                                           // Exit chat form
    
	// Update button style
	$("#"+el).attr('onclick','').removeClass('brz-hover-red brz-btn brz-light-grey').html('<div id ="pre-loader-update-status" style="margin-top:1px;"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" width="22" height="22"></img></div>');
	
	// Perform AJAX request to exit chat
	var performed = ajaxProtocol(delete_content_file,0,0,t,id,0,0,0,0,0,0,0,0,0,0,0,0,98);
	
}

function submitComment(e,id) {                                        // Submit Comment 
	
	// Prevent form submit and URL redirect
	e.preventDefault();
	
	// Add preloader
	csLoader(id,2,1);
	
	// Disable form
	$("#form-add-comment-"+id).css('pointer-events','none');

	// Perform AJAX request
	var performed = ajaxProtocol(add_comment_file,0,0,0,id,1,$("#form-add-comment-text-"+id).val(),0,0,0,0,0,id,1,0,0,0,9);
	
	// Return false also prevent default
	return false;
	
}

function proccessRequest(p,el,t){                                     // Respond to friend request
	
	// Add loader animations in notifications widget
	notificationLoaders(1,1);
	
	// Remove last notification id holding element
	$('#7h4sd4').remove();
	
	// Fade out notification
	$("#"+el).fadeOut();
 	
	// Perform AJAX request to allow || remove user follow request
	var performed = ajaxProtocol(users_file,p,0,t,0,0,0,0,0,0,0,0,0,0,0,0,0,3);
	
}

function notsEditMember(type,el,nid,formid,userid,t){                 // Respond to member request
	
	// Remove last notification id holding element
	$('#7h4sd4').remove();
	
	// Fade out notification
	$("#"+el).fadeOut();
 	
	// Perform AJAX request to delete notification
	var performed = ajaxProtocol(delete_content_file,0,0,4,nid,0,0,0,0,0,0,0,0,0,0,0,0,0);
	
	if(t == 1) {
		// Add member
	    editMember(formid,userid,1);	
	} else {
		// Remove member
	    editMember(formid,userid,0);
	}
	
}

function report(id,t){                                                // Report form generator
    
	// Reset report form (Uncheck everything)
	$("#report-1").prop('checked',false);$("#report-2").prop('checked',false);$("#report-3").prop('checked',false);$("#report-4").prop('checked',false);                                   
	
	// Fade in report modal
	$('#report-modal').fadeIn();
	
	// Add post id | user id | comment id to on click event
	$('#confirm-report-submit').attr("onclick","submitReport("+id+","+t+")");
	
}

function submitReport(id,t){                                          // Report form submit
    
	// Get report comments marked by user (reporter)
	var v2 = valCheck("report-1") , v3 = valCheck("report-2") , v4 = valCheck("report-3") , v5 = valCheck("report-4") ;
    
	// Disable report submit button
	$('#confirm-report-submit').attr("onclick","");                                
	
	// Add loader animation in report modal
	reportLoaders(1);
	
	// Perform AJAX request to submit report
	var performed = ajaxProtocol(report_submit_file,0,0,t,id,v2,v3,v4,v5,0,0,0,0,"report-modal",0,0,0,10);
	
}

function valCheck(el){                                                // RETURN 1 if check box checked
    
	// Return true whether element is checked
	if($('input[id="'+el+'"]').is(':checked')){return 1;} else { return 0;}
	
}

function deleteContent(id,t){                                         // Delete form generator                                    
	
	// Fade in delete wizard || modal
	$('#confirm-delete').fadeIn();
	
	// Add post id | user id | comment id to on click event 
	$('#confirm-delete-submit').attr("onclick","deleteSubmit("+id+","+t+")");
	
}

function deleteChat(el,id,t){                                         // Delete form generator                                    
	
	// Fade in delete wizard || modal
	$('#confirm-delete').fadeIn();
	
	// Add post id | user id | comment id to on click event 
	$('#confirm-delete-submit').attr("onclick","exitChat("+el+","+id+","+t+");");
	
}

function editChatForm(id){                                            // Edit chat form generator                                    

	// Add post id to on click event 	
	$('#confirm-chat-edit-submit').attr("onclick","editChatFormSubmit("+id+")");
	
	$('#chat-edit-modal-content').html('<div class="brz-center"><img class="nav-item-text-inverse-big brz-padding-32" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img></div>');
	
	// Fade in edit chat wizard || modal	
	$('#chat-edit-modal').fadeIn();
	
	// Perform AJAX request	to get chat content editor
	var performed = ajaxProtocol(chat_description_file,0,0,0,id,0,0,0,0,0,0,0,0,0,0,0,0,14);
	
}

function editChatFormSubmit(id){                                      // Submit new chat edit
    
	// Get new text
	var v2 = $("#chat-edit-modal-name").val(),v3 = $("#chat-edit-modal-description").val();                                      
   
    // Disable edit submit button
    $('#confirm-edit-submit').attr("onclick","");                                
	
	// Add loader animation in edit modal
	editLoaders2(1);
	
	// Perform AJAX request to save chat edit
	var performed = ajaxProtocol(save_edit_file,0,0,2,id,v2,v3,0,0,0,0,0,1,id,0,0,0,14);
	
}

function editPost(id){                                                // Edit post form generator                                    

	// Fade in edit post wizard || modal	
	$('#edit-modal').fadeIn();
	
	// Add post id to on click event 	
	$('#confirm-edit-submit').attr("onclick","editSubmit("+id+")");
	
	$('#edit-modal-content').html('<div class="brz-center"><img class="nav-item-text-inverse-big brz-padding-32" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img></div>');
	
	// Perform AJAX request	to get post content editor
	var performed = ajaxProtocol(load_post_file,0,0,0,id,0,0,0,0,0,0,0,0,0,2,0,0,11);
	
}

function editSubmit(id){                                              // Submit new text
    
	// Get new text
	var v2 = $("#edit-modal-text").val();                                      
   
    // Disable edit submit button
    $('#confirm-edit-submit').attr("onclick","");                                
	
	// Add loader animation in edit modal
	editLoaders(1);
	
	// Perform AJAX request to save post edit
	var performed = ajaxProtocol(save_edit_file,0,0,1,id,v2,0,0,0,0,0,0,id,"edit-modal",0,0,0,10);
	
}

function deleteSubmit(id,t){                                          // Delete form submit
    
	// Dynamically disable delete submit button
	$('#confirm-delete-submit').attr("onclick","");                                
	
	// Loader animations in delete wizard || modal
	deleteLoaders(1);
	
	if(t == 1) {
		
		// Remove deleted post HTML existence
	    $("#post_view_"+id).fadeOut(500,function(){$("#post_view_"+id).remove();});
	    $("#RIGHT_RECENT_LIKES").fadeOut(500,function(){$("#RIGHT_RECENT_LIKES").remove();});
		
	} 
	if(t == 2) {
		
		// Remove deleted comment HTML existence
		$("#comment-id-"+id).remove();
		
	}
	if(t == 3) {
		
		// Remove deleted comment HTML existence
		$("#message-id-"+id).remove();
		
	}
	
	// Perform AJAX request to delete post || comment
	var performed = ajaxProtocol(delete_content_file,0,0,t,id,0,0,0,0,0,0,0,0,"confirm-delete",0,0,0,10);
	
}

function aOverlow() {                                                 // Add body overflow (makes body scrollable)
	
	// Setting empty means removing CSS property which in future prevents jerks
	$('html,body').css('overflowY','');	
	
}

function hOverlow() {                                                 // Hide body overflow(allow scrolling for POP UP MODALS)

	// Hide overflow remove scrolls on body and fix page
	$('html,body').css('overflowY','hidden');

}
 
function resetForm(x) {                                               // Reset request FORM
    
	// Reset form X data
	document.getElementById(x).reset();

}

function upStatus() {                                                // Update status

	// Unwrap all emojis
	if($("#update-status-emoji-target").val()=="#update-status-form-text-viewable") {
		var html = $("#update-status-form-text-viewable").html();
	}else{
		$("#update-status-form-file").val("");
		var html = $("#update-status-form-btext-viewable").html();
	}
	
	$("#update-status-form-text-viewable-2").html(html);
	$("#update-status-form-text-viewable-2 img").unwrap();

	// Add space after each line
	$('#update-status-form-text-viewable-2').children('br,div').each(function () {
        $(this).after(" "); 
	});

	$("#update-status-form-text-viewable-2 span,br").remove();

	// Get html data
	var get_text = $("#update-status-form-text-viewable-2").html().replace(/&nbsp;/g,'');

	// first create an element and add the string as its HTML
	var container = $('<div>').html(get_text);

	// then use .replaceWith(function()) to modify the HTML structure
	container.find('img').replaceWith(function() { return "{"+$(this).attr("data-emoji")+"}"; })

	// finally get the HTML back as string
	var strAlt = container.text();

	$("#update-status-form-text").val(strAlt);

	// Edit submit button
	$('#update-status-form-submit').removeClass('brz-blue brz-text-white brz-hover-blue-hd').addClass('brz-new_btn').attr('onclick','');
	
	// Add pre loaders to submit button
	$('#update-status-form-submit').html('<div id = "pre-loader-update-photo" style="margin-top:1px;"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" width="22" height="22"></img></div>');
	
	// Submit form
	document.getElementById("update-status-form-data").submit();

}

// Post status update
function statusUpdated(data) {                                          
	
	// Enable submit button
	$('#update-status-form-submit').removeClass('brz-new_btn').addClass('brz-blue brz-text-white brz-hover-blue-hd').attr('onclick','upStatus();');

	// Remove pre loaders
	$('#pre-loader-update-status').fadeOut();

	// Reset forms
	$('#update-status-form-text-viewable-2').html('');
	$('#update-status-form-text-viewable').html('');
	$('#update-status-form-btext-viewable').html('');

	// Add fetched data to body
	$('#all_posts').prepend(data);

	$('#post-file-1').removeClass('brz-file-active-green');
	
	return true; 
	
}

function commentsLoaders(t,id) {                                      // Loader animations for comments

	// Select loaders
	var el = ".n23sd23-losdf43ad3"+id,el2 = "#n23sd23-losdf43ad-3"+id ,lod = "comments-bar-"+id ,lod2 = "#comments-bar-"+id ;
	
	// Remove animations
	$(el2).html('');		
	
	// If requested start animations 
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
}

function chatLoaders(t) {                                             // Loader animations for chat window
	
	// Select loaders
	var el = ".add-message-loader-class",el2 = "#add-message-loader" ,lod = "messages-loader-bar" ,lod2 = "#messages-loader-bar" ;
	
	// Remove animations
	$(el2).html('');		
	
	// If requested start animations 
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
}

function modalLoaders(t) {                                            // Loader animations modal
	
	// Select loaders	
	var el = ".confirmLoaders-class",el2 = "#confirmLoaders-id" ,lod = "confirmLoaders-bar" ,lod2 = "#confirmLoaders-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
}

function deleteLoaders(t) {                                           // Loader animations delete
	
	// Select loaders	
	var el = ".confirmLoaders2-class",el2 = "#confirmLoaders2-id" ,lod = "confirmLoaders2-bar" ,lod2 = "#confirmLoaders2-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
}

function editLoaders(t) {                                             // Loader animations edit post
	
	// Select loaders	
	var el = ".confirmLoaders3-class",el2 = "#confirmLoaders3-id" ,lod = "confirmLoaders3-bar" ,lod2 = "#confirmLoaders3-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
	
}

function editLoaders2(t) {                                            // Loader animations edit post ads version
	
	// Select loaders	
	var el = ".confirmLoaders5-class",el2 = "#confirmLoaders5-id" ,lod = "confirmLoaders5-bar" ,lod2 = "#confirmLoaders5-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
	
}

function reportLoaders(t) {                                           // Loader animations reports
	
	// Select loaders	
	var el = ".confirmLoaders4-class",el2 = "#confirmLoaders4-id" ,lod = "confirmLoaders4-bar" ,lod2 = "#confirmLoaders4-bar";
	
	// Remove animations	
	$(el2).html('');		
	
	// If requested start animations 	
	if(t == 1) {
	    $(el2).html('<div id="'+lod+'" class="bar"></div>');
	    rotate(lod2,el);
	}
	
}

function btnLoader(t,tt) {                                            // Loader animations within submit button
	
	// Add loaders if requested
	
	if(t == 1) {
		
		$('#update-status-form-submit').removeClass('brz-blue brz-text-white brz-hover-blue-hd').addClass('brz-new_btn').css('pointer-events','none').html('<div id = "pre-loader-update-photo"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" width="15" height="15" ></img></div>');
	
	} else {
	    
		// Else remove loaders
		$('#update-status-form-submit').addClass('brz-blue brz-text-white brz-hover-blue-hd').removeClass('brz-new_btn').css('pointer-events','auto').html(tt);
		
	}
	
}

function rotate(load,el){                                             // Loader animations using bars
    
	// Animation set
	$(load).animate( { left: $(el).width() }, 1300, function() {

		// Update CSS left 
		$(load).css("left", -($(load).width()) + "px");
    
		// Again rotate 
		rotate(load,el);
    
	});
 }

function chatIcon() {                                                 // Update chat icon
	
	// Add form id 
	var v = $("#active-form").val();
	$("#chat-icon-form-id").val(v);
	
	// Submit photo form	
	document.getElementById("chat-icon-update-form").submit();

	// Disabled submit button	
	$('#chat-icon').attr('src',$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif').css('padding','30px');
	
}

function chatCover() {                                                // Update chat cover photo
	
	// Add form id 
	var v = $("#active-form").val();
	$("#chat-cover-form-id").val(v);
	
	// Submit photo form	
	document.getElementById("chat-cover-update-form").submit();

	// Disabled submit button
	$('#chat-cover').css('background-image','url('+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif)');
	$('#chat-cover').css('background-repeat','no-repeat');
	$('#chat-cover').css('background-position','center');
	$('#chat-cover').css('background-size','');	
	
}
  
function chatIconUpdated(data) {                                      // Post Updated chat icon
	
	// Remove pre loaders
	$('#chat-icon').attr('src',$('#installation_select').val()+'/index.php?'+data);
	
	// Display fetched message
	$('#chat-icon').css('padding','0px');
	$('#chat-cover').css('background-size','100% 100%');	
	
	// Reset submit form
	document.getElementById("chat-icon-update-form").reset();
	
	return true;
	
}

function chatCoverUpdated(data) {                                     // Post Updated chat cover
	
	// Display fetched message
	$('#chat-cover').css('background','url(' + $('#installation_select').val() + '/index.php?'+data);
	
	// Reset submit form
	document.getElementById("chat-cover-update-form").reset();
	
	return true;
	
}

function chatIconReturn(data,type) {                                  // Post error chat icon
	
	if(type == 0) {
		// Remove pre loaders
	    $('#chat-icon').attr('src',$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/error.png');
	}
	
	// Reset submit form
	document.getElementById("chat-icon-update-form").reset();
	
	// Display fetched message
	$('#chat-icon-update-error').html(data);
	
	return true;
	
}

function chatCoverReturn(data,type) {                                 // Post error chat cover
	
	if(type == 0) {
		$('#chat-cover').css('background-image','url('+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/error.png)');
		$('#chat-cover').css('background-repeat','no-repeat');
		$('#chat-cover').css('background-position','center');	
	}

	// Reset submit form
	document.getElementById("chat-cover-update-form").reset();
	
	// Display fetched message
	$('#chat-cover-update-error').html(data);
	
	return true;
	
}

function showModal(data) {                                            // Show error
	$("#success-modal").after(data);
}

function loadModal(t) {                                            // Show error
	if(t==1) {
		$("#new-modal").html('<div id="new-modal-content" class="brz-modal-content3 brz-white brz-card-8 brz-round" style="height:auto;"><div align="center" class="brz-padding-16" ><img style="margin-top:66px ;" src="'+$("#installation_select").val()+'/themes/'+$("#theme_select").val()+'/img/icons/cc_loader.gif"></div></div>');
		$("#new-modal").fadeIn(0);
	} else {
	    $("#new-modal").fadeOut(100);	
	}
}

function loadImageFull(el,img,id,t) {                                 // Load image dynamically
    
	// Add preloader for cover
	if(t == 1) {
	    $(el).removeAttr('src');
		var pp = '#btn-cover-chn';
	    $(el).parent().css("background","url('"+$('#installation_select').val()+"/themes/"+$('#theme_select').val()+"/img/icons/loader-small.gif') no-repeat center center"); 
	} else {
	    var pp = '#btn-photo-chn';
	}

	var downloadingImage = new Image();
    downloadingImage.onload = function(){
        $(el).parent().css("background","none");   
        $(el).attr("src", this.src);     		    
		if(id !== 0) {
			profileLoadTimeline(id);
		}	
		smartLoader(0,pp);
    };
    downloadingImage.src = img;

}

function smartLoader(t,el) {                                          // User profile photos loader
    
	var ell = $(el);
	
	if(t==1) {
	    
		//  Remove camera	
	    ell.find('i.fa').remove();
		
		// Pervent browser image caching
		ell.find('img').remove();
		
		// Remove hovering effect
	    ell.parent().parent().removeClass('brz-display-hover');
		
		// Add preloader
	    ell.prepend('<img class="nav-item-text-inverse-big" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img>')
	    
		// Disable button
		ell.css('pointer-events','none');
		
	} else {
     
	    // enable button
	    ell.css('pointer-events','auto');
   
     	// Add camera	
	    ell.prepend('<i aria-hidden="true" class="fa fa-camera"></i> ');
		
		// Add hovering effect
	    ell.parent().parent().addClass('brz-display-hover');
		
		// Remove preloader
	    ell.find('img').remove();	
	}
}

function csLoader(c_id,t,tt) {                                        // Comment loader
    
	// Add | remove preloader
	if(tt == 1) {
	    $("#comments-loader-"+t+"-"+c_id).html('<img class="nav-item-text-inverse-big" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img>');
	} else {
	    $("#comments-loader-"+t+"-"+c_id).html('');
	}
}

function loadImage(url,el) {                                          // Dynamic Image loader
    
    var downloadingImage = new Image();
    downloadingImage.onload = function(){
        $(el).parent().css("background","none");   
        $(el).attr("src", this.src);   
   };
   downloadingImage.src = url;
}

function sizeElements() {                                             // Refresh PLUGINS
  	
	$('.brz-dettachable').each(function() {
	    if($(this).hasClass('brz-detach-to-threequarter')) {
		    
			if($(window).width() < 600) {
			    $(this).detach().prependTo('#threequarter');
			} else {
			    if($(window).width() > 600) {
			        $(this).detach().prependTo('#right_content');
			    } 
			}    
		}
	});
}

function onScroll() {                                                 // Scroll functions

	if(/Mobi/.test(navigator.userAgent) == false) {
		$('.load-more-data').each(function() {
			if($(this).parent().hasClass("AUTO-LOAD") && $(this).isOnScreen()) {
				$(this).click();
				$(this).parent().prev().remove();
				$(this).parent().remove();
			}
		});

		// If window is large
		if($(window).width() > 1000 && $(window).height() > 500) {
		
		    // Cross browser scroll support
		    var vis_cross = (!$("#RIGHT_SCROLL_CRACK").prev().isOnScreen() && !$("#RIGHT_SCROLL_CRACK").is(":visible") && $("#RIGHT_SCROLL_CRACK").prev().length ) ? true : false;

			if($("#RIGHT_SCROLL_FINISH").isOnScreen() && $("#RIGHT_SCROLL_FINISH").is(":visible") && ( $("#RIGHT_SCROLL_CRACK").offset().top - $(window).scrollTop() < 60 || vis_cross)) {
	            $("#RIGHT_FIXED").width($("#RIGHT_LANGUAGE").width());
				$("#RIGHT_SCROLL_CRACK").nextAll('div.crackable').detach().appendTo("#RIGHT_FIXED");				
			} else {
		        $("#right_content").append($("#RIGHT_FIXED").html());
				$("#RIGHT_FIXED").html('');
			} 
		} else {
		    $("#right_content").append($("#RIGHT_FIXED").html());
			$("#RIGHT_FIXED").html('');
		} 
	}
}

function addCrack() {                                                 // Add fixing point to right body
    
	$("#RIGHT_SCROLL_CRACK").remove();
	
	if($("#RIGHT_LANGUAGE").prev('div.crackable').length) {
		
		// Prevent height because of the images
		var height_margin = 25;

		if($("#GROUPS_YOU_I").length) height_margin = height_margin  + 75;
	
	    if($("#RIGHT_LANGUAGE").prev("div.crackable").prev("div.crackable").length && $("#RIGHT_LANGUAGE").height() + $("#RIGHT_LANGUAGE").prev("div.crackable").height() + $("#RIGHT_LANGUAGE").prev("div.crackable").prev("div.crackable").height() + height_margin < $(window).height() - 100) {
		    $("#RIGHT_LANGUAGE").prev("div.crackable").prev("div.crackable").before('<div class="" id="RIGHT_SCROLL_CRACK"></div>');
		} else {
		    $("#RIGHT_LANGUAGE").prev("div.crackable").before('<div class="" id="RIGHT_SCROLL_CRACK"></div>');
		}
	    
	} else {
	    $("#RIGHT_LANGUAGE").before('<div class="" id="RIGHT_SCROLL_CRACK"></div>');
	} 
}

function addExpress() {                                               // Add express show
    if($(window).height() > 550 && $("#NO_EXPRESS").length == 0) {
	    $.ajax({
		    type: "POST",
		    url: $('#installation_select').val() + '/require/requests/load/express_content.php',
		    data: "p=EXPRESS_SHOW",
		    cache: false,
		    success: function(data) {
				$('#body-start').after(data);		
		    }
	    }); 
	}
}

function updateExpress() {                                            // Update express activity

	if($("#right_express").isOnScreen() && $("#right_express").is(":visible")) {
	
		$.ajax({
			type: "POST",
			url: $('#installation_select').val() + "/require/requests/content/active_express.php",
			cache: false,
			success: function(data) {
            	$("#EXPRESS_ACTIVITY").html(data);
			}
   	 	});
		
	}
}

function updateFriends() {                                            // Update active friends
	var installation = $('#installation_select').val();
	if($("#right_express").isOnScreen() && $("#right_express").is(":visible")) {
	
		$.ajax({
			type: "POST",
			url: installation + "/require/requests/content/active_friends.php",
			cache: false,
			success: function(data) {
            	$("#EXPRESS_FRIENDS").html(data);
			}
   	 	});
		
	}
}

function getContent(get,saved,addto) {                                 // Get content

    // Select requested content
    switch(get) {	
		// Get Emoji selecter
		case 'emoji-selecter':
			if(!$(addto).hasClass('addedContent')){
				var get_content = ajaxProtocol(live_cont_file,0,0,get,0,0,0,0,0,0,0,0,0,addto,0,0,0,13);
			}
			break;
	}
}

function addEmoji(element,emoji_name) {                                // Add emoji in contentable div
	
	var el = $(element).val();
	
	var html = $(el).html().replace(/\s+/g, " ");
	
	var add_emoji = "{"+emoji_name+"}";
	
	$(el).html(html+" "+add_emoji);
	
	$(el).html(function (i,text) {
        $.each(f,function (i,v) {	
		    var emoji_is = ""+re[i]+"";
		    text = text.replace(re[i],"<span><img class=\"brz-noselect\" data-emoji=\""+emoji_name+"\" src=\""+$('#installation_select').val()+"/themes/"+$('#theme_select').val()+"/img/emojis/"+ r[i] +".png\" width=\""+18+"\"></span> ");
        });
        return text;
    });
	
	$(element+' span:empty,br').remove();
	checkContentbb();
}

function refreshElements() {                                          // Refresh PLUGINS
    
	// Move elements to their right positions
	sizeElements();

	// Reload Time ago PLUGIN on all DIVs and SPANs
	jQuery("div.timeago").timeago();
    jQuery("span.timeago").timeago();
	
	// Cool picker plugin
	$("input[type=radio]").picker();

	// Reload nice select PLUGIN
	$('select').niceSelect();
	
	// Reload nice scroll
    if($(window).width() > 600) {$('.nicescrollit').niceScroll();}
	
	// Import strings 
	importLang();

	// Max visible chars for posts
	var max = 300;
	
	// Auto compress large text pharas
	$('.brz-c-text').each(function() {
	
		// Select unparsed hidden text
		var content = $(this).prev('.brz-hide').html();
        
		// If content exceeds
		if(content.length > max) {
			var c = content.substr(0, max);	
			var h = content.substr(max, content.length - max);
     		var html = c + '...&nbsp;&nbsp;&nbsp;<span class="brz-tiny-2 brz-underline-hover brz-text-triggers brz-cursor-pointer brz-hover-text-black brz-opacity brz-text-grey">' + lang[4] + '</span>';
			$(this).html(html);	
			$(this).prev('.brz-hide').html(html);
		 
        // Else insert parsed text		 
		} else {
		    var span = $(this).next('.brz-hide').html();
            $(this).prev('.brz-hide').html(span);			
            $(this).html(span);			
            $(this).removeClass('brz-c-text');		
		}
	});
	
	// Toggle large text to show/hide
	$('body').on('click', 'span.brz-text-triggers', function() {
		if($(this).hasClass('brz-viewed')) {
			var viewable = $(this).parent();	
		    viewable.html(viewable.prev('.brz-hide').html());	
		} else {   
			var view = $(this).parent();	
		    view.html(view.next('.brz-hide').html()+'&nbsp;&nbsp;&nbsp;<span class="brz-tiny-2 brz-viewed brz-cursor-pointer brz-underline-hover brz-text-triggers brz-hover-text-black brz-opacity brz-text-grey">' + lang[5] + '</span>');        
		}
		return false;
	});
	
	// Text trigger handler
	$('body').on('click', 'button.load-more-data', function() {
		$(this).remove();
	});
	
}

function t56zs3() {                                                   // Notifications #2
	
	// This functions are UGLIFIED because of too much user interactions
	
	// Installation URL
	var installation = $('#installation_select').val(),	
	
	// Last processed notification
	v = $('#7h4sd4').val(); 
	
	// Remove identifier
	$('#7h4sd4').remove();
	
	// Add loading animations to notifications widget
	notificationLoaders(0,1);	  
	rotate('#not-bar',".n23sd23-losdf43ad");	
    
	// Abort other request assigned to VAR content
	var content;
	
	if(content && content.readyState != 4) {
		content.abort();
	}
	
	// Assign a new request to VAR content
    content = $.ajax({
		
		// POST || GET
		type: "POST",
	    
		// Request host
		url: installation + "/require/requests/more/widget_notifications.php",
		
		// Whether build up content in memory
		cache: false, 
		
		// Add last processed notification ID
		data: "f=" + v, 
		
		// On request completion
		success: function(data) {
			
			// If no more notifications available 
			if(data == 0) {
				
				// Remove "load more" element
				$("#78vn4we87v").fadeOut(100);
				
				// Add "No more notifications element"
				$("#fdg54sdr").fadeIn();

			} else{
				
				// Append newly processed notifications
				$("#43if89").append(data);

				// Refresh PLUGINS
			    refreshElements();
				
			}
		
		    // Stop loading animations
		    notificationLoaders(0,0);			
			
		}
	});		
}	

function updateTopbar(x) {                                            // Update Header navigation
	
	// Close side nav if opened
	sidenav_close();
	
	s23u89dssh();
	
	if(x !== "brz-class-help"){$('#HELP_DESK').removeClass("brz-showing").fadeOut(0);}
	
	if(x !== "brz-class-me"){$('#MY_DESK').removeClass('brz-showing').fadeOut(0);$(".brz-class-me").removeClass('fa-rotate-180');}
	if(x == "brz-class-me"){$(".brz-class-me").addClass('fa-rotate-180');}
	
	// remove all active classes
	$(".brz-main-actiable").each(function() {
	    $(this).removeClass('brz-text-white').addClass('brz-text-shad').parent().addClass("brz-no-image");		
	});
	
	// Add active class to requested element
	$("."+x).addClass('brz-text-white').removeClass('brz-text-shad').parent().removeClass("brz-no-image");

}

function s23u89dssh() {                                               // Notifications #3

	// This functions are UGLIFIED because of too much user interactions
	
	// Notifications container
	x = $("#367u8sdfv55ysdfg");
	
	// if Container is visible
	if(x.hasClass('brz-fade332asdugyu')){ 
	 
	    // Add overflow
		aOverlow();
		
	    // Remove this identifier
		x.removeClass("brz-fade332asdugyu");
	    
		// Remove all animations belong to this container
		notificationLoaders(0,0);
        
		// Fade out notifications container
		$("#fdg54sdr").fadeOut();
		x.fadeOut();
		
    }		
}

function sNX(f) {                                                     // Show notifications in page

	// Add animation to left body
	bodyLoader('threequarter');
	
	// Perform AJAX request to show requested notifications type
	var performed = ajaxProtocol(load_notifications_file,0,0,0,0,0,0,0,0,0,0,0,0,0,f,0,1,25);
	
}

function s23u89dsh(){                                                 // Notifications #4
 
	// This functions are UGLIFIED because of too much user interactions 
	// This function toggles notifications container (Executed from top navigation)

	if($(window).width() < 601) {
		
		// Load notifications on full page
		sidenav_close();loadNotifications();
		
	} else {
		
		// Installation URL
		var installation = $('#installation_select').val();
	
		// Select container
		x = $("#367u8sdfv55ysdfg");
	
		// If visible 
		if(x.hasClass('brz-fade332asdugyu')){ 
	    
			// remove above identifier
			x.removeClass("brz-fade332asdugyu");
	    
			// Remove animations too
			notificationLoaders(0,0);
		
			// fade out notifications widget
        	$("#fdg54sdr").fadeOut();
        	x.fadeOut();
		
			// Update top navigation activate->Notifications
	    	updateTopbar('NULL');	
		
		// If container is not visible
    	} else {
	
			// Update top navigation activate->Notifications
	    	updateTopbar('brz-class-notifications');
	
			// Add identifiers and fade in notifications widget
	    	x.addClass("brz-fade332asdugyu");
	    	x.addClass("brz-dfertwesdfsdfdf");
			x.fadeIn();
		
			// Add loader animations in notifications widget
			notificationLoaders(1,0);
        
			// Abort other request assigned to VAR content
			var content;
	   		if(content && content.readyState != 4) {
		    	content.abort();
	   		}
		
			// Assign a new request to VAR content	
        	content = $.ajax({
			
				// POST || GET methods
		    	type: "POST",
		    
				// Request host
				url: installation + "/require/requests/content/check_notifications.php",
		    
				// Save memory
				cache: false,

            	// On request return || completion			
		    	success: function(data) {
				
					// If no more 
					if(data == 0) {
					
						// Fade out load more notifications
						$("#78vn4we87v").fadeOut();
					
					} else{
					
						// Add fetched data
						$("#43if89").html(data);
					
						// Fade in load more notifications
						$("#78vn4we87v").fadeIn();
					
						// Refresh PLUGINS
						refreshElements();
					
					} 
				
					// Remove loader animations 
	            	notificationLoaders(0,0);
				
				}
			});		
		}
    }		
}

function submitSearch(e) {                                            // Search value
	
	// Prevent default behaviour
	e.preventDefault();
	
	// Trigger search on submit for mobile users
	if(/Mobi/.test(navigator.userAgent) == true) {
		detachResults();
	
		// Perform search
	    search(0,1,0)
	};

	// Prevent default behaviour	
	return false;

}

function manualLoaders(el,t) {                                        // Add manual loaders to elements 
	
	// Select and empty requested element
	$("#"+el).empty();
	
	// Add animations to requested element if requested
	if(t == 1) {
		$("#"+el).html('<div id="pre-loader-main" name="pre-loader-main"class="brz-animate-top"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" style="margin-left:40%;margin-top:20%;" width="50" height="50"></img><div>');
	}
	
}

function updateProfileTab(el) {                                       // Update profile navigation
    
	// Remove active classes
	$(".brz-hvr-active").removeClass('brz-hvr-active-1');
	
	// Add active class to requested element
	$("#profile_view_tab_"+el).parent().addClass('brz-hvr-active-1');
	
}

function updateMainTab(el) {                                          // Update profile navigation
	
	// Fade out mobile version of navigation if opened
	$("#sd3a4srtsfadgfssdf334erfgs").removeClass('brz-show');
	
	// Remove active classes	
	$(".brz-element-main-tab").removeClass('brz-active-small');
	
	// Add active class to requested element	
	$("#ki43"+el).addClass('brz-active-small');
	
}

function updateNavigation(el) {                                       // Update Side navigation
	
	// Remove active classes	
	$(".brz-add-listner1").removeClass('brz-hover-n_active');
	
	
	// Check whether animations are requested
	if(el !== 0) {
		
	    // Add active class if requested to a element
		$("#brz-add-listner1-"+el).addClass('brz-hover-n_active');
		
	}
	
}

function bodyLoader(el) {                                             // Loader animations for body
	
	// Remove body loaders
	$("#"+el).html('');
	
	// If requested add loader animations to body
	$("#"+el).html('<div id="pre-loader-main" name="pre-loader-main" class="brz-animate-opacity"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" style="margin-left:40%;margin-top:20%;" width="50" height="50"></img><div>');

}

function lastPostLoads(t) {                                           // Loader animations for last post 
	
	// Remove loader animations
	$("#last_post_preload").html('');
	
	// Check whether animations are requested
	if(t == 1) {
		
		// Disabled animations origin
		$("#pre-loader-starter").addClass('brz-disabled');
		
		// Add animations
		$("#last_post_preload").html('<div class="bar" id="more-load-bar"></div>');
		
		// Start animations
		rotate('#more-load-bar',"#last_post_preload");
		
	}
	
	// Else remove animations
	if(t == 0) {
		
		// Remove Animations 
		$("#load-more-data").remove();
		
		// Remove origin 
		$("#last_post_preload").remove();
		
	}
	
}

function lastPostLoaders(t) {                                         // Loader animations for last post version 2.0
	
	// Animations requested
	if(t == 1) {
		
		// Remove if animations exists
		$("#last_post_preload").empty();
		
		// Add animations
		$("#last_post_preload").after('<div name="temp_pre_loader_load_more_feed" id="temp_pre_loader_load_more_feed" class="brz-animate-top"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" style="margin-left:40%;margin-top:20%;" width="50" height="50"></img></div>');
	    
		// Remove origin
		$("#last_post_preload").remove();
		
	} else {
		
		// Remove animations if not requested
		$("#temp_pre_loader_load_more_feed").remove();
		
	}
	
}

function notificationLoaders(n1,n2) {                                 // Loader animations for notifications widget
	
	// Remove animations in top bar of notifications container
	$("#n23sd23-losdf43ad-1").html('');	
	
	// Remove animations in bottom bar of notifications container	
	$("#n23sd23-losdf43ad-2").html('');		
	
	// If animations requested
	if(n1 == 1) {
	    
		// Add animations in top bar of notifications container
		$("#n23sd23-losdf43ad-1").html('<div id="not-bar" class="bar"></div>');	
	    
		// Start animations
		rotate('#not-bar',".n23sd23-losdf43ad");
	
	}	
	
	
	if(n2 == 1) {
	    
		// Add animations in bottom bar of notifications container		
		$("#n23sd23-losdf43ad-2").html('<div id="not-bar" class="bar"></div>');
	    
		// Start animations
		rotate('#not-bar',".n23sd23-losdf43ad");
	
	}
	
}

function removeEl(id) {                                               // Fade out element
	$("#"+id).remove();
}

function locate(to) {                                                 // Redirect	
	window.location.href = to ;
}

function resetElement(id,text) {                                      // Update html data of element
	$(id).html(text); 
}

function updateSRC(id,src) {                                          // Update IMG SRC
	$(id).attr('src',src); 
}
 
function ajaxProtocol(fl,p,f,t,v1,v2,v3,v4,v5,v6,v7,v8,v9,v10,ff,i,req,body) {      // Breeze Ultimate JQuery AJAX engine Executer(Imported)
	var gen = $('#installation_select').val() + fl;

	// This abort all pending requests
	if(req == 1) {
		
		// Generate token
		var token = generateToken(1);
		
		var xhr;
		if(xhr && xhr.readyState != 4) {
			xhr.abort();
		}
		xhr = $.ajax({
		    type: "POST",
		    url: gen,
		    data: { p: p, f: f, t: t, ff: ff, i: i, v1: v1, v2: v2, v3: v3, v4: v4, v5: v5, v6: v6, v7: v7, v8: v8, v9: v9, v10: v10, bo: body},
			cache: false,
		    success: function(data) {
				
				// Match token
				var valid_token = matchToken(1,token);
			
			    // Confirm token
				if(valid_token) {
					
					// Handover data to organise in body
                	return handover(v9,v10,data,body);
					
				}
				
		    }
	    });

	// This type of requests will keep processing till finish
	} else {
		$.ajax({
		    type: "POST",
		    url: gen,
		    data: { p: p, f: f, t: t, ff: ff, i: i, v1: v1, v2: v2, v3: v3, v4: v4, v5: v5, v6: v6, v7: v7, v8: v8, v9: v9, v10: v10, bo: body},
			cache: false,
		    success: function(data) {
				
				// Handover data to organise in body
                return handover(v9,v10,data,body);  
				
		    }
	    });			
	}
}

function handover(v9,v10,data,body) {	                                            // Breeze Ultimate JQuery AJAX engine DATA handler for Social network
	if(body == 1) {       // Full body replace component
		$("#content-body").empty();
		$("#content-body").html(data);
	    refreshElements();
		return 1;
	} 
	if(body == 2) {		  // Mid body Internal replacing component
		$("#threequarter").empty();
		$("#threequarter").html(data);
		refreshElements();
		return 1;
	}
	if(body == 3) {       // Remove notification loader
		notificationLoaders(0,0);	
		return 1;
	}
	if(body == 4) {       // Append fetched data to previous ROWED content
		lastPostLoaders(0);
		$("#all_posts").append(data);
	    refreshElements();
	}
	if(body == 5) {       // Replace mid body with fetched data
		$("#threequarter").replaceWith(data);
	    refreshElements();
	}
	if(body == 6) {       // Posts appending data component
		lastPostLoads(0);
		$("#all_posts").append(data);
	    refreshElements();
	}
	if(body == 7) {       // Remove button loader
		// Import strings
	    importLang();
		btnLoader(0,lang[7]);	
		$("#errors-eb").html(data);
	}
	if(body == 8) {       // Clean posts appending data component
        lastPostLoaders(0);
		$("#all_posts").empty();
		$("#all_posts").append(data);
	    refreshElements();
	}
	if(body == 9) {       // Comments updating component
		
		if(v10 == 2) {
		    csLoader(v9,1,0);
		    $("#comments-"+v9).prepend(data);
		} else {
		    if(v10 == 1) {
			    csLoader(v9,2,0);
				$("#comments-"+v9).append(data);
				$("#form-add-comment-"+v9).css('pointer-events','auto');
			} else {
			    csLoader(v9,1,0);
			    $("#comments-"+v9).html(data);
			}	    
		}

        refreshElements();		
	}
	if(body == 10) {      // Universal pop up managing component
		editLoaders(0);
        $("#"+v10).fadeOut();
		$("#success-modal-content").html(data);
		$("#success-modal").fadeIn();
		$("#post_view_"+v9).html('<div class="brz-center brz-padding-32"><img class="nav-item-text-inverse-big brz-padding-32" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img></div>');
	    var performed = ajaxProtocol(load_post_file,0,0,1,v9,0,0,0,0,0,0,0,0,v9,3,0,0,31);
	}
	if(body == 11) {      // Edit post content fetcher
		$("#edit-modal-content").html(data);
	}
	if(body == 12) {      // Chats messages fetcher
		lastPostLoads(0);
		$("#messages-container-"+v10).prepend(data);
	}if(body == 13) {     // Chat search management
		$(v10).html(data);
	}
	if(body == 14) {      // Edit chat content fetcher
		editLoaders2(0);
		if(v9 == 1) {		    
			$("#chat-edit-modal-msg").html(data);
			$("#confirm-edit-submit").attr("onclick","editChatFormSubmit("+v10+")");
		} else { 
			$("#chat-edit-modal-content").html(data);
		}
	}if(body == 15) {      // Edit chat content fetcher
		$("#quick-message").html(data);
	}if(body == 20) {      // Gallery box on profile
		$("#gallery-box-main").find('div#last_post_preload').remove();
		$("#gallery-box-main").find('div#load-more-data').remove();	
		$("#gallery-box-main").append(data);
	}
	if(body == 21) {      // Gallery box on profile
		$("#gallery-box-main").html(data);
	}
	if(body == 23) {      // Gallery box on profile
		$("#people-box-main").find('div#last_post_preload').remove();
		$("#people-box-main").find('div#load-more-data').remove();	
		$("#people-box-main").append(data);
	}
	if(body == 24) {      // Gallery box on profile
		$("#chats-box-main").find('div#last_post_preload').remove();
		$("#chats-box-main").find('div#load-more-data').remove();	
		$("#chats-box-main").append(data);
	}
	if(body == 25) {      // Gallery box on profile
		$("#threequarter").replaceWith(data);
	}
	if(body == 26) {      // Gallery box on profile
		$("#trending-box-main").find('div#last_post_preload').remove();
		$("#trending-box-main").find('div#load-more-data').remove();	
		$("#trending-box-main").append(data);
	}
	if(body == 27) {      // Search
		$("#search-results-main-box").find('div#last_post_preload').remove();
		$("#search-results-main-box").find('div#load-more-data').remove();	
		$("#search-results-main-box").append(data);
	}
	if(body == 28) {      // Settings content
		var el = $("#settings-tab-"+v10);  
		el.fadeOut(0);
		el.after(data);
	}
	if(body == 29) {      // Settings tab
		var el = $("#settings-tab-"+v10);
		$(".settings-content-class").fadeOut(0);
		el.replaceWith(data);
		$('html, body').animate({scrollTop: 0},"slow");
	}
    if(body == 30) {      // Settings saved
		$("#settings-content-mess-"+v10).html(data);
	}
	if(body == 31) {
	    $("#post_view_"+v10).replaceWith(data);
	}
	if(body == 32) {
	
	    if($(window).width() > 600) {
	    	$("#new-modal-content").empty().animate({
            	width: '+=200px',height: '+=300px'
        	}, 500,function() {
			    $("#new-modal-content").html('<div id="new-modal-inner"></div>');
			    $("#new-modal-inner").fadeOut(0).html(data);
			    $("#new-modal-inner").fadeIn();
			});
		} else {
	    	$("#new-modal-content").empty().animate({
            	height: '+=200px'
        	}, 500,function() {
			    $("#new-modal-content").html('<div id="new-modal-inner"></div>');
			    $("#new-modal-inner").fadeOut(0).html(data);
			    $("#new-modal-inner").fadeIn();
			});
		}
		
	}
	if(body == 66) {      // Settings saved
		$(v10).find('div.preloader').remove();
		if(v9 == 0){
		    $(v10).find('div.brz-new-container-2').html(data);
		} else {
		    if(v9 == 1){
		        $(v10).find('div.brz-new-container-2').append(data);
		    } else {
			    $(v10).find('div.brz-new-container-2').prepend(data);
			}
	    }
	}
	if(body == 0) {       // RETURN fetched data
		refreshElements();
		return data;
	}
	if(body == 97) {      // reload on return
       location.reload();
	}
	if(body == 98) {      // Attach response to bottom
		$("#execute_responses").append(data);
	}	
	if(body == 99) {      // ALERT fetched data without processing
       alert(data);
	} 
	refreshElements();
}

function userDescription(id) {                                        // Toggle accordion data
    
	// Select accordion
	var x = $("#"+id);
    
	// If accordion data is already visible
	if(x.hasClass('brz-fade332asdugyu')){ 
	    
		// remove above identifier
		x.removeClass("brz-fade332asdugyu");
        
		// fade out accordion data
		x.slideUp();
		
		x.prev().removeClass("brz-white-3").find("i.rotateable").removeClass("brz-rotated");
    
	} else {	 
	    
		// Add fade in identifier
		x.addClass("brz-fade332asdugyu");
		
		x.prev().addClass("brz-white-3").find("i.rotateable").addClass("brz-rotated");
		
		// Fade in accordion data
		x.slideDown();
		
	}
	
}

function openTab(t) {                                                 // Open settings tab
  
	// Select tab
	var el = $("#settings-tab-"+t);

	closeAll();
	
	// Add tab loader
	tabLoader(1,t);
	
	// Load tab
	var performed = ajaxProtocol(load_tab_content_file,0,0,0,0,t,0,0,0,0,0,0,0,t,0,0,1,28);
	
}

function saveTab(t) {                                                 // Save settings tab
    
	v1=v2=v3=v4=v5=v6=v7=v8=v9=0;
	
	// General settings | information requries only three params
	var v1 = $("#settings-input-1").val(),
	    v2 = $("#settings-input-2").val(),
	    v3 = $("#settings-input-3").val();
		
	// Privacy settins requires 9-10 params
	if(t > 14) {
		var v4 = $("#settings-input-4").val(),
	    	v5 = $("#settings-input-5").val(),
	    	v6 = $("#settings-input-6").val(),
	    	v7 = $("#settings-input-7").val(),
	    	v8 = $("#settings-input-8").val(),
	    	v9 = $("#settings-input-9").val();
	}

    // Add wizard loader
	contentLoader(1,t);
	
	// Load tab
	var performed = ajaxProtocol(save_tabs_file,0,0,t,v1,v2,v3,v4,v5,v6,v7,v8,v9,t,0,0,1,30);
	
}

function closeTab(t,s) {                                              // Close settings tabs
    
	// Select tab
	var el = $("#settings-tab-"+t);

	// Load tab
	var performed = ajaxProtocol(load_tabs_file,0,0,0,s,t,0,0,0,0,0,0,0,t,0,0,1,29);
	
}

function tabLoader(tt,t) {                                            // Tab loader
    
	var el = $("#settings-tab-"+t);
	if(tt == 1) {
       el.css('pointer-events','none');
       $("#settings-tab-space-"+t).prepend('<img class="settings-tab-loader nav-text-inverse-big" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" width="15" height="15"></img>');
    } 
}

function contentLoader(tt,t) {                                        // Settings loader
   	if(tt == 1) {
        $("#settings-content-close-"+t).css('pointer-events','none');
        $("#settings-content-save-"+t).css('pointer-events','none');
		$("#settings-content-space-"+t).html('<img id="settings-content-loader" class="settings-content-loader nav-text-inverse-big" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif" width="15" height="15"></img>');
    } else {
	    $("#settings-content-close-"+t).css('pointer-events','auto');
        $("#settings-content-save-"+t).css('pointer-events','auto');
		$("#settings-content-space-"+t).find('img.settings-content-loader').remove();
	}
}

function setSettings(type) {                                          // Update user settings navigation
	$(".settings-nav-element").removeClass('brz-active-not-set');
	$("#settings-nav-"+type).removeClass('brz-hover-xxlight-grey').addClass('brz-active-not-set');
}

function toggleIt(x,y) {                                              // Togle topbar
	// fade out notifications widget
    if($(x).hasClass("brz-showing")){ 
        $(x).removeClass("brz-showing").fadeOut(300);
		updateTopbar('NULL');    
	} else {
	    $(x).addClass("brz-showing").fadeIn(300);
		updateTopbar(y);
	}
	 
}

function addKeys(type,t) {                                              // Attach dynamic keywords to search results
    
	// Get custom keywords
	var v = $.trim($('#add-'+type+'-keys').val());
	
	// Random number
	var id = "",
    character_set = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	// Generate captcha
    for( var i=0; i < 5; i++ )
        id += character_set.charAt(Math.floor(Math.random() * character_set.length));
	
	// Check random id
	if($('#filter-people-'+type+'-'+id).length) {var id = 1 + Math.floor(Math.random() * 6 + Math.random() * 2223);}

	// Uncheck previous
	$("input[name='filter-people-"+type+"']:checked").next().removeClass('checked');
	
	// Remove attribute too
	$("input[name='filter-people-"+type+"']:checked").prop('checked', false);

	// Add new radio with custom filter
	$('<div class="brz-hover-new_clr" onclick="search(0,25,'+t+');scrollToTop();"><input type="radio" class="add-'+type+'-old" id="filter-people-'+type+'-'+id+'" name="filter-people-'+type+'" value="'+v+'" checked/><label class="brz-small" for="filter-people-'+type+'-'+id+'">'+v+'</label></div>').insertBefore("#add-"+type+"-keys-root");

	// Reload radios plugin 
	$("input[type=radio]").picker();
	
	// Empty wizard
	$("#add-"+type+"-keys").val('');
	
	// Remove wizard
	$("#add-"+type+"-keys").next().fadeOut(200);
	
	// Search with new filters
	search(0,25,t);scrollToTop();

}

function getKeys(type) {                                              // Get keywords
    var v = $.trim($("#add-"+type+"-keys").val());
	
	if(v.length > 0) {
	    $("#add-"+type+"-keys").next().fadeIn();
	} else {
	    $("#add-"+type+"-keys").next().fadeOut(200);
	}
}

function closeAll() {                                                 // Close settings tabs
    
	// Remove all previous opened tabs
	$(".settings-content-class").each(function() { 
	    $(this).prev().fadeIn(0).css('pointer-events','auto').find("img.settings-tab-loader").remove();
		$(this).remove();	
    });
	
}

function returnFalse() {}                                             // Disable href for IE9