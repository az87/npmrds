<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Data extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata['id'])
			redirect(base_url().'login');
	}
	
	public function index()
	{
		$this->data_import();
	}
	public function data_import()
	{
		$data = $this->globalclass->get_global_data();
		$this->load->model('highway_model');
		$data["highways"] = $this->highway_model->get_highway();
		$data['error'] = '';
		$this->load->view('header',$data);
		$this->load->view('data_import',$data);
		$this->load->view('footer');
	}
	public function data_import_test()
	{
		$data = $this->globalclass->get_global_data();
		$this->load->model('highway_model');
		$data["highways"] = $this->highway_model->get_highway();
		$this->load->view('header',$data);
		$this->load->view('data_import_test',$data);
		$this->load->view('footer');
	}
	
	public function data_export()
	{
		$data = $this->globalclass->get_global_data();
		$this->load->model('highway_model');
		$data["highways"] = $this->highway_model->get_highway();
		
		$this->load->view('header',$data);
		$this->load->view('data_export',$data);
		$this->load->view('footer');
	}
	
	public function segment()
	{
		$data = $this->globalclass->get_global_data();
		
		$this->load->view('header',$data);
		$this->load->view('data_segment',$data);
		$this->load->view('footer');
	}
	
	public function speed()
	{
		$data = $this->globalclass->get_global_data();

		$this->load->model('highway_model');
		$data["highways"] = $this->highway_model->get_highway();
		
		$this->load->view('header',$data);
		$this->load->view('data_speed',$data);
		$this->load->view('footer');
	}
	
	public function segment_json()
	{
		$this->load->model('segment_model');
		$this->segment_model->years = $this->input->post("years");
		$this->segment_model->quarter = $this->input->post("quarter");
		echo json_encode($this->segment_model->get_segment($this->input->post("limit")));
	}
	public function speed_json()
	{
		$this->load->model('speed_model');
		$this->speed_model->highway = $this->input->post("highway");
		$this->speed_model->epoch = $this->input->post("epoch");
		$this->speed_model->segment = $this->input->post("segment");
		echo json_encode($this->speed_model->get_speed($this->input->post("limit"),$this->input->post("from"),$this->input->post("to")));
	}
	public function segment_count()
	{
		$this->load->model('segment_model');
		$this->segment_model->years = $this->input->post("years");
		$this->segment_model->quarter = $this->input->post("quarter");
		$result =  $this->segment_model->get_segment_count();
		echo "Total Number of Results : <b>".$result[0]["segment_count"]."</b>";
	}
	public function speed_count()
	{
		$this->load->model('speed_model');
		$this->speed_model->highway = $this->input->post("highway");
		$this->speed_model->epoch = $this->input->post("epoch");
		$this->speed_model->segment = $this->input->post("segment");
		$result =  $this->speed_model->get_speed_count($this->input->post("from"),$this->input->post("to"));
		echo "Total Number of Results : <b>".$result[0]["speed_count"]."</b>";
	}
	
	function delete_segments()
	{
		$this->load->model('segment_model');
		$this->segment_model->delete_segment($this->input->post("ids"));
	}
	function delete_speeds()
	{
		$this->load->model('speed_model');
		$this->speed_model->delete_speed($this->input->post("ids"));
	}
	
	public function import()
	{
		$config['upload_path'] = './files/';
		$config['allowed_types'] = 'csv';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			echo $this->upload->display_errors();
		}
		else
		{
			$data = $this->upload->data();
			echo 'Your file was uploaded to server successfully!<br>';
			echo '<input type="hidden" id="file_name" name="file_name" value="'.$data["file_name"].'">';
			echo '<input type="button" class="btn btn-info" class="form-control" value="Next" onclick="next_page()">';
		}
	}
	
	public function progress_data()
	{
		$this->load->model("segment_model");
		$this->load->model("speed_model");
		
		error_reporting(E_ALL);
		
		include './application/third_party/Spout/Autoloader/autoload.php';
				
		$reader = Box\Spout\Reader\ReaderFactory::create(Box\Spout\Common\Type::CSV);

		$reader->open('./files/'.$this->input->post("file"));
		
		$from = (int) $this->input->post('from');
		
		$index = 0;
		if($this->input->post('file_type') == 'S')
		{
			$to = $from + 10000;
			$years = $this->input->post('years');
			$quarter = $this->input->post('quarter');
			if($from == 1)
			{
				$this->segment_model->years = $years;
				$this->segment_model->quarter = $quarter;
				$this->segment_model->delete_segment();
			}
			$data = array();
			foreach ($reader->getSheetIterator() as $sheet) {
				foreach ($sheet->getRowIterator() as $row) {
					if($index >= $from && $index < $to)
					{
						$r = array(
								  'years' => $years ,
								  'quarter' => $quarter ,
								  'tmc' => $row[0] ,
								  'admin_level_1' => $row[1] ,
								  'admin_level_2' => $row[2],
								  'admin_level_3' => $row[3],
								  'distance' => $row[4],
								  'road_number' => $row[5],
								  'road_name' => $row[6],
								  'latitude' => $row[7],
								  'longitude' => $row[8],
								  'road_direction' => $row[9]
								);
						array_push($data,$r);
					}
					$index++;
				}
			}
			if(count($data)>0)
				$this->segment_model->add_segments($data);
		}
		else
		{
			$to = $from + 1000;
			$this->speed_model->highway = $this->input->post('highway');
			$segments = array();
			$file_type = "";
			switch ($this->input->post('file_type'))
			{
				case 'RF':$file_type = "raw_freight";
					break;
				case 'RP':$file_type = "raw_passenger";
					break;
				case 'RT':$file_type = "raw_total";
					break;
				case 'FN':$file_type = "freight";
					break;
				case 'PN':$file_type = "passenger";
					break;
				case 'TN':$file_type = "total";
					break;
				case 'FP':$file_type = "freight";
					break;
				case 'PP':$file_type = "passenger";
					break;
				case 'TP':$file_type = "total";
					break;
			}
			foreach ($reader->getSheetIterator() as $sheet) {
				foreach ($sheet->getRowIterator() as $row) {
					if($index == 0)
					{
						if($from == 1)
						{
							$this->segment_model->delete_segment_order($this->input->post('highway'),$this->input->post('file_type'));
							$orders = array();
							for($i=2;$i<count($row);$i++)
							{
								$r = array(
								  'highway' => $this->input->post('highway'),
								  'segment' => $row[$i],
								  'seg_ord' => $i-2,
								  'seg_type' => $this->input->post('file_type')
								);
								array_push($orders,$r);
							}
							if(count($orders)>0)
								$this->segment_model->add_segment_order($orders);
						}
						for($i=2;$i<count($row);$i++)
							array_push($segments,$row[$i]);
					}
					else if($index >= $from && $index < $to)
					{
						$this->speed_model->datee = $this->str_time($row[0]);
						$this->speed_model->epoch = $row[1];
						for($i=2;$i<count($row);$i++)
						{
							$this->speed_model->segment = $segments[$i-2];
							$this->speed_model->upsert_speed($file_type,($row[$i] != '')?$row[$i]:"NULL");
						}
					}
					$index++;
				}
			}
		}
		$reader->close();
		$result = array();
		$result["total"] = $index-1;
		if($to > $index)
			$result["to"] = $index;
		else
			$result["to"] = $to;
		echo json_encode($result);
	}
	
	public function str_time($string)
	{
		return date('m/d/Y', mktime(0,0,0,substr($string,-strlen($string),strlen($string)-6),substr($string, -6, 2),substr($string, -4, 4)));
	}
	public function time_str($date)
	{
		$date = new DateTime($date);
		return $date->format('mdY');
	}
	
	public function export_data()
	{		
		error_reporting(E_ALL);
		
		include './application/third_party/Spout/Autoloader/autoload.php';
		$writer = Box\Spout\Writer\WriterFactory::create(Box\Spout\Common\Type::CSV);
		
		$writer->setFieldDelimiter(',');
		$writer->setFieldEnclosure('"');
				
		if($this->input->post("file_type") == "S")
		{
			$this->load->model('segment_model');
			$this->segment_model->years = $this->input->post("years");
			$this->segment_model->quarter = $this->input->post("quarter");
			$segments =  $this->segment_model->get_segment_export();			
			$file_name = 'files/FHWA_Monthly_Static_File_'.$this->input->post("years").'Q'.$this->input->post("quarter").' '.date("Y-m-d H_i_s").'.csv';
			$writer->openToFile($file_name);
			$header = array('TMC','ADMIN_LEVEL_1','ADMIN_LEVEL_2','ADMIN_LEVEL_3','DISTANCE','ROAD_NUMBER','ROAD_NAME','LATITUDE','LONGITUDE','ROAD_DIRECTION');
			$writer->addRow($header);
			$writer->addRows($segments);
			$writer->close();
			echo $file_name;
		}
		else
		{
			$step = 10000;
			$file_type = "";
			switch ($this->input->post('file_type'))
			{
				case 'RF':
					$file_type = "raw_freight";
					$file_name = "files/".$this->input->post("highway")."_RAW_Freight ".date("Y-m-d H_i_s").".csv";
					break;
				case 'RP':
					$file_type = "raw_passenger";
					$file_name = "files/".$this->input->post("highway")."_RAW_Passenger ".date("Y-m-d H_i_s").".csv";
					break;
				case 'RT':
					$file_type = "raw_total";
					$file_name = "files/".$this->input->post("highway")."_RAW_Total ".date("Y-m-d H_i_s").".csv";
					break;
				case 'FN':
					$file_type = "freight";
					$file_name = "files/".$this->input->post("highway")."_Freight_N ".date("Y-m-d H_i_s").".csv";
					break;
				case 'PN':
					$file_type = "passenger";
					$file_name = "files/".$this->input->post("highway")."_Passenger_N ".date("Y-m-d H_i_s").".csv";
					break;
				case 'TN':
					$file_type = "total";
					$file_name = "files/".$this->input->post("highway")."_Total_N ".date("Y-m-d H_i_s").".csv";
					break;
				case 'FP':
					$file_type = "freight";
					$file_name = "files/".$this->input->post("highway")."_Freight_P ".date("Y-m-d H_i_s").".csv";
					break;
				case 'PP':
					$file_type = "passenger";
					$file_name = "files/".$this->input->post("highway")."_Passenger_P ".date("Y-m-d H_i_s").".csv";
					break;
				case 'TP':
					$file_type = "total";
					$file_name = "files/".$this->input->post("highway")."_Total_P ".date("Y-m-d H_i_s").".csv";
					break;
			}
			
			$this->load->model('speed_model');
			$this->speed_model->highway = $this->input->post("highway");
			
			$this->load->model('segment_model');
			$segments =  $this->segment_model->get_segment_order($this->input->post("highway"),$this->input->post('file_type'));
			if(count($segments) == 0)
				$segments =  $this->speed_model->get_speed_segment();
			$header = array('DATE','EPOCH');
			for($i=0;$i<count($segments);$i++)
				array_push($header, $segments[$i]["segment"]);
			
			$writer->openToFile($file_name);
			$writer->addRow($header);
			$writer->addRows($this->speed_model->get_speed_export($segments,$file_type,$this->input->post("from"),$this->input->post("to"),$step,0));
			$writer->close();
			
			$index = $step;
			$results =  $this->speed_model->get_speed_export_count($file_type,$this->input->post("from"),$this->input->post("to"));
			while ( $index <= $results[0]["speed_count"])
			{
				$writer->openToFile($file_name);
				$writer->addRows($this->speed_model->get_speed_export($segments,$file_type,$this->input->post("from"),$this->input->post("to"),$step,$index));
				$writer->close();
				$index = $index + $step;
			}
			echo $file_name;
		}
	}
}