<?php
require_once 'sicurezza.php';
class userProfile extends CI_Controller{
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
		$data['title']='Pharma3D-Profilo Personale';
		$data['main_box']='userProfile_view';
		$data['data_mainbox']['utente']=Utente_Corrente::informazioni();
		$data['data_mainbox']['stato_profilo']='';
		$data['data_mainbox']['stato_password']='';
		if($this->input->post('inviaDati_utente')){
			$u=Doctrine_Core::getTable('utente')->findOneById($data['info_utente']['id']);
			if($this->input->post('email')!=$u->email ||$this->input->post('numcel')!=$u->numcel ||$this->input->post('numtel')!=$u->numtel){
				$u->email=$this->input->post('email');
				$u->numtel=$this->input->post('numtel');
				$u->numcel=$this->input->post('numcel');
				$u->save();
				$data['data_mainbox']['stato_profilo']='Dati salvati correttamente.';
				Sicurezza::aggiornaUtente();
			}
		}
		if($this->input->post('inviaPassword')){
			if($this->input->post('new_password')!=$this->input->post('control_password')){
				$data['data_mainbox']['stato_password']='Attenzione! La password e la verifica non coincidono. Password non aggiornata!';
			}else{
				$u=Doctrine_Core::getTable('utente')->findOneById($data['info_utente']['id']);
				$u_n=new Utente();
				$u_n->password=$this->input->post('old_password');
				if($u->password==$u_n->password){
					$u->password=$this->input->post('new_password');
					$u->save();
					unset($u_n);
					$data['data_mainbox']['stato_password']='Password aggiornata correttamente.';
					Sicurezza::aggiornaUtente();
				}else{
					$data['data_mainbox']['stato_password']='Password non aggiornata: vecchia password errata!';
				}
			}
			
		}
		$data['data_mainbox']['utente']=Utente_Corrente::informazioni();	
		$this->load->view('template',$data);
		
	}
	
}

?>