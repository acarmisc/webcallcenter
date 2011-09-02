<?php
require_once 'sicurezza.php';
class Storecheck extends CI_Controller{
	private $data;
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','form','date'));
		$this->load->library(array('session','table'));
		//se l'utente non risulta loggato, viene reindirizzato sulla home (con form di login)
		Sicurezza::verificaUtente(true);
		//Imposto valori predefiniti
		$this->data['stylesheet']='css/default.css';
		$this->data['mostra_form_login']=false;
		$this->data['login_fallito']=false;
		$this->data['info_utente']=Utente_Corrente::informazioni();
		$this->data['nome_completo']=$this->data['info_utente']['nome_completo'];
		$this->data['data_toolbar']['voci_toolbar']=Sicurezza::vociToolbar();	
	}
	
	public function index(){	
		$data=$this->data;
		$data['title']='Pharma3D-Storecheck';
		$data['main_box']='storecheck_form';
		//Se non è impostato farmacia_id, si viene reindirizzati sulla home
		if(! $this->input->post('farmacia_id')){
			redirect('home','refresh');
		}
		$farmacia_id=$this->input->post('farmacia_id');
		//si recuperano i dati relativi alla farmacia e allo storecheck
		$farmacia=Doctrine_Core::getTable('Farmacia')->findOneById($farmacia_id);
		$storecheck=Doctrine_core::getTable('Storecheck')->findOneByFarmacia_id($farmacia_id);
		
		
		$this->load->view('template',$data);
	}