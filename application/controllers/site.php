<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once MODPATH.'core/controllers/nova_site.php';

class Site extends Nova_site {

	public function __construct()
	{
		parent::__construct();
	}
	
	/***************************/
	/*  BIO FORM DESCRIPTIONS  */
	/***************************/
	public function bioform()
	{
		Auth::check_access();
		
		if (isset($_POST['submit']))
		{
			switch ($this->uri->segment(3))
			{
				case 'add':
					foreach ($_POST as $key => $value)
					{
						$insert_array[$key] = $this->security->xss_clean($value);
					}
					
					// pull the items off the array
					$select = $insert_array['select_values'];
					$type = $insert_array['field_type'];
					
					// pop unnecessary items off the array
					unset($insert_array['select_values']);
					unset($insert_array['submit']);
							
					$insert = $this->char->add_bio_field($insert_array);
					$insert_id = $this->db->insert_id();
					
					$this->sys->optimize_table('characters_fields');
					
					if ($insert > 0)
					{
						if ($type == 'select')
						{
							$select_array = explode("\n", $select);
							
							$i = 0;
							foreach ($select_array as $select)
							{
								$array = explode(',', $select);
								
								$values_array = array(
									'value_field' => $insert_id,
									'value_field_value' => trim($array[0]),
									'value_content' => trim($array[1]),
									'value_order' => $i
								);
								
								$insert = $this->char->add_bio_field_value($values_array);
								
								++$i;
							}
						}
						
						$characters = $this->char->get_all_characters('all');
						
						if ($characters->num_rows() > 0)
						{
							foreach ($characters->result() as $char)
							{
								$ins_array = array(
									'data_field' => $insert_id,
									'data_char' => $char->charid,
									'data_user' => $char->user,
									'data_value' => '',
									'data_updated' => now()
								);
								
								$ins = $this->char->add_bio_field_data($ins_array);
							}
						}
						
						$message = sprintf(
							lang('flash_success'),
							ucfirst(lang('labels_bio') .' '. lang('labels_field')),
							lang('actions_created'),
							''
						);

						$flash['status'] = 'success';
						$flash['message'] = text_output($message);
					}
					else
					{
						$message = sprintf(
							lang('flash_failure'),
							ucfirst(lang('labels_bio') .' '. lang('labels_field')),
							lang('actions_created'),
							''
						);

						$flash['status'] = 'error';
						$flash['message'] = text_output($message);
					}
				break;
					
				case 'delete':
					$id = (is_numeric($this->input->post('id', true))) ? $this->input->post('id', true) : 0;
							
					$delete = $this->char->delete_bio_field($id);
					
					if ($delete > 0)
					{
						$delete_fields = $this->char->delete_character_field_data($id);
						$values = $this->char->get_bio_values($id);
						
						if ($values->num_rows() > 0)
						{
							foreach ($values->result() as $value)
							{
								$delete_values = $this->char->delete_bio_field_value($value->value_id);
							}
						}
						
						$message = sprintf(
							lang('flash_success'),
							ucfirst(lang('labels_bio') .' '. lang('labels_field')),
							lang('actions_deleted'),
							''
						);

						$flash['status'] = 'success';
						$flash['message'] = text_output($message);
					}
					else
					{
						$message = sprintf(
							lang('flash_failure'),
							ucfirst(lang('labels_bio') .' '. lang('labels_field')),
							lang('actions_deleted'),
							''
						);

						$flash['status'] = 'error';
						$flash['message'] = text_output($message);
					}
				break;
					
				case 'edit':
					foreach ($_POST as $key => $value)
					{
						$update_array[$key] = $this->security->xss_clean($value);
					}
					
					// set the ID
					$id = $update_array['field_id'];
					
					// pop unnecessary items off the array
					unset($update_array['field_id']);
					unset($update_array['submit']);

					$update = $this->char->update_bio_field($id, $update_array);
					
					if ($update > 0)
					{
						$message = sprintf(
							lang('flash_success'),
							ucfirst(lang('labels_bio') .' '. lang('labels_field')),
							lang('actions_updated'),
							''
						);

						$flash['status'] = 'success';
						$flash['message'] = text_output($message);
					}
					else
					{
						$message = sprintf(
							lang('flash_failure'),
							ucfirst(lang('labels_bio') .' '. lang('labels_field')),
							lang('actions_updated'),
							''
						);

						$flash['status'] = 'error';
						$flash['message'] = text_output($message);
					}
				break;
					
				case 'editval':
					$value = $this->input->post('value_field_value', true);
					$content = $this->input->post('value_content', true);
					$field = $this->input->post('value_field', true);
					$id = $this->input->post('id', true);

					$update_array = array(
						'value_field_value' => $value,
						'value_content' => $content,
						'value_field' => $field
					);

					$update = $this->char->update_bio_field_value($id, $update_array);

					if ($update > 0)
					{
						$message = sprintf(
							lang('flash_success'),
							ucfirst(lang('labels_bio') .' '. lang('labels_field') .' '. lang('labels_value')),
							lang('actions_updated'),
							''
						);

						$flash['status'] = 'success';
						$flash['message'] = text_output($message);
					}
					else
					{
						$message = sprintf(
							lang('flash_failure'),
							ucfirst(lang('labels_bio') .' '. lang('labels_field') .' '. lang('labels_value')),
							lang('actions_updated'),
							''
						);

						$flash['status'] = 'error';
						$flash['message'] = text_output($message);
					}
				break;
			}
			
			// set the flash message
			$this->_regions['flash_message'] = Location::view('flash', $this->skin, 'admin', $flash);
		}
		
		$id = $this->uri->segment(4, 0, true);
		
		if ($id == 0)
		{
			// grab the join fields
			$sections = $this->char->get_bio_sections();
			
			if ($sections->num_rows() > 0)
			{
				foreach ($sections->result() as $sec)
				{
					$sid = $sec->section_id; /* section id */
					
					// set the section name
					$data['join'][$sid]['name'] = $sec->section_name;
					
					// grab the fields for the given section
					$fields = $this->char->get_bio_fields($sec->section_id);
					
					if ($fields->num_rows() > 0)
					{
						foreach ($fields->result() as $field)
						{
							$f_id = $field->field_id;
							
							// set the page label
							$data['join'][$sid]['fields'][$f_id]['field_label'] = $field->field_label_page;
							
							switch ($field->field_type)
							{
								case 'text':
									$input = array(
										'name' => $field->field_id,
										'id' => $field->field_fid,
										'class' => $field->field_class,
										'value' => $field->field_value
									);
									
									$data['join'][$sid]['fields'][$f_id]['input'] = form_input($input);
									$data['join'][$sid]['fields'][$f_id]['id'] = $field->field_id;
								break;
									
								case 'textarea':
									$input = array(
										'name' => $field->field_id,
										'id' => $field->field_fid,
										'class' => $field->field_class,
										'value' => $field->field_value,
										'rows' => $field->field_rows
									);
									
									$data['join'][$sid]['fields'][$f_id]['input'] = form_textarea($input);
									$data['join'][$sid]['fields'][$f_id]['id'] = $field->field_id;
								break;
									
								case 'select':
									$value = false;
									$values = false;
									$input = false;
									
									$values = $this->char->get_bio_values($field->field_id);
									
									if ($values->num_rows() > 0)
									{
										foreach ($values->result() as $value)
										{
											$input[$value->value_field_value] = $value->value_content;
										}
									}
									
									$data['join'][$sid]['fields'][$f_id]['input'] = form_dropdown($field->field_id, $input);
									$data['join'][$sid]['fields'][$f_id]['id'] = $field->field_id;
								break;
							}
						}
					}
				}
			}
			
			$data['images'] = array(
				'tabs' => array(
					'src' => Location::img('forms-tab.png', $this->skin, 'admin'),
					'class' => 'image inline_img_left',
					'alt' => ''),
				'sections' => array(
					'src' => Location::img('forms-section.png', $this->skin, 'admin'),
					'class' => 'image inline_img_left',
					'alt' => ''),
				'edit' => array(
					'src' => Location::img('icon-edit.png', $this->skin, 'admin'),
					'class' => 'image',
					'alt' => lang('actions_edit')),
				'delete' => array(
					'src' => Location::img('icon-delete.png', $this->skin, 'admin'),
					'class' => 'image',
					'alt' => lang('actions_delete')),
				'add_field' => array(
					'src' => Location::img('icon-add.png', $this->skin, 'admin'),
					'class' => 'image inline_img_left',
					'alt' => ''),
			);
					
			// figure out where the view should be coming from
			$view_loc = 'site_bioform_all';
			
			// set the header
			$data['header'] = ucwords(lang('labels_bio') .'/'. ucfirst(lang('actions_join')) .' '. lang('labels_form'));
			$data['text'] = lang('text_bioform');
		}
		else
		{
			$field = $this->char->get_bio_field_details($id);
			
			if ($field->num_rows() > 0)
			{
				$row = $field->row();
				
				$data['id'] = $row->field_id;
				
				$data['inputs'] = array(
					'fid' => array(
						'name' => 'field_fid',
						'id' => 'field_fid',
						'value' => $row->field_fid),
					'name' => array(
						'name' => 'field_name',
						'id' => 'field_name',
						'value' => $row->field_name),
					/* ****************************************** */
					/* ADDITION FOR THE MOD BIO FIELD DESCRIPTION */
					'desc' => array(
						'name' => 'field_desc',
						'id' => 'field_desc',
						'rows'=> 4,
						'cols'=> 10),
					'descval' => $row->field_desc,
					/* END ADDITION FOR THE MOD BIO FIELD DESCRIPTION */
					/* ********************************************** */
					'class' => array(
						'name' => 'field_class',
						'id' => 'field_class',
						'value' => $row->field_class),
					'label' => array(
						'name' => 'field_label_page',
						'id' => 'field_label_page',
						'value' => $row->field_label_page),
					'value' => array(
						'name' => 'field_value',
						'id' => 'field_value',
						'value' => $row->field_value),
					'order' => array(
						'name' => 'field_order',
						'id' => 'field_order',
						'class' => 'small',
						'value' => $row->field_order),
					'display_y' => array(
						'name' => 'field_display',
						'id' => 'field_display_y',
						'value' => 'y',
						'checked' => ($row->field_display == 'y') ? true : false),
					'display_n' => array(
						'name' => 'field_display',
						'id' => 'field_display_n',
						'value' => 'n',
						'checked' => ($row->field_display == 'n') ? true : false),
					'rows' => array(
						'name' => 'field_rows',
						'id' => 'field_rows',
						'class' => 'small',
						'value' => $row->field_rows)
				);
				
				$data['values']['type'] = array(
					'text' => ucwords(lang('labels_text') .' '. lang('labels_field')),
					'textarea' => ucwords(lang('labels_text') .' '. lang('labels_area')),
					'select' => ucwords(lang('labels_dropdown') .' '. lang('labels_menu'))
				);
				
				$sections = $this->char->get_bio_sections();
		
				if ($sections->num_rows() > 0)
				{
					foreach ($sections->result() as $sec)
					{
						$data['values']['section'][$sec->section_id] = $sec->section_name;
					}
				}
				
				$data['defaults']['type'] = $row->field_type;
				$data['defaults']['section'] = $row->field_section;
			}
			
			// figure out where the view should be coming from
			$view_loc = 'site_bioform_one';
			
			// set the header
			$data['header'] = ucwords(lang('actions_edit') .' '. lang('labels_bio') .'/'. 
				ucfirst(lang('actions_join')) .' '. lang('labels_form'));
			$data['text'] = lang('text_bioform_edit');
			
			$data['buttons'] = array(
				'submit' => array(
					'type' => 'submit',
					'class' => 'button-main',
					'name' => 'submit',
					'value' => 'submit',
					'content' => ucwords(lang('actions_submit'))),
				'update' => array(
					'type' => 'submit',
					'class' => 'button-main',
					'name' => 'submit',
					'value' => 'update',
					'id' => 'update',
					'content' => ucwords(lang('actions_update'))),
				'add' => array(
					'type' => 'submit',
					'class' => 'button-main',
					'name' => 'submit',
					'rel' => $id,
					'id' => 'add',
					'content' => ucwords(lang('actions_add'))),
			);
			
			if ($row->field_type == 'select')
			{
				$values = $this->char->get_bio_values($row->field_id);
				
				$data['select'] = false;
				
				if ($values->num_rows() > 0)
				{
					foreach ($values->result() as $value)
					{
						$data['select'][$value->value_id] = $value->value_content;
					}
				}
				
				$data['loading'] = array(
					'src' => Location::img('loading-circle.gif', $this->skin, 'admin'),
					'alt' => lang('actions_loading'),
					'class' => 'image'
				);
				
				$data['inputs']['val_add_value'] = array('id' => 'value');
				$data['inputs']['val_add_content'] = array('id' => 'content');
			}
		}
		
		$data['label'] = array(
			'back' => LARROW .' '. ucfirst(lang('actions_back')) .' '. lang('labels_to') .' '. 
				ucwords(lang('labels_bio') .'/'. ucfirst(lang('actions_join')) .' '. lang('labels_form')),
			'biofield' => ucwords(lang('actions_add') .' '. lang('labels_bio') .' '. lang('labels_field')) .' '. RARROW,
			'biosections' => ucwords(lang('actions_manage') .' '. lang('labels_bio') .' '. lang('labels_sections')) .' '. RARROW,
			'biotabs' => ucwords(lang('actions_manage') .' '. lang('labels_bio') .' '. lang('labels_tabs')) .' '. RARROW,
			'bioval' => lang('text_site_bioval'),
			'class' => ucfirst(lang('labels_class')),
			'content' => ucwords(lang('labels_dropdown') .' '. lang('labels_content')),
			'display' => ucfirst(lang('labels_display')),
			'html' => lang('misc_html_attr'),
			'id' => lang('abbr_id'),
			'label' => ucwords(lang('labels_page') .' '. lang('labels_label')),
			'name' => ucfirst(lang('labels_name')),
			'no' => ucfirst(lang('labels_no')),
			'nofields' => sprintf(lang('error_not_found'), lang('labels_fields')),
			'order' => ucfirst(lang('labels_order')),
			'rows' => lang('misc_textarea_rows'),
			'section' => ucfirst(lang('labels_section')),
			'select_values' => ucwords(lang('labels_dropdown') .' '. lang('labels_menu') .' '. lang('labels_values')),
			'type' => ucwords(lang('labels_field') .' '. lang('labels_type')),
			'value' => ucwords(lang('labels_dropdown') .' '. lang('labels_value')),
			'yes' => ucfirst(lang('labels_yes')),
		);
		
		$this->_regions['content'] = Location::view($view_loc, $this->skin, 'admin', $data);
		$this->_regions['javascript'] = Location::js('site_bioform_js', $this->skin, 'admin');
		$this->_regions['title'].= $data['header'];
		
		Template::assign($this->_regions);
		
		Template::render();
	}
	
	
	
	/*******************************/
	/*  END BIO FORM DESCRIPTIONS  */
	/*******************************/
	
}
