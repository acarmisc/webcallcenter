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
		$var_get=$uri_farma=$this->uri->uri_to_assoc(3);
		if(isset($var_get['cliente'])){
			Sicurezza::verificaUtente(false);
		}else{
			Sicurezza::verificaUtente(true);
		}
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
			if($this->input->post('csv_attivita')){
				$risultati=Doctrine_Core::getTable('Farmacia')->getAttivitaPerFarma_Adv($data['data_mainbox']['advisorCorrente']);
			}else{
				$criteri['advisor_id']=$data['data_mainbox']['advisorCorrente'];
				$risultati=Doctrine_Core::getTable('Farmacia')->ricercaFarmacia($criteri);
			}
			if((! $this->input->post('csv_export')) && (! $this->input->post('csv_attivita'))){
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
			}
			if($this->input->post('csv_export')) {
				$righe="cod_cliente;denominazione;indirizzo;localita;provincia;agente;advisor;stato;flusso_lavoro\n";
				
					foreach($risultati as $cli){
						$righe.=$cli['id'].";".$cli['denominazione'].";".$cli['indirizzo'].";".$cli['localita'].";".
									$cli['provincia'].";".$cli['agente'].";".$cli['advisor'].";".$cli['stato'].";".$cli['flusso_lavoro']."\n";	
					}
				
				force_download('esportazione.csv',$righe);
			}else if($this->input->post('csv_attivita')){
				$righe="cod_cliente;denominazione;indirizzo;localita;provincia;agente;advisor;stato;flusso_lavoro;giorno;inizio;attivita;campagna;stato_attivita;chiusura\n";
				
					foreach($risultati as $cli){
						$righe.=$cli['f_id'].";".$cli['f_denominazione'].";".$cli['f_indirizzo'].";".$cli['f_localita'].";".
									$cli['f_provincia'].";".$cli['utag_agente'].";".$cli['utad_advisor'].";".$cli['f_stato'].";".$cli['f_flusso_lavoro'].";".
									$cli['at_giorno'].";".$cli['at_ora_inizio'].";".$cli['ta_nome'].";".($cli['te_campagna']?$cli['te_campagna']:'').";".$cli['at_stato_attivita'].";".$cli['at_chiusura']."\n";	
					}
				
				force_download('esportazione.csv',$righe);
			}else{
				$dati=array();
				foreach($ripartiti as $nome=>$gruppo){
					$gr=strtoupper(substr($nome,0,1)).substr($nome,1);
					$righe[$gr]=array();
					foreach($gruppo as $cli){
						$righe[$gr][]=array(
						array('data'=>$cli['id'],'onClick'=>'visualizza("'.$cli['id'].'")'),
						array('data'=>$cli['denominazione'],'onClick'=>'visualizza("'.$cli['id'].'")'),
						array('data'=>$cli['indirizzo'],'onClick'=>'visualizza("'.$cli['id'].'")'),
						array('data'=>$cli['localita'],'onClick'=>'visualizza("'.$cli['id'].'")'),
						array('data'=>$cli['provincia'],'onClick'=>'visualizza("'.$cli['id'].'")'),
						array('data'=>$cli['agente'],'onClick'=>'visualizza("'.$cli['id'].'")'),
						array('data'=>$cli['advisor'],'onClick'=>'visualizza("'.$cli['id'].'")'),
						array('data'=>$cli['flusso_lavoro'],'onClick'=>'visualizza("'.$cli['id'].'")')
						);
						if(true){
							$righe[$gr][]=array(
								array('data'=>nbs(),'colspan'=>7,'id'=>$cli['id'],'style'=>'display: none;')
							);
						}	
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
	
	public function eventiCliente(){
		$cliente=$uri_farma=$this->uri->uri_to_assoc(3);
		$cliente=$cliente['cliente'];
		$eventi=Doctrine_Core::getTable('Attivita')->getAttivitaFarmacia($cliente);
		$data['intestazioneTabella']=array('giorno','inizio','attivita','campagna','stato','chiusura');
		$righe=array();
		foreach($eventi as $evento){
			$righe[]=array(
				array('data'=>$evento['giorno']),
				array('data'=>$evento['ora_inizio']),
				array('data'=>$evento['nome']),
				array('data'=>$evento['campagna']?$evento['campagna']:''),
				array('data'=>$evento['stato']),
				array('data'=>$evento['data_chiusura']),
			);
		}
		$data['righe']=$righe;
		$this->load->view('reportAdvisor_Farmacie_1_view',$data);
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