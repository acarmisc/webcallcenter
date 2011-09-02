<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Storeman extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		if(isset($_GET['farmacia_id'])){
			$this->session->set_userdata('current_farmacia', $_GET['farmacia_id']);
		}elseif($this->session->userdata('current_farmacia')==''){
			$this->session->set_userdata('current_farmacia', 0);
		}
		$this->load->view('storeman/index');
	}
	
	function saveG(){
		$this->db->insert('store_globale', $_POST);
		$this->load->view('storeman/panoramica');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */