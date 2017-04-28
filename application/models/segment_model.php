<?php if (!defined("BASEPATH")) exit("No direct script access allowed"); 

class Segment_model extends CI_Model
{
	var $id;
	var $years;
	var $quarter;
	var $tmc;
	var $admin_level_1;
	var $admin_level_2;
	var $admin_level_3;
	var $distance;
	var $road_number;
	var $road_name;
	var $latitude;
	var $longitude;
	var $road_direction;

	function __construct()
	{
		parent::__construct();
	}

	public function add_segments($data)
	{
		$this->db->insert_batch('"NPMRDS".segment', $data);
	}
	
	public function add_segment_order($data)
	{
		$this->db->insert_batch('"NPMRDS".segment_order', $data);
	}
	
	public function update_segments($data,$index)
	{
		$this->db->update_batch('"NPMRDS".segment', $data,$index);		
	}
	
	public function upsert_segments()
	{
		$query = '	INSERT INTO "NPMRDS".segment
					(
						years,quarter,tmc,admin_level_1,admin_level_2,admin_level_3,distance,road_number,road_name,latitude,longitude,road_direction
					)
					VALUES 
					(
						\''.$this->years.'\',
						\''.$this->quarter.'\',
						\''.$this->tmc.'\',
						\''.$this->admin_level_1.'\',
						\''.$this->admin_level_2.'\',
						\''.$this->admin_level_3.'\',
						\''.$this->distance.'\',
						\''.$this->road_number.'\',
						\''.$this->road_name.'\',
						\''.$this->latitude.'\',
						\''.$this->longitude.'\',
						\''.$this->road_direction.'\'
					)
					ON CONFLICT (years,quarter,tmc)
					DO
					UPDATE SET
						admin_level_1 = \''.$this->admin_level_1.'\',
						admin_level_2 = \''.$this->admin_level_2.'\',
						admin_level_3 = \''.$this->admin_level_3.'\',
						distance = \''.$this->distance.'\',
						road_number = \''.$this->road_number.'\',
						road_name = \''.$this->road_name.'\',
						latitude = \''.$this->latitude.'\',
						longitude = \''.$this->longitude.'\',
						road_direction = \''.$this->road_direction.'\'
						WHERE 	"segment".tmc = \''.$this->tmc.'\'
							and "segment".years = \''.$this->years.'\'
							and "segment".quarter = \''.$this->quarter.'\'';
		$this->db->query($query);		
	}
	
