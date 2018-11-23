function uUSAA(p) {                                                   // Update user profile for administration
	  
	// Installation URL
	var gen = $('#installation_select').val() + update_profile_admin_file;
	
	// Set values
	var v2 = $('#uUSAA-v2').val(), v3 = $('#uUSAA-v3').val(),
	v4 = $('#uUSAA-v4').val(),v5 = $('#uUSAA-v5').val(),v6 = $('#uUSAA-v6').val(),
	v7 = $('#uUSAA-v7').val(),v8 = $('#uUSAA-vv1').val(),v9 = $('#uUSAA-vv2').val(),
	v10 = $('#uUSAA-vv3').val(),v11 = $('#uUSAA-vv4').val(),v12 = $('#uUSAA-vv5').val(),
	v13 = $('#uUSAA-vv6').val(),v14 = $('#uUSAA-ss1').val(),v15 = $('#uUSAA-ss2').val()
	,v16 = $('#uUSAA-ss3').val();
    
	// Add loader animations to button
	btnLoader(1,''); 
    
		// Perform AJAX
	$.ajax({
		type: "POST",
	    url: gen,
		data: "v1=" + p + "&v2=" + v2 + "&v3=" + v3 + "&v4=" + v4 + "&v5=" + v5 + "&v6=" + v6 + "&v7=" + v7 + "&v8=" + v8 + "&v9=" + v9 + "&v10=" + v10 + "&v11=" + v11 + "&v12=" + v12 + "&v13=" + v13 + "&v14=" + v14 + "&v15=" + v15 + "&v16=" + v16,
		cache: false,
		success: function(data) {
			// Handover data to Fasebook engine for further data management
            return handover(v9,v10,data,7);   
		}
	});		
}

function uUSA() {                                                     // Update user settings for administration
	
	// Installation value
	var gen = $('#installation_select').val() + update_users_settings_file;
	
	// Set values
	var v1 = $('#usa-v1').val(),v2 = $('#usa-v2').val(), v3 = $('#usa-v3').val(),
	v4 = $('#usa-v4').val(),v5 = $('#usa-v5').val(),v6 = $('#usa-v6').val(),
	v7 = $('#usa-v7').val(),v8 = $('#usa-v8').val(),v9 = $('#usa-v9').val(),
	v10 = $('#usa-v10').val(),v11 = $('#usa-v11').val(),v12 = $('#usa-v12').val(),
	v13 = $('#usa-v13').val(),v14 = $('#usa-v14').val(),v15 = $('#usa-v15').val(),
	v16 = $('#usa-v16').val(),v17 = $('#usa-v17').val(),v18 = $('#usa-v18').val(),
	v19 = $('#usa-v19').val(),v20 = $('#usa-v20').val(),v21 = $('#usa-v21').val(),
	v22 = $('#usa-v22').val(),v23 = $('#usa-v23').val(),v24 = $('#usa-v24').val(),
	v25 = $('#usa-v25').val(),v26 = $('#usa-v26').val(),v27 = $('#usa-v27').val(),
	v28 = $('#usa-v28').val(),v29 = $('#usa-v29').val(),v30 = $('#usa-v30').val(),
	v31 = $('#usa-v31').val(),v32 = $('#usa-v32').val(),v33 = $('#usa-v33').val();
    
	// Add loader animation to submit button
	btnLoader(1,'');
	
	// Perform AJAX
	$.ajax({
		type: "POST",
	    url: gen,
		data: "v1=" + v1 + "&v2=" + v2 + "&v3=" + v3 + "&v4=" + v4 + "&v5=" + v5 + "&v6=" + v6 + "&v7=" + v7 + "&v8=" + v8 + "&v9=" + v9 + "&v10=" + v10 + "&v11=" + v11 + "&v12=" + v12 + "&v13=" + v13 + "&v14=" + v14 + "&v15=" + v15 + "&v16=" + v16 + "&v17=" + v17 + "&v18=" + v18 + "&v19=" + v19 + "&v20=" + v20 + "&v21=" + v21 + "&v22=" + v22 + "&v23=" + v23 + "&v24=" + v24 + "&v25=" + v25 + "&v26=" + v26 + "&v27=" + v27 + "&v28=" + v28 + "&v29=" + v29 + "&v30=" + v30 + "&v31=" + v31 + "&v32=" + v32 + "&v33=" + v33,
		cache: false,
		success: function(data) {
			// Handover data to Fasebook engine for further data management
            return handover(v9,v10,data,7);   
		}
	});
	
	//var performed = ajaxProtocol(settings_file,0,0,3,v1,v2,v3,v4,v5,v6,v7,v8,0,0,0,0,0,7);
}

