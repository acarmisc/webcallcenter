<?php
require_once 'sicurezza.php';
class Manuali extends CI_Controller{
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
		//per pura stenografia
		$data=$this->data;
		$data['title']='Pharma3D-Manuali';
		$data['main_box']='manuali_view';
	
		
		$this->load->view('template',$data);
		
	}
public function test_storecheck(){
		
		$this->load->view('storecheck_form');
		
	}
	
	public function test_form(){
		$this->load->view('storecheck_form_servizi');
	}
}
