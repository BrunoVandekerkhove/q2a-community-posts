<?php

/*

	Question2Answer (c) Gideon Greenspan
	http://www.question2answer.org/
	
	Community Posts Plugin by Bruno Vandekerkhove © 2015
	
*/

	class qa_cp_event {
	
		// Intercept the question posting event
		function process_event($event, $userid, $handle, $cookieid, $params) {
		
			if (qa_opt('cp_enable')) {
			
				switch ($event) {
				
					case 'q_post':
					
						// If question is set as community post then the ^postmeta table is altered to reflect this (this table is created if it does not exist)
						if(qa_post_text('cp_community')) {
						
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
											
							qa_db_query_sub(
								'INSERT INTO ^postmeta (post_id,meta_key,meta_value) VALUES (#,$,$)',
								$params['postid'],'is_community',(qa_post_text('poll_multiple')?'2':'1')
							);
							
						}
						
						break;
						
					default:
						break;
						
				}
			}
			
		}
		
	}			