function uWS() {                                                      // Update website settings
	
	// Installation value
	var gen = $('#installation_select').val() + update_web_settings_file;
	
	// Set values
	var v1 = $('#web_name').val(),v2 = $('#web_title').val(), 
	v3 = $('#font_colours').val(),v4 = $('#p_per_page').val(),v5 = $('#p_per_page_2').val(),
	v6 = $('#f_per_page').val(),v7 = $('#l_per_page').val(),v8 = $('#c_per_page').val(),
	v9 = $('#s_r_per_page').val(),v10 = $('#m_c_length').val(),v11 = $('#i_u_quality').val(),
	v12 = $('#m_p_i_length').val(),v13 = $('#m_p_i_length_2').val(),v14 = $('#m_c_i_length').val(),v15 = $('#p_c_length2').val(),
	v16 = $('#mens_type').val(),v17 = $('#inf_scrolling').val(),v18 = $('#ch_per_page').val(),v19 = $('#m_c_m_length').val(),
	v20 = $('#m_ccc_i_length').val(),v21 = $('#m_ccc_c_length').val(),v22 = $('#i_u_size').val();
    
    // Add loader animation to submit button
	btnLoader(1,'');
	
	// Perform AJAX
	$.ajax({
		type: "POST",
	    url: gen,
		data: "v1=" + v1 + "&v2=" + v2 + "&v3=" + v3 + "&v4=" + v4 + "&v5=" + v5 + "&v6=" + v6 + "&v7=" + v7 + "&v8=" + v8 + "&v9=" + v9 + "&v10=" + v10 + "&v11=" + v11 + "&v12=" + v12 + "&v13=" + v13 + "&v14=" + v14 + "&v15=" + v15 + "&v16=" + v16 + "&v17=" + v17 + "&v18=" + v18 + "&v19=" + v19 + "&v20=" + v20 + "&v21=" + v21 + "&v22=" + v22,
		cache: false,
		success: function(data) {
			// Handover data to Fasebook engine for further data management
            return handover(v9,v10,data,7);   
		}
	});		
}

function saveAdds() {                                                 // Update adds settings
	
	// Installation value
	var gen = $('#installation_select').val() + update_adds_settings_file;
	
	// Set values
	var v1 = $('#sponsor1').val(),v2 = $('#sponsor2').val(), 
	v3 = $('#sponsor3').val(),v4 = $('#sponsor4').val(),v5 = $('#sponsor5').val(),
	v6 = $('#sponsor6').val(),v7 = $('#sponsor7').val(),v8 = $('#sponsor8').val(),
	v9 = $('#sponsor9').val(),v10 = $('#sponsor10').val(),v11 = $('#sponsor11').val(),
	v12 = $('#sponsor12').val();
    
    // Add loader animation to submit button
	btnLoader(1,'');
	
	// Perform AJAX
	$.ajax({
		type: "POST",
	    url: gen,
		data: "v1=" + v1 + "&v2=" + v2 + "&v3=" + v3 + "&v4=" + v4 + "&v5=" + v5 + "&v6=" + v6 + "&v7=" + v7 + "&v8=" + v8 + "&v9=" + v9 + "&v10=" + v10 + "&v11=" + v11 + "&v12=" + v12 ,
		cache: false,
		success: function(data) {
			// Handover data to Fasebook engine for further data management
            return handover(v9,v10,data,7);   
		}
	});		
}

function uAdmin() {                                                   // Update administration settings

	// Set values
	var v1 = $('#uS6-old').val(), v2 = $('#uS6-new').val(), v3 = $('#uS6-re').val(); 

	// Add loader animations to submit button
	btnLoader(1,'');	
	
	// Perform AJAX request to update administration password (Edit Administration)
	var performed = ajaxProtocol(admin_settings_file,0,0,6,v1,v2,v3,0,0,0,0,0,0,0,0,0,0,7);
	
}

