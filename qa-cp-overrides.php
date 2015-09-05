<?php
		
/*

	Question2Answer (c) Gideon Greenspan
	http://www.question2answer.org/
	
	Community Posts Plugin by Bruno Vandekerkhove © 2015
	
*/

/*

	FUNCTION OVERRIDING (this file adds permission options to the admin panel)
	
*/
		
	function qa_get_permit_options() {
		$permits = qa_get_permit_options_base();
		$permits[] = 'permit_create_cp';
		$permits[] = 'permit_edit_cp';
		return $permits;
	}
	
	function qa_get_request_content() {
		$qa_content = qa_get_request_content_base();
		if(isset($qa_content['form_profile']['fields']['permits'])) {			
			
				$ov = $qa_content['form_profile']['fields']['permits']['value'];
				$ov = str_replace('[profile/permit_create_cp]',qa_lang('cp/permit_create_community_posts'),$ov);
				$ov = str_replace('[profile/permit_edit_cp]',qa_lang('cp/permit_edit_community_posts'),$ov);
				$qa_content['form_profile']['fields']['permits']['value'] = $ov;
		}
		return $qa_content;
	}
	
	function qa_user_permit_error($permitoption=null, $limitaction=null, $userlevel=null, $checkblocks=true) {
		global $wiki_enable;
		$permit_error = qa_user_permit_error_base($permitoption, $limitaction, $userlevel, $checkblocks);
		if ($permitoption == 'permit_edit_q' && $permit_error == 'level' && qa_is_logged_in()) {
			if (!isset($wiki_enable)) {
				$result = qa_db_query_sub('SELECT * FROM ^postmeta WHERE meta_key=$ AND post_id=#', 'is_community', qa_request_part(0));
				$wiki_enable = $result->num_rows > 0;
			}
			if ($wiki_enable)
				return false;
		}
		return $permit_error;
	}
		
/*							  
		Omit PHP closing tag to help avoid accidental output
*/							  
						  

