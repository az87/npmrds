<?php if (!defined("BASEPATH")) exit("No direct script access allowed"); 

class User_model extends CI_Model
{
	var $id;
	var $user_name;
	var $full_name;
	var $email;
	var $password;
	var $role;
	var $date_update;

	function __construct()
	{
		parent::__construct();
	}

	public function add_user(){
		$query = 'INSERT INTO "NPMRDS"."user"(
							user_name,
							role,
							date_update
						) 
						VALUES (
							\''.$this->user_name.'\',
							\'U\',
							CURRENT_TIMESTAMP
						)';
		$this->db->query($query);
		return $this->db->insert_id();
	}

	public function add_registered_user($key)
	{
		$this->db->where('confirm_key',$key);
		$temp_user = $this->db->get('user_temp');
		if($temp_user)
		{
			$row = $temp_user->row();
			
			$query = 'INSERT INTO "NPMRDS"."user"(
								full_name,
								user_name,
								email,
								password,
								role,
								date_update
							) 
							VALUES (
								\''.$row->full_name.'\',
								\''.$row->user_name.'\',
								\''.$row->email.'\',
								\''.$row->password.'\',
								\'U\',
								CURRENT_TIMESTAMP
							)';
			$this->db->query($query);
			$user_id =  $this->db->insert_id();
		}
		
		if($user_id > 0){
			$this->db->where('confirm_key',$key);
			$this->db->delete("user_temp");
			return $user_id;
		}
		else
			return false;
	}
	public function add_user_temp($key="")
	{
		$query = 'INSERT INTO "NPMRDS"."user_temp"(
							full_name,
							user_name,
							email,
							password,
							confirm_key
						) 
						VALUES (
							\''.$this->full_name.'\',
							\''.$this->user_name.'\',
							\''.$this->email.'\',
							\''.$this->password.'\',
							\''.$key.'\'
						)';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	public function is_valid_key($key)
	{
		$query = '	SELECT * FROM "NPMRDS"."user_temp" where confirm_key = \''.$key.'\'';
		$query = $this->db->query($query);
		if($query->num_rows() == 1)
			return true;
		else
			return false;
	}
	public function deleteUser()
	{
		$query = '	delete from "NPMRDS"."user" where id = \''.$this->id.'\'';
		$this->db->query($query);
		return true;
	}

	public function modifyUser()
	{
		$query = 'UPDATE "NPMRDS"."user"
				  SET ';
		if(isset($this->user_name))
			$query .= " user_name='{$this->user_name}',";
		if(isset($this->full_name))
			$query .= " full_name='{$this->full_name}',";
		if(isset($this->email))
			$query .= " email='{$this->email}',";
		if(isset($this->password) && $this->password != '')
			$query .= " password='{$this->password}',";
		if($this->role != '')
			$query .= " role='{$this->role}',";
		$query .= " date_update = CURRENT_TIMESTAMP WHERE id = {$this->id}";
		$this->db->query($query);
		return true;
	}
	
	public function get_user()
	{
		$query = 'SELECT * FROM "NPMRDS"."user" WHERE 1=1 ';
		if($this->id != '')
			$query .= " and id = {$this->id}";
		if($this->user_name != '')
			$query .= " and user_name = '{$this->user_name}'";
		if($this->password != '')
			$query .= " and password = '{$this->password}'";
		
		$query = $this->db->query($query);
		return $query->result_array();
	}

	public function can_log_in()
	{		
		$query = '	SELECT * FROM "NPMRDS"."user"
					where user_name = \''.$this->user_name.'\'
					and password = \''.md5($this->password).'\'';
		$query = $this->db->query($query);
		if($query->num_rows() == 1)
			return true;
		else
			return false;
	}
	
	public function log_in()
	{
		$query = '	SELECT * FROM "NPMRDS"."user"
					where user_name = \''.$this->user_name.'\'
					and password = \''.md5($this->password).'\'  ORDER BY id ASC LIMIT 1 ';
		$query = $this->db->query($query);
		return $query->result_array();
	}
}