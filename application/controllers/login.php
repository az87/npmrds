<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
	public function index()
	{
		if(isset($this->session->userdata['id']))
			redirect(base_url().'main');
		$this->log();
	}
	public function log()
	{		
		$this->load->view("login_view");
	}
		
	public function login_validation()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('user_name','User Name','required|trim|xss_clean|callback_validate_credintials');
		$this->form_validation->set_rules('password','Password','required|trim');
		if($this->form_validation->run())
		{
			$this->load->model('user_model');
			$this->user_model->user_name = $this->input->post('user_name');
			$this->user_model->password = $this->input->post('password');
			$user = $this->user_model->log_in();
			$data = array(
				'id' => $user[0]['id'],
				'user_name' => $user[0]['user_name']
			);
			$this->session->set_userdata($data);
			redirect(base_url().'main');
		}
		else
		{
			$this->load->view("login_view");
		}
	}
	
	public function validate_credintials()
	{
		$this->load->model('user_model');
		$this->load->library('form_validation');
		$this->user_model->user_name = $this->input->post('user_name');
		$this->user_model->password = $this->input->post('password');
		if($this->user_model->can_log_in())
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('validate_credintials','Incorrect Username/Password');
			return false;
		}
		
	}
	public function lock()
	{
		$user_name = $this->session->userdata['user_name'];
		$this->session->sess_destroy();
		if($user_name != '')
			redirect(base_url()."login/lock_screen/".$user_name);
		else
			redirect(base_url()."login");
	}
	public function lock_screen()
	{
		$this->session->sess_destroy();
	}
	public function open_screen()
	{
		$this->load->model('user_model');
		$this->user_model->user_name = $this->input->post('user_name');
		$this->user_model->password = $this->input->post('password');
		if($this->user_model->can_log_in())
		{
			$user = $this->user_model->log_in();
			$data = array(
				'id' => $user[0]['id'],
				'user_name' => $user[0]['user_name']
			);
			$this->session->set_userdata($data);
			echo "done";
		}
		else
		{
			echo 'Incorrect Password';
		}
	}
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}
	public function register_validation()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('full_name','Full Name','required|trim|xss_clean');
		$this->form_validation->set_rules('user_name','User Name','required|trim|xss_clean');
		$this->form_validation->set_rules('email','Email','required|trim|valid_email|is_unique[[user].email]');
		$this->form_validation->set_rules('password','Password','required|trim|md5');
		
		$this->form_validation->set_message('is_unique','That email address already exists.');
		
		if($this->form_validation->run())
		{
			$key = md5(uniqid());
			
			$config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => 465,
				'smtp_user' => 'mdcc.odot@gmail.com',
				'smtp_pass' => 'ecobuild',
				'mailtype'  => 'html', 
				'charset'   => 'iso-8859-1'
			);
			
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
			
			$this->email->from("mdcc.odot@gmail.com","NPMRDS Tools");
			$this->email->to($this->input->post('email'));
			$this->email->subject("confirm your account.");
			
			$message = "<p>Thank you for registering!</p>";
			$message .= "<p><a href='".base_url()."login/register_user/$key'>Click here</a> to confirm your account</p>";
			$this->email->message($message);
			$this->load->model('user_model');
			$this->user_model->full_name = $this->input->post('full_name');
			$this->user_model->user_name = $this->input->post('user_name');
			$this->user_model->email = $this->input->post('email');
			$this->user_model->password = $this->input->post('password');
			if($this->user_model->add_user_temp($key))
			{
				if($this->email->send())
				{
					echo "The email has been sent<BR>Please check your email to confirm your account.<br>Then you can <a href='".base_url()."login'>login</a>";
				}
				else
				{
					echo "Error in sending email";
				}			
			}
			else
			{
				echo "Error in database";
			}
		}
		else
		{
			echo validation_errors();
		}
	}
	
	public function register_user($key)
	{
		$this->load->model('user_model');
		if($this->user_model->is_valid_key($key))
		{
			if($user_id = $this->user_model->add_registered_user($key))
			{
				$this->user_model->id = $user_id;
				$user = $this->user_model->get_user();
				$data = array(
					'id' => $user[0]['id'],
					'user_name' => $user[0]['user_name']
				);
				$this->session->set_userdata($data);
				redirect(base_url().'main');
			}
			else
			{
				echo "Failed to add user, please try again.";
			}
		}
		else
		{
			echo "Invalid Key";
		}
	}
}