	public function add_segment(){
		$query = 'INSERT INTO "NPMRDS".segment(
						years,quarter,tmc,admin_level_1,admin_level_2,admin_level_3,distance,road_number,road_name,latitude,longitude,road_direction
					) 
					VALUES (
						\''.$this->years.'\',
						\''.$this->quarter.'\',
						\''.$this->tmc.'\',
						\''.$this->admin_level_1.'\',
						\''.$this->admin_level_2.'\',
						\''.$this->admin_level_3.'\',
						\''.$this->distance.'\',
						\''.$this->road_number.'\',
						\''.$this->road_name.'\',
						\''.$this->latitude.'\',
						\''.$this->longitude.'\',
						\''.$this->road_direction.'\'
					)';
		$this->db->query($query);
	}
	
	public function delete_segment( $ids = '')
	{
		if($ids != '')
		{
			$query = 'DELETE FROM "NPMRDS".segment WHERE tmc IN (';
			$segments = explode(",",$ids);
			for($i=0;$i<count($segments);$i++)
			{
				if ($i == count($segments)-1)
					$query .= "'".$segments[$i]."'";
				else
					$query .= "'".$segments[$i]."',";
			}
			$query .= ')';
			$this->db->query($query);
		}
		else
		{
			$query = 'DELETE FROM "NPMRDS".segment WHERE 1=1 ';
			if($this->years != '')
				$query .= " and years = '{$this->years}'";
			if($this->quarter != '')
				$query .= " and quarter = '{$this->quarter}'";
			$this->db->query($query);
		}
	}
	
	public function delete_segment_order($highway="",$seg_type="")
	{
		$query = 'DELETE FROM "NPMRDS".segment_order WHERE highway = \''.$highway.'\' and seg_type = \''.$seg_type.'\' ';
		$this->db->query($query);
	}
	public function get_segment_TMC()
	{
		$query = 'SELECT DISTINCT tmc FROM "NPMRDS".segment';
		$query = $this->db->query($query);
		return $query->result_array();
	}
	public function get_segment_count()
	{
		$query = 'SELECT count(*) segment_count FROM "NPMRDS".segment where 1=1 ';
		if($this->years != '')
			$query .= " and years = '{$this->years}'";
		if($this->quarter != '')
			$query .= " and quarter = '{$this->quarter}'";
		$query = $this->db->query($query);
		return $query->result_array();
	}
	
	public function get_segment_order($highway = "", $seg_type = "")
	{
		$query = 'SELECT * FROM "NPMRDS".segment_order WHERE highway = \''.$highway.'\' and seg_type = \''.$seg_type.'\' ORDER BY seg_ord ';
		$query = $this->db->query($query);
		return $query->result_array();
	}
	
	public function get_last_segments()
	{
		$query = '	SELECT years, quarter
					FROM "NPMRDS".segment 
					ORDER BY years DESC,quarter DESC LIMIT 1 ';
		$query = $this->db->query($query);
		return $query->result_array();
	}
	public function get_segment_points()
	{
		$query = '	SELECT id,tmc,latitude,longitude 
					FROM "NPMRDS".segment 
					WHERE 1=1 ';
		
		if($this->years != '')
			$query .= " and years = '{$this->years}'";
		if($this->quarter != '')
			$query .= " and quarter = '{$this->quarter}'";
		if($this->admin_level_2 != '')
			$query .= " and admin_level_2 = '{$this->admin_level_2}'";
		$query .= " ORDER BY id ";
		
		$query = $this->db->query($query);
		return $query->result_array();
	}
	public function get_segment($limit = "")
	{
		$query = 'SELECT * FROM "NPMRDS".segment WHERE 1=1 ';
		
		if($this->id != '')
			$query .= " and id = '{$this->id}'";
		if($this->years != '')
			$query .= " and years = '{$this->years}'";
		if($this->quarter != '')
			$query .= " and quarter = '{$this->quarter}'";
		if($this->tmc != '')
			$query .= " and tmc = '{$this->tmc}'";
		if($this->admin_level_1 != '')
			$query .= " and admin_level_1 = '{$this->admin_level_1}'";
		if($this->admin_level_2 != '')
			$query .= " and admin_level_2 = '{$this->admin_level_2}'";
		if($this->admin_level_3 != '')
			$query .= " and admin_level_3 = '{$this->admin_level_3}'";
		if($this->distance != '')
			$query .= " and distance = '{$this->distance}'";
		if($this->road_number != '')
			$query .= " and road_number = '{$this->road_number}'";
		if($this->road_name != '')
			$query .= " and road_name = '{$this->road_name}'";
		if($this->latitude != '')
			$query .= " and latitude = '{$this->latitude}'";
		if($this->longitude != '')
			$query .= " and longitude = '{$this->longitude}'";
		if($this->road_direction != '')
			$query .= " and road_direction = '{$this->road_direction}'";
		if($limit != '')
			$query .= " ORDER BY id LIMIT ".$limit;
		$query = $this->db->query($query);
		return $query->result_array();
	}
	public function get_segment_export()
	{
		$query = '	SELECT tmc, admin_level_1, admin_level_2, admin_level_3, distance, road_number, road_name, latitude, longitude, road_direction
					FROM "NPMRDS".segment WHERE 1=1 ';
		
		if($this->years != '')
			$query .= " and years = '{$this->years}'";
		if($this->quarter != '')
			$query .= " and quarter = '{$this->quarter}'";
		$query = $this->db->query($query);
		return $query->result_array();
	}
	
	public function update_segment()
	{
		$query = ' 	UPDATE "NPMRDS".segment
					SET admin_level_1=\''.$this->admin_level_1.'\',
						admin_level_2=\''.$this->admin_level_2.'\',
						admin_level_3=\''.$this->admin_level_3.'\',
						distance=\''.$this->distance.'\',
						road_number=\''.$this->road_number.'\',
						road_name=\''.$this->road_name.'\',
						latitude=\''.$this->latitude.'\',
						longitude=\''.$this->longitude.'\',
						road_direction=\''.$this->road_direction.'\'
					WHERE 	"segment".tmc =\''.$this->tmc.'\'
						and "segment".years = \''.$this->years.'\'
						and "segment".quarter = \''.$this->quarter.'\'';
		$this->db->query($query);
	}
}