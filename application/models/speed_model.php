<?php if (!defined("BASEPATH")) exit("No direct script access allowed"); 

class Speed_model extends CI_Model
{
	var $id;
	var $highway;
	var $datee;
	var $epoch;
	var $segment;
	var $freight;
	var $passenger;
	var $total;
	var $raw_freight;
	var $raw_passenger;
	var $raw_total;

	function __construct()
	{
		parent::__construct();
	}

	public function upsert_speed($type="",$speed= "")
	{
		$query = '	INSERT INTO "NPMRDS".speed(highway,datee,epoch,segment,'.$type.') 
					VALUES (
						\''.$this->highway.'\',\''.$this->datee.'\',\''.$this->epoch.'\',\''.$this->segment.'\','.$speed.'
					)
					ON CONFLICT (highway,datee,epoch,segment)
					DO
					UPDATE SET	"'.$type.'" = '.$speed.'
						WHERE 	"speed".highway = \''.$this->highway.'\'
							and	"speed".datee = \''.$this->datee.'\'
							and	"speed".epoch = \''.$this->epoch.'\'
							and	"speed".segment = \''.$this->segment.'\'';
		$this->db->query($query);
	}
	
	public function delete_speed( $ids = '')
	{
		if($ids != '')
		{
			$query = 'DELETE FROM "NPMRDS".speed WHERE id IN (';
			$ids = explode(",",$ids);
			for($i=0;$i<count($ids);$i++)
			{
				if ($i == count($ids)-1)
					$query .= "'".$ids[$i]."'";
				else
					$query .= "'".$ids[$i]."',";
			}
			$query .= ')';
			$this->db->query($query);
		}
		else
		{
			$query = 'DELETE FROM "NPMRDS".speed WHERE 1=1 ';
			
			$this->db->query($query);
		}
	}
	
	public function get_speed($limit = "", $from="", $to="")
	{
		$query = 'SELECT * FROM "NPMRDS".speed WHERE 1=1 ';
		
		if($this->id != '')
			$query .= " and id = '{$this->id}'";
		if($this->highway != '')
			$query .= " and highway = '{$this->highway}'";
		if($this->datee != '')
			$query .= " and datee = '{$this->datee}'";
		if($from != '')
			$query .= " and datee >= '{$from}'";
		if($to != '')
			$query .= " and datee <= '{$to}'";
		if($this->epoch != '')
			$query .= " and epoch = '{$this->epoch}'";
		if($this->segment != '')
			$query .= " and segment = '{$this->segment}'";
		if($this->freight != '')
			$query .= " and freight = {$this->freight}";
		if($this->passenger != '')
			$query .= " and passenger = {$this->passenger}";
		if($this->total != '')
			$query .= " and total = {$this->total}";
		if($this->raw_freight != '')
			$query .= " and raw_freight = {$this->raw_freight}";
		if($this->raw_passenger != '')
			$query .= " and raw_passenger = {$this->raw_passenger}";
		if($this->raw_total != '')
			$query .= " and raw_total = {$this->raw_total}";
		if($limit != '')
			$query .= " ORDER BY datee,epoch LIMIT ".$limit;
		
		$query = $this->db->query($query);
		return $query->result_array();
	}
	public function get_speed_export($segments = "",$speed="", $from="", $to="",$limit="ALL",$offset="0")
	{
		$query = 'SELECT to_char(datee,\'FMMMDDYYYY\'),epoch ';
		
		for($i=0;$i<count($segments);$i++)
			$query .= ',MAX(CASE WHEN segment = \''.$segments[$i]["segment"].'\' THEN '.$speed.' ELSE NULL END) AS "'.$segments[$i]["segment"].'"';
			
		$query .= 'FROM "NPMRDS".speed WHERE 1=1 ';
		
		if($this->highway != '')
			$query .= " and highway = '{$this->highway}'";
		if($this->datee != '')
			$query .= " and datee = '{$this->datee}'";
		if($from != '')
			$query .= " and datee >= '{$from}'";
		if($to != '')
			$query .= " and datee <= '{$to}'";
			
		$query .= " GROUP BY datee,epoch 
					ORDER BY datee,epoch LIMIT ".$limit." OFFSET ".$offset." ";

		$query = $this->db->query($query);
		return $query->result_array();
	}
	
	public function get_speed_export_count($speed="", $from="", $to="")
	{
		$query = '	SELECT count(*) speed_count FROM 
					( 	SELECT datee,epoch 
						FROM "NPMRDS".speed 
						WHERE 1=1 ';
		
		if($this->highway != '')
			$query .= " 	AND highway = '{$this->highway}'";
		if($this->datee != '')
			$query .= " 	AND datee = '{$this->datee}'";
		if($from != '')
			$query .= " 	AND datee >= '{$from}'";
		if($to != '')
			$query .= " 	AND datee <= '{$to}'";
			
		$query .= ' 	GROUP BY datee,epoch 
					) S';
		
		$query = $this->db->query($query);
		return $query->result_array();
	}
	
	public function get_speed_count($from="",$to="")
	{
		$query = 'SELECT count(*) speed_count FROM "NPMRDS".speed where 1=1 ';
		if($this->highway != '')
			$query .= " and highway = '{$this->highway}'";
		if($this->datee != '')
			$query .= " and datee = '{$this->datee}'";
		if($from != '')
			$query .= " and datee >= '{$from}'";
		if($to != '')
			$query .= " and datee <= '{$to}'";
		if($this->epoch != '')
			$query .= " and epoch = '{$this->epoch}'";
		if($this->segment != '')
			$query .= " and segment = '{$this->segment}'";
		
		$query = $this->db->query($query);
		return $query->result_array();
	}
	public function get_highway()
	{
		$query = 'SELECT DISTINCT highway FROM "NPMRDS".speed ';
		
		$query = $this->db->query($query);
		return $query->result_array();
	}
	public function get_speed_segment()
	{
		$query = '	SELECT DISTINCT segment 
					FROM "NPMRDS".speed WHERE 1=1 ';
		
		if($this->highway != '')
			$query .= " and highway = '{$this->highway}'";
				
		$query = $this->db->query($query);
		return $query->result_array();
	}
}