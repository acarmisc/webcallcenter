<?php
require_once 'sicurezza.php';
class AssegnazioneFarmacie extends CI_Controller{
	private $data;
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','form','date','cookie'));
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
		$data['title']='Pharma3D-Assegnazione Advisor';
		$data['main_box']='assegnazioneFarmacie_view';
		$data['data_mainbox']['sceltaEffettuata']=false;
		$data['data_mainbox']['advisors']=Doctrine_Core::getTable('Advisor')->elencoNomi();
		if(count($data['data_mainbox']['advisors'])>1) array_unshift($data['data_mainbox']['advisors'],'');
		$data['data_mainbox']['advisorCorrente']='';
		if($this->input->post('cambiaAdvisor')){
			$contatore=0;
			while($this->input->post('advisor_nuovo'.$contatore)=='0') {
				$contatore++;
			}
			if($f=Doctrine_Core::getTable('Farmacia')->findOneById($this->input->post('farmacia_id'.$contatore))){
				$f->advisor_id=$this->input->post('advisor_nuovo'.$contatore);
				$f->save();
			}
		}
		if($this->input->post('advisor_corrente')){
			$data['data_mainbox']['advisorCorrente']=$this->input->post('advisor_corrente');
			$criteri['advisor_id']=$this->input->post('advisor_corrente');
			$risultati=Doctrine_Core::getTable('Farmacia')->ricercaFarmacia($criteri);
			
			$farmacie=array();
			$contatore=0;
		foreach($risultati as $ris){
			$farmacie[]=array(
				$ris['id'].form_hidden('advisor_corrente',$this->input->post('advisor_corrente')).form_hidden('farmacia_id'.$contatore,$ris['id']).form_hidden('contatore',$contatore),
				anchor('gestioneCliente/index/farma/'.$ris['id'],$ris['denominazione']),
				$ris['indirizzo'],
				$ris['cap'],
				$ris['localita'],
				$ris['provincia'],
				$ris['advisor'],
				form_dropdown('advisor_nuovo'.$contatore,$data['data_mainbox']['advisors'],''),
				form_submit('cambiaAdvisor','Assegna')
			);
			$contatore++;
		}
		$data['data_mainbox']['risultati']=$farmacie;
		$data['data_mainbox']['sceltaEffettuata']=true;
		}
		
		$this->load->view('template',$data);
	}
}