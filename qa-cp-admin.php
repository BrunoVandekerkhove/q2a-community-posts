<?php

/*

	Question2Answer (c) Gideon Greenspan
	http://www.question2answer.org/
	
	Community Posts Plugin by Bruno Vandekerkhove © 2015
	
*/

/*

	ADMIN PANEL
	
*/
class qa_cp_admin {

	// Default options
	function option_default($option) {
		switch($option) {
			case 'cp_enable':
				return true;
			case 'permit_create_cp':
				return QA_PERMIT_USERS;
			case 'permit_edit_cp':
				return QA_PERMIT_USERS;
			default:
				return null;				
		}
	}
	
	// Template to allow
	function allow_template($template) {
		return ($template != 'admin');
	}	   
			
	// Create the admin form
	function admin_form(&$qa_content) {					   
							
		// Process form input
		$ok = null;
		if (qa_clicked('cp_save')) { // Save
		
			qa_db_query_sub(
				'CREATE TABLE IF NOT EXISTS ^postmeta (
					meta_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
					post_id bigint(20) unsigned NOT NULL,
					meta_key varchar(255) DEFAULT \'\',
					meta_value longtext,
					PRIMARY KEY (meta_id),
					KEY post_id (post_id),
					KEY meta_key (meta_key)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8'
			);					
			
			qa_opt('cp_enable', (bool)qa_post_text('cp_enable'));
			
			$ok = qa_lang('admin/options_saved');
			
		}
		else if (qa_clicked('cp_reset')) { // Reset
		
			foreach($_POST as $i => $v) {
			
				$def = $this->option_default($i);
				if ($def !== null) 
					qa_opt($i,$def);
			}
			
			qa_opt('cp_enable', true);
			
			$ok = qa_lang('admin/options_reset');
			
		}
  
		// Create the form for display
		$fields = array();
		$fields[] = array(
			'label' => qa_lang('cp/enable_community_posts'),
			'tags' => 'NAME="cp_enable"',
			'value' => qa_opt('cp_enable'),
			'type' => 'checkbox',
		);
		return array(		   
			'ok' => ($ok && !isset($error)) ? $ok : null,
			'fields' => $fields,
			'buttons' => array(
				array(
					'label' => qa_lang_html('main/save_button'),
					'tags' => 'NAME="cp_save"',
				),
				array(
					'label' => qa_lang_html('admin/reset_options_button'),
					'tags' => 'NAME="cp_reset"',
				),
			),
		);
		
	}

}