function searchAdmin(f,ff,b) {                                        // load search results (admin)
	
	// Search input box value
	var v = $('#w2rsdf').val();
	
	if(b == 6) {

	    // Add animation after last post HTML content
	    lastPostLoads(1);
		
	} else {
		
		// Add history 
		if(!isIE()) store('/manage/search/'+v);		
		
		// Close Notifications widget if opened
	    s23u89dssh();
		
		// Update side navigation
	    updateTopbar('brz-class-trending');

	    // Add animation to full body	
	    bodyLoader('content-body');
		
	}
	
	// Perform AJAX request to get search for administration
	var performed = ajaxProtocol(load_searchadmin_file,0,f,0,v,ff,0,0,0,0,0,0,0,0,0,0,1,b);
	
}

function manageUsers(f,v1,b) {                                        // Manage users

	// Add history 
	store('/manage/users');
	
	if(b == 6) {

	    // Add animation after last post HTML content
	    lastPostLoads(1);
		
	} else {
		
		// Update top navigation activate->manage users
	    updateTopbar('brz-class-users');
		
		// Update side navigation
		updateNavAdmin('users');

	    // Add animation to full body	
	    bodyLoader('content-body');
		
	}
	
	// Perform AJAX request to get users
	var performed = ajaxProtocol(load_admin_content_file,0,f,0,v1,0,0,0,0,0,0,0,0,0,7,0,1,b);
	
}

function loadReports(f,v1,b) {                                        // Load reports

	// Add history 
	store('/manage/reports');
	
	if(b == 6) {

	    // Add animation after last post HTML content
	    lastPostLoads(1);
		
	} else {
		
		// Update top navigation activate->reports
	    updateTopbar('brz-class-reports');
		
		// Update side navigation 
		updateNavAdmin('rep');

	    // Add animation to full body	
	    bodyLoader('content-body');
		
	}
	
	// Perform AJAX request to get reports
	var performed = ajaxProtocol(load_admin_content_file,0,f,0,v1,0,0,0,0,0,0,0,0,0,3,0,1,b);
	
}

function loadWebSettings() {                                          // Load web settings for Administration
  
	// Add history 
	store('/manage/websettings');

	// Update top navigation activate->web settings
	updateTopbar('brz-class-websettings');

	// Update side navigation
	updateNavAdmin('2');

	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load web settings
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,6,0,1,1);
	
}

function manageAdds() {                                               // Load adds manager for Administration
  
	// Add history 
	store('/manage/adds');

	// Update top navigation activate->none
	updateTopbar('brz-class-removeall');
	
	// Update side navigation
	updateNavAdmin('6');

	// Add animation to full body	
	bodyLoader('content-body');

	// Perform AJAX request to load sponsors
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,1,1);

}

function loadNewregsettings() {                                       // Load new registrations settings for Administration

	// Add history 
	store('/manage/usersettings');    

	// Update top navigation activate->web settings
	updateTopbar('brz-class-websettings');
	
	// Update side navigation
	updateNavAdmin('3');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,2,0,1,1);
}

function executeReport(id,t) {                                        // Delete or mark safe reported content
	
	// Perform AJAX request to mark action on selected report
	var performed = ajaxProtocol(execute_report_file,0,0,0,id,t,0,0,0,0,0,0,0,0,0,0,0,0);
	
}

function loadRegChart(t) {                                            // Show admin dashboard in page

	// Add animation to left body
	$("#graphs_top").html('<div class="brz-margin brz-padding brz-center"><img src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif" ></img></div>');
	
	// Perform AJAX request to show admin dashboard
	var performed = ajaxProtocol(load_admin_content_file,0,0,t,t,t,0,0,0,0,0,0,0,"#graphs_top","graphs_top",0,0,13);
	
}

function editAdmin() {                                                // Load page Edit administration

    // Update sidebar navigation activate->Edit Administration	
	updateNavAdmin('e');
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request	to get password updating wizard or administration
    var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,9,0,1,1);

	// Add history 
	store('/manage/edit');
	
}

function editProfileAdmin(id) {                                       // Load Edit profile for administration
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request	to fetch user edit-able user info	
    var performed = ajaxProtocol(load_admin_content_file,0,0,0,id,0,0,0,0,0,0,0,0,0,8,0,1,1);

}

