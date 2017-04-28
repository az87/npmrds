<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata['id'])
			redirect(base_url().'login');
	}
	
	public function index()
	{			
		$this->manage();
	}
	
	public function manage()
	{
		$data = $this->globalclass->get_global_data();
			
		$data["active_menu"] = "settings";
		$data["page_title"] = "Manage User";
		
		$this->load->model('user_model');
		
		$data["users"] = $this->user_model->get_user();
		
		$this->load->view('header',$data);
		$this->load->view('user_view',$data);
		$this->load->view('footer');
	}
	public function create_node()
	{
		$this->load->model("user_model");
		$this->user_model->user_name = $this->input->post("text");
		echo $this->user_model->add_user();
	}
	public function rename_node()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->input->post("id");
		$this->user_model->user_name = $this->input->post("text");
		$this->user_model->modifyUser();
	}
	public function delete_node()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->input->post("id");
		$this->user_model->deleteUser();
	}
	public function make_admin()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->input->post("id");
		$this->user_model->role = 'A';
		$this->user_model->modifyUser();
	}
	public function remove_admin()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->input->post("id");
		$this->user_model->role = 'U';
		$this->user_model->modifyUser();
	}
	public function settings()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->input->post("id");
		$data['user'] = $this->user_model->get_user();
		echo $this->load->view('user_settings',$data);
	}
	public function save_settings()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->input->post("id");
		$this->user_model->full_name = $this->input->post("full_name");
		$this->user_model->email = $this->input->post("email");
		if($this->input->post("password") != '')
		$this->user_model->password = md5($this->input->post("password"));
		echo $this->user_model->modifyUser();
	}
	public function update_info()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->session->userdata['id'];
		$this->user_model->full_name = $this->input->post("full_name");
		$this->user_model->email = $this->input->post("email");
		$this->user_model->modifyUser();
		redirect(base_url().'user/profile');
	}
	public function validate_password()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->session->userdata['id'];
		$this->user_model->password = md5($this->input->post("password"));
		$user = $this->user_model->get_user();
		if(count($user)>0)
			echo true;
		else
			echo false;
	}
	public function change_password()
	{
		$this->load->model("user_model");
		$this->user_model->id = $this->session->userdata['id'];
		$this->user_model->password = md5($this->input->post("password"));
		$this->user_model->id_user = $this->session->userdata['id'];
		echo $this->user_model->modifyUser();
	}
	
	public function profile()
	{
		$data = $this->globalclass->get_global_data();
		$data['page_title'] = 'profile';
		$this->load->view('header',$data);
		$this->load->view('profile');
		$this->load->view('footer');
	}
}
