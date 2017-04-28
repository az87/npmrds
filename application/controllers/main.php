<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once  dirname(__DIR__) .'/libraries/geometry-library/PolyUtil.php';
require_once  dirname(__DIR__) .'/libraries/geometry-library/MathUtil.php';
require_once  dirname(__DIR__) .'/libraries/geometry-library/SphericalUtil.php';
require_once  dirname(__DIR__) .'/libraries/PolylineEncoder.php';

class Main extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata['id'])
			redirect(base_url().'login');
	}
	
	public function index()
	{
		$this->dashboard();
	}
	public function dashboard()
	{
		$data = $this->globalclass->get_global_data();
		
		$this->load->view('header',$data);
		$this->load->view('dashboard',$data);
		$this->load->view('footer');
	}
	public function dashboard1()
	{
		$data = $this->globalclass->get_global_data();
		
		$this->load->view('header',$data);
		$this->load->view('dashboard1',$data);
		$this->load->view('footer');
	}
	public function dashboard2()
	{
		$data = $this->globalclass->get_global_data();
		
		$this->load->view('header',$data);
		$this->load->view('dashboard2',$data);
		$this->load->view('footer');
	}
	public function search_segment()
	{
		$this->load->library('curl');
		
		$routes_points = array();
		
		$origin = $this->input->post('origin');
		$destination   = $this->input->post('destination');
		if($origin != '' && $destination != '')
		{
			$url=(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
			$url .= 'maps.googleapis.com/maps/api/directions/xml?alternatives=true&sensor=false';
			$url .= '&origin='.$origin;
			$url .= '&destination='.$destination;
			
			$directions = $this->curl->simple_get($url);
			$directions = simplexml_load_string($directions);
			if (is_object($directions)) {
				foreach ($directions->route as $route)
				{
					array_push ( $routes_points ,  PolylineEncoder::decodeValue($route->overview_polyline->points));
				}
			}
		}
		
		$this->load->model('segment_model');
		$last = $this->segment_model->get_last_segments();
		if(isset($last[0]))
		{
			$this->segment_model->years = $last[0]['years'];
			$this->segment_model->quarter = $last[0]['quarter'];
		}
		if($this->input->post('state') != "")
			$this->segment_model->admin_level_2 = $this->input->post('state');
		else
			$this->segment_model->admin_level_2 = 'Oklahoma';
			
		$segments = $this->segment_model->get_segment_points();
		$results = array();
		if(count($routes_points)>0)
		{
			for($p=0;$p<count($segments);$p++)
			{
				$exist = false;
				for($r=0;$r<count($routes_points);$r++)
				{
					$response =  \GeometryLibrary\PolyUtil::isLocationOnPath(['lat' => $segments[$p]['latitude'], 'lng' =>  $segments[$p]['longitude']],$routes_points[$r],10);
					if($response)
					{
						$exist = true;
						break;
					}
				}
				if($exist)
					array_push($results,$segments[$p]);
			}
		}
		echo json_encode($results);
	}
}