function toggleAdminCates(name) {                                         // Toggle navi on admin page
    
	$('.brz-admin-cat').slideUp().prev().removeClass('brz-border-blue-grey brz-text-blue-grey').addClass('brz-border-grey');
	
	$('#'+name).slideToggle();
	
	$('#'+name).prev().toggleClass('brz-border-blue-grey brz-border-grey brz-text-blue-grey');
} 

function updateNavAdmin(id) {                                           // Update naigation on admin page
	
	// Update side navigation
	$('.brz-add-listner1').removeClass('brz-text-bold');
	$('#brz-add-listner1-'+id).addClass('brz-text-bold');
	
}

function loadStats() {                                                // Load Main Administration page

    // Store history
	store('/manage/home')
	
	// Update top navigation activate->home
	updateTopbar('brz-class-stats');

	// Update side navigation
	updateNavAdmin('1');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to load administration home || STATS
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,4,0,1,1);
	
}

function loadThemes() {                                               // Load website themes packages
	
	// Add history 
	store('/manage/themes');
	
	// Update top navigation activate->web settings
	updateTopbar('brz-class-websettings');
	
	// Update side navigation
	updateNavAdmin('4');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get theme page
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,5,0,1,1);
	
}

function loadLanguages() {                                                // Load website languages
	
	store('/manage/languages');

	updateTopbar('brz-class-lang');

	updateNavAdmin('ll');
	
	bodyLoader('content-body');
	
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,13,0,1,1);
	
}

function loadBackgrounds() {                                               // Load website post backgrounds
	
	// Add history 
	store('/manage/backgrounds');
	
	// Update top navigation activate->web settings
	updateTopbar('brz-class-backgrounds');
	
	// Update side navigation
	updateNavAdmin('bb');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get post backgrounds page
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,10,0,1,1);
	
}

function uploadExtension(text,t) {                                     // Upload website extensions
    
	// Check response type
	if(t==1) {
		
		$('#LOADER_EXTENTION').remove();

		if(text==1) {
			loadExtensions();
		} else {
			$("#EXT_UPLOAD").show().append(text);
		}
		
		return true;	
	}
	
	// Add preloader
	$('#EXT_UPLOAD').hide();
	$('#LOADER_EXTENTION').remove();	
	$('#EXT_UPLOAD').after('<div id="LOADER_EXTENTION" class="brz-middle brz-center"><img class="brz-center brz-margin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif"></img></div>');

	// Submit etension for upload
	document.getElementById("EXT_FORM").submit();
	
	return false;

}

function uploadUpdate(text,t) {                                     // Upload website update
    
	// Check response type
	if(t==1) {
		
		$('#LOADER_EXTENTION').remove();

		if(text==1) {
			updateWebsite();
		} else {
			$("#UP_UPLOAD").show().append(text);
		}
		
		return true;	
	}
	
	// Add preloader
	$('#UP_UPLOAD').hide();
	$('#LOADER_EXTENTION').remove();	
	$('#UP_UPLOAD').after('<div id="LOADER_EXTENTION" class="brz-middle brz-center"><img class="brz-center brz-margin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif"></img></div>');

	// Submit etension for upload
	document.getElementById("EXT_FORM").submit();
	
	return false;

}

function applyPatch(text,t) {                                     // Apply patch
    
	// Check response type
	if(t==1) {
		
		$('#LOADER_EXTENTION').remove();

		if(text==1) {
			patchWebsite();
		} else {
			$("#UP_UPLOAD").show().append(text);
		}
		
		return true;	
	}
	
	// Add preloader
	$('#UP_UPLOAD').hide();
	$('#LOADER_EXTENTION').remove();	
	$('#UP_UPLOAD').after('<div id="LOADER_EXTENTION" class="brz-middle brz-center"><img class="brz-center brz-margin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif"></img></div>');

	// Submit etension for upload
	document.getElementById("EXT_FORM").submit();
	
	return false;

}

function updateExtension(name,t) {                                     // Update website extensions
	
	// Check request type
	if(t==1) {
		el = $('#INS_EXTS');
		var type = 'install';
	} else {
		el = $('#AVA_EXTS');
		var type = 'uninstall';
	}
	
	// Add preloader
	el.html('<div class="brz-middle brz-center"><img class="brz-center brz-margin" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader-small.gif"></img></div>');
	
	// Submit request to install/uninstall extension
	$.ajax({
		type: "POST",
		url: $('#installation_select').val()+'/index.php?extend='+type+'&name='+name,
		data: '&no=2ha',
		cache: false,
		success: function(data) {
			
			el.html(data);	
		
		}
	});	
}

