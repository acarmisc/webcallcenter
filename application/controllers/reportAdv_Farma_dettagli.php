<?php
require_once 'sicurezza.php';
class ReportAdvisor_Farmacie extends CI_Controller{
	private $data;
	//variabile utilizzata da filtraPerStato() per filtrare i record dei clienti in base al loro stato
	private $statoAnalizzato;
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','form','date','cookie','download'));
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
		$data['title']='Pharma3D-Report Clienti per Advisor';
		$data['main_box']='reportAdvisor_Farmacie_view';
		$data['data_mainbox']['sceltaEffettuata']=false;
		$data['data_mainbox']['advisors']=Doctrine_Core::getTable('Advisor')->elencoNomi();
		if(count($data['data_mainbox']['advisors'])>1) array_unshift($data['data_mainbox']['advisors'],'');
		$data['data_mainbox']['advisorCorrente']='';
		if($this->input->post('advisor_corrente')){
			$data['data_mainbox']['sceltaEffettuata']=true;
			$data['data_mainbox']['advisorCorrente']=$this->input->post('advisor_corrente');
		}else{
			if($this->input->post('advisor_id_txt')){
				$data['data_mainbox']['sceltaEffettuata']=true;
				$data['data_mainbox']['advisorCorrente']=$this->input->post('advisor_id_txt');				
			}
		}
		if($data['data_mainbox']['sceltaEffettuata']){
			$criteri['advisor_id']=$data['data_mainbox']['advisorCorrente'];
			$risultati=Doctrine_Core::getTable('Farmacia')->ricercaFarmacia($criteri);
			//ripartisco i risultati fra i vari stati possibili dei clienti trovati
			$stati=Doctrine_Core::getTable('Farmacia')->getStatiCliente();
			$ripartiti=array();
			foreach($stati as $stato){
				$this->statoAnalizzato=$stato;
				$r=array_filter($risultati,array($this,'filtraPerStato'));
				if(count($r)>0){
					$ripartiti[$stato]=$r;
				}
			}
			if($this->input->post('csv_export')) {
				$righe="cod_cliente;denominazione;indirizzo;localita;provincia;agente;advisor;stato;flusso_lavoro\n";
				foreach($ripartiti as $gruppo){
					foreach($gruppo as $cli){
						$righe.=$cli['id'].";".$cli['denominazione'].";".$cli['indirizzo'].";".$cli['localita'].";".
									$cli['provincia'].";".$cli['agente'].";".$cli['advisor'].";".$cli['stato'].";".$cli['flusso_lavoro']."\n";	
					}
				}
				force_download('esportazione.csv',$righe);
			}else{
				$dati=array();
				foreach($ripartiti as $nome=>$gruppo){
					$gr=strtoupper(substr($nome,0,1)).substr($nome,1);
					$righe[$gr]=array();
					foreach($gruppo as $cli){
						$righe[$gr][]=array(
						array('data'=>$cli['id']),
						array('data'=>$cli['denominazione']),
						array('data'=>$cli['indirizzo']),
						array('data'=>$cli['localita']),
						array('data'=>$cli['provincia']),
						array('data'=>$cli['agente']),
						array('data'=>$cli['advisor']),
						array('data'=>$cli['flusso_lavoro'])
						);	
					}
					
				}	
				$data['data_mainbox']['risultati']=$righe;
				$data['data_mainbox']['intestazione_tabelle']=array('cod. Cl.',
																'Denominazione',
																'Indirizzo',
																'Città',
																'Prov.',
																'Agente',
																'Advisor',
																'Tipo attivazione');
			}
			
		}
		$this->load->view('template',$data);
	}
	
	//restituisce true se l'array $var ha il valore con chiave stato pari al valore di $this->statoAnalizzato
	private function filtraPerStato($var){
		if($var['stato']==$this->statoAnalizzato){
			return true;
		}else{
			return false;
		}
	}
	

}