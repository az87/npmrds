<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Globalclass {
	
	public function __construct()
    {
		
    }

    public function get_global_data()
    {
		$CI =& get_instance();
		
		$CI->load->model('user_model');
		$CI->user_model->id = $CI->session->userdata('id');
		$user = $CI->user_model->get_user();
		$CI->user_model->id = '';
		$data['user'] = $user[0];
		if($user[0]["role"] == 'A')
			$data["role"] = true;
		else
			$data["role"] = false;
			
		return $data;
    }
}