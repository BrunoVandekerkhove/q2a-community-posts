<?php

/*

	Question2Answer (c) Gideon Greenspan
	http://www.question2answer.org/
	
	Community Posts Plugin by Bruno Vandekerkhove © 2015
	
*/

/*

	THEME ALTERATION
	
*/
class qa_html_theme_layer extends qa_html_theme_base {
		
	function doctype(){
	
		qa_html_theme_base::doctype();
		
		if (qa_opt('cp_enable') && ($this->template == 'ask' || isset($this->content['q_list']) || isset($this->content['q_view']))) {
		
			global $qa_request;
			global $wiki_enable;
			
			if($this->template == 'ask' && !qa_user_permit_error('permit_post_q') && !qa_opt('site_maintenance') && qa_permit_check('permit_create_cp')) {
				$this->content['form']['tags'] .= ' onSubmit="pollSubmit(event)"';
				$this->content['form']['fields'][] = array(
					'label' => qa_lang('cp/checkbox_text'),
					'tags' => 'NAME="cp_community" ID="cp_community"',
					'type' => 'checkbox',
					'value' => qa_post_text('cp_community') ? 1 : 0,
				);
			}
			
			
			if(isset($this->content['q_view'])) {
				$qid = $this->content['q_view']['raw']['postid'];
				$author = $this->content['q_view']['raw']['userid'];
				if (!isset($wiki_enable)) {
					$result = qa_db_query_sub('SELECT * FROM ^postmeta WHERE meta_key=$ AND post_id=#', 'is_community', $qid);
					$wiki_enable = $result->num_rows > 0;
				}
				if($wiki_enable) { // is a community post
					$this->content['title'] .= ' '.qa_lang('cp/question_title');
					// $this->content['q_view']['content'] = @$this->content['q_view']['content'].'<div id="qa-wiki-div">'.$this->getPollDiv($qid,qa_get_logged_in_userid()).'</div>';
					$this->content['q_view']['main_form_tags'] = @$this->content['q_view']['main_form_tags'].' class="qa-community-posts"';
					// print_r($this->content['q_view']['form']['buttons']);
					if (isset($this->content['q_view']['form']['buttons']['edit'])) {
						$this->content['q_view']['form']['buttons']['edit']['label'] = qa_lang_html('cp/contribute');
						$this->content['q_view']['form']['buttons']['edit']['popup'] = qa_lang_html('cp/contribute_description');
					}
					unset($this->content['q_view']['form']['buttons']['answer']);
					unset($this->content['q_view']['form']['buttons']['comment']);
					unset($this->content['a_form']);
					unset($this->content['c_form']);
				}
			}
			
			if(isset($this->content['q_list'])) {
				$wiki_array = qa_db_read_all_assoc(
					qa_db_query_sub(
						'SELECT * FROM ^postmeta WHERE meta_key=$',
						'is_community'
					)
				);
				
				foreach($wiki_array as $q) {
					$wiki[(int)$q['post_id']] = $q['meta_value'];
				}
				foreach($this->content['q_list']['qs'] as $idx => $question) {
					if(isset($wiki[$question['raw']['postid']])) {
						$this->content['q_list']['qs'][$idx]['title'] .= ' '.qa_lang('cp/question_title');
					}
				}					
			}
			
		}
	}
		
}

