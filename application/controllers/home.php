<?php
require_once 'sicurezza.php';
class Home extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','cookie'));
		$this->load->library('session');
	}
	public function index(){
		$data['title']='Pharma3D-Home';
		$data['stylesheet']='css/default.css';
		$data['mostra_form_login']=true;
		//Imposto valori predefiniti
		$data['login_fallito']=false;
		$data['nome_completo']='';
		
		//Se l'utente si sta loggando,
		if($this->input->post('submit')){
			//Se ha inviato credenziali valide
			if(Utente_Corrente::login($this->input->post('username'),$this->input->post('password'))){
				$data['login_fallito']=false;

			//se, invece ci ha provato...
			}else{
				$data['login_fallito']=true;
			}
		}
		//Se l'utente è loggato, eventualmente perché ha appena effettuato il login viene reindirizzato
		if(Sicurezza::verificaUtente(false)){
				$data['main_box']=Sicurezza::attivitaIniziale();
		}	
		$data['main_box']='welcome_box';			
		$this->load->view('template',$data);
	}
	public function logout(){
		$data['title']='Pharma3D-Home';
		$data['stylesheet']='css/default.css';
		$data['mostra_form_login']=true;
		//Imposto valori predefiniti
		$data['login_fallito']=false;
		$data['nome_completo']='';
		$data['main_box']='welcome_box';
		$this->session->sess_destroy();
		delete_cookie('ci_session');
		$this->load->view('template',$data);
	}
}