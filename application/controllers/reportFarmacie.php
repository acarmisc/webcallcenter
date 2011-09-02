<?php
require_once 'sicurezza.php';
class ReportFarmacie extends CI_Controller{
	private $data;
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','form','cookie','download'));
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
		$data['title']='Pharma3D-Report';
		$data['main_box']='reportFarmacie_view';
		$data['data_mainbox']['titolo']='Report Attività Farmacie';
		$dati=Doctrine_Core::getTable('Attivita')->getEventiFuturi();
		$data['data_mainbox']['pulsante_csv']='csv_attivita';
		if($this->input->post('csv_attivita')){
			$exp="cod_cliente;denominazione;indirizzo;cap;località;provincia;telefono;fax;email;isf;agente;advisor;data;evento;\n";
			foreach($dati as $riga){
				$exp.=$riga['id'].";".$riga['denominazione'].";".$riga['indirizzo'].";".$riga['cap'].";".$riga['localita'].";".$riga['provincia'].
				";".$riga['numtel'].";".$riga['numfax'].";".$riga['email'].";".$riga['isf'].";".$riga['agente'].";".$riga['advisor'].
				";".$riga['giorno'].";".$riga['evento'].";\n";
			}
			force_download('esportazione.csv',$exp);
		}else{
			$data['data_mainbox']['intestazione_tabella']=array('Cod_Cliente','Cliente','Agente','Advisor','Data','Evento');
			$righe=array();
			foreach($dati as $riga){
				$righe[]=array(
					array('data'=>$riga['id']),
					array('data'=>$riga['denominazione']),
					array('data'=>$riga['agente']),
					array('data'=>$riga['advisor']),
					array('data'=>$riga['giorno']),
					array('data'=>$riga['evento']),
				);
			}
			$data['data_mainbox']['statistiche']=$righe;
			$this->load->view('template',$data);
		}
	}
	
}