<?php if (!defined("BASEPATH")) exit("No direct script access allowed"); 

class Highway_model extends CI_Model
{
	var $id;
	var $name;

	function __construct()
	{
		parent::__construct();
	}
	
	public function add_highway(){
		$query = '	INSERT INTO "NPMRDS".highway(name) 
					VALUES (
						\''.$this->name.'\'
					)';
		$this->db->query($query);
		return $this->db->insert_id();
	}
	
	public function delete_highway()
	{
		$query = '	DELETE FROM "NPMRDS".highway where id = \''.$this->id.'\'';
		$this->db->query($query);
		return true;
	}

	public function modify_highway()
	{
		$query = 'UPDATE "NPMRDS".highway SET  name=\''.$this->name.'\' where id = \''.$this->id.'\'';
		$this->db->query($query);
		return true;
	}
	
	public function get_highway()
	{
		$query = 'SELECT * FROM "NPMRDS".highway WHERE 1=1 ';
		if($this->id != '')
			$query .= " and id = {$this->id}";
		if($this->name != '')
			$query .= " and name = '{$this->name}'";
		
		$query = $this->db->query($query);
		return $query->result_array();
	}
}