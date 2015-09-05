<?php
        
/*              
        Plugin Name: Community Posts
        Plugin URI:    
        Plugin Update Check URI:   
        Plugin Description: Allows users to create community posts
        Plugin Version: 1.0
        Plugin Date: 2015-03-14
        Plugin Author: Bruno Vandekerkhove
        Plugin Author URI: http://brunovandekerkhove.com                          
        Plugin License: GPLv2                           
        Plugin Minimum Question2Answer Version: 1.5
*/                      
                        
                        
    if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
    	header('Location: ../../');
    	exit;   
    }               

    qa_register_plugin_module('module', 'qa-cp-admin.php', 'qa_cp_admin', 'Community Posts Admin');
    qa_register_plugin_module('event', 'qa-cp-check.php', 'qa_cp_event', 'Community Posts Admin');
    qa_register_plugin_module('page', 'qa-cp-page.php', 'qa_cp_page', 'Community Posts Page');
    
    qa_register_plugin_layer('qa-cp-layer.php', 'Community Posts Layer');
                    
    if (function_exists('qa_register_plugin_phrases')) {
        qa_register_plugin_overrides('qa-cp-overrides.php');
        qa_register_plugin_phrases('qa-cp-lang-*.php', 'cp');
    } 

	if (!function_exists('qa_permit_check')) {
		function qa_permit_check($opt) {
			if(qa_opt($opt) == QA_PERMIT_POINTS)
				return qa_get_logged_in_points() >= qa_opt($opt.'_points');
			return !qa_permit_value_error(qa_opt($opt), qa_get_logged_in_userid(), qa_get_logged_in_level(), qa_get_logged_in_flags());
		}
	}

/*                              
        Omit PHP closing tag to help avoid accidental output
*/                              
                          

