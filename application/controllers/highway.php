<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Highway extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata['id'])
			redirect(base_url().'login');
	}
	
	public function index()
	{
		$data = $this->globalclass->get_global_data();
		
		$this->load->model('highway_model');
		
		$data["highways"] = $this->highway_model->get_highway();
		
		$this->load->view('header',$data);
		$this->load->view('highway_view',$data);
		$this->load->view('footer');
	}
	public function create_node()
	{
		$this->load->model("highway_model");
		$this->highway_model->name = $this->input->post("text");
		echo $this->highway_model->add_highway();
	}
	public function rename_node()
	{
		$this->load->model("highway_model");
		$this->highway_model->id = $this->input->post("id");
		$this->highway_model->name = $this->input->post("text");
		$this->highway_model->modify_highway();
	}
	public function delete_node()
	{
		$this->load->model("highway_model");
		$this->highway_model->id = $this->input->post("id");
		$this->highway_model->delete_highway();
	}
}