function loadExtensions() {                                               // Load website extensions
	
	// Add history 
	store('/manage/extensions');
	
	// Update top navigation activate->web settings
	updateTopbar('brz-class-backgrounds');
	
	// Update side navigation
	updateNavAdmin('ex');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get post backgrounds page
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,15,0,1,1);
	
}

function loadCategories() {                                               // Load website Categories
	
	// Add history 
	store('/manage/cats');
	
	// Update top navigation activate->web settings
	updateTopbar('brz-class-backgrounds');
	
	// Update side navigation
	updateNavAdmin('cats');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get post backgrounds page
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,16,0,1,1);
	
}

function delCat(id) {                                             // Delete new category
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get post backgrounds page
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,id,0,0,0,0,0,0,0,0,0,18,0,1,1);
	
}

function addCat(t) {                                              // Add new category
	
	var val  = $("#add_cat_"+t).val();
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get post backgrounds page
	var performed = ajaxProtocol(load_admin_content_file,0,0,t,val,0,0,0,0,0,0,0,0,0,17,0,1,1);
	
}

function activateBackground(name) {                                        // Load website themes packages
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get theme page
	var performed = ajaxProtocol(load_admin_content_file,0,0,name,0,0,0,0,0,0,0,0,0,0,11,0,1,1);
	
}

function reorderBackground(name) {                                        // Load website themes packages
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get theme page
	var performed = ajaxProtocol(load_admin_content_file,0,0,name,0,0,0,0,0,0,0,0,0,0,12,0,1,1);
	
}

function saveLanguage(name) {                                        // Load website themes packages
	
	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get theme page
	var performed = ajaxProtocol(load_admin_content_file,0,0,name,0,0,0,0,0,0,0,0,0,0,14,0,1,1);
	
}

function addedBackground(r,t) {                                              // After addition of new background

    // Remove preloader
    $("#add_background_trigger").html('<img src="'+$('#installation_select').val()+'/index.php?thumb=1&src=add-image.png&fol=bb&w=252&h=192" style="width:100%;cursor:pointer"></img>');

    // Show errors if any
    $('#addBackground_error').html(r);
	
	// If updated load new backgrounds
	if(t == 1) {
		loadBackgrounds();
	}
}

function updateWebsite() {                                               // Load website updater
	
	// Add history 
	store('/manage/update');
	
	// Update top navigation activate->web settings
	updateTopbar('brz-class-null');
	
	// Update side navigation
	updateNavAdmin('web');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get post backgrounds page
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,19,0,1,1);
	
}
 
function clearTemp() {                                                  // Clear temp files such as logs

    // Add pre-loader
    $("#TEMP_FILE_CONTAINER").html('<div align="center" class="brz-padding-16" ><img src="'+$("#installation_select").val()+'/themes/'+$("#theme_select").val()+'/img/icons/cc_loader.gif"></div>');
	
	// Perform AJAX request to mark action on selected report
	var performed = ajaxProtocol(execute_temp_file,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,98);
	
}

function patchWebsite() {                                               // Load website patcher
	
	// Add history 
	store('/manage/patch');
	
	// Update top navigation activate->web settings
	updateTopbar('brz-class-null');
	
	// Update side navigation
	updateNavAdmin('patch');

	// Add animation to full body	
	bodyLoader('content-body');
	
	// Perform AJAX request to get post backgrounds page
	var performed = ajaxProtocol(load_admin_content_file,0,0,0,0,0,0,0,0,0,0,0,0,0,20,0,1,1);
	
}

function addBackground() {                                                // Add new background
	
	// Add preloader
	$("#add_background_trigger").html('<img style="width:30%;margin:20px auto auto 20px;" class="settings-tab-loader brz-center nav-text-inverse-big" src="'+$('#installation_select').val()+'/themes/'+$('#theme_select').val()+'/img/icons/loader.gif"></img>');
	
	// Submit form
	document.getElementById("add_background").submit();
	
	return false;
}