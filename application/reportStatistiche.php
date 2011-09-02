<?php
require_once 'sicurezza.php';
class ReportStatistiche extends CI_Controller{
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
		$data['main_box']='reportStatistiche_view';
		$data['data_mainbox']['titolo']='Report';
		$statistiche=array();
		$data['data_mainbox']['intestazione_tabella']=array('Clienti','Attivi','Eventi','Confermati','Richiesti agente','Attivati ADV');
		if($this->input->post('provincialiStatisticheForm') || $this->input->post('csv_province')){
			array_unshift($data['data_mainbox']['intestazione_tabella'],'Provincia');
			$dati=Doctrine_Core::getTable('Provincia')->statisticheProvinciali();
			$data['data_mainbox']['pulsante_csv']='csv_province';
		}else if($this->input->post('agenteStatisticheForm') || $this->input->post('csv_agente')){
			array_unshift($data['data_mainbox']['intestazione_tabella'],'Agente');
			$dati=Doctrine_Core::getTable('Agente')->statistiche();
			$data['data_mainbox']['pulsante_csv']='csv_agente';
		}else if($this->input->post('advisorStatisticheForm') || $this->input->post('csv_advisor')){
			array_unshift($data['data_mainbox']['intestazione_tabella'],'Advisor');
			$dati=Doctrine_Core::getTable('Advisor')->statistiche();
			$data['data_mainbox']['pulsante_csv']='csv_advisor';	
		}else if($this->input->post('microbrickStatisticheForm') || $this->input->post('csv_microbrick')){
			array_unshift($data['data_mainbox']['intestazione_tabella'],'Microbrick');
			$dati=Doctrine_Core::getTable('Microbrick')->statistiche();
			$data['data_mainbox']['pulsante_csv']='csv_microbrick';
		}else{
			//recupero le statistiche
			$data['data_mainbox']['pulsante_csv']='csv_regioni';
			array_unshift($data['data_mainbox']['intestazione_tabella'],'Regione');
			$nazionali=Doctrine_Core::getTable('Regione')->statisticheNazionali();
			$dati=Doctrine_Core::getTable('Regione')->statisticheRegionali();
			//array amichevole per la view
			$testo="Totale: ".($nazionali['attive_ag']+$nazionali['attive_adv'])."<br>  Da agente: ".$nazionali['attive_ag']
				   ."<br>  Da advisor: ".$nazionali['attive_adv'];

			$statistiche[]=array(
				array('data'=>'Italia','rowspan'=>5),
				array('data'=>$nazionali['numero'],'rowspan'=>5),
				array('data'=>$testo,'rowspan'=>5),
				array('data'=>'Intestino'),
				array('data'=>array_key_exists('numero1',$nazionali) && is_numeric($nazionali['numero1'])?$nazionali['numero1']:0),
				array('data'=>array_key_exists('numero_ag_1',$nazionali) && is_numeric($nazionali['numero_ag_1'])?$nazionali['numero_ag_1']:0),
				array('data'=>array_key_exists('numero_attadv1',$nazionali) && is_numeric($nazionali['numero_attadv1'])?$nazionali['numero_attadv1']:0)
			);
			
			$statistiche[]=array(
				array('data'=>'Gambe'),
				array('data'=>array_key_exists('numero2',$nazionali) && is_numeric($nazionali['numero2'])?$nazionali['numero2']:0),	
				array('data'=>array_key_exists('numero_ag_2',$nazionali) && is_numeric($nazionali['numero_ag_2'])?$nazionali['numero_ag_2']:0),
				array('data'=>array_key_exists('numero_attadv2',$nazionali) && is_numeric($nazionali['numero_attadv2'])?$nazionali['numero_attadv2']:0)	
			);
			$statistiche[]=array(
				array('data'=>'Naso'),
				array('data'=>array_key_exists('numero3',$nazionali) && is_numeric($nazionali['numero3'])?$nazionali['numero3']:0),
				array('data'=>array_key_exists('numero_ag_3',$nazionali) && is_numeric($nazionali['numero_ag_3'])?$nazionali['numero_ag_3']:0),
				array('data'=>array_key_exists('numero_attadv3',$nazionali) && is_numeric($nazionali['numero_attadv3'])?$nazionali['numero_attadv3']:0)		
			);		
			$statistiche[]=array(
				array('data'=>'Stomaco'),
				array('data'=>array_key_exists('numero4',$nazionali) && is_numeric($nazionali['numero4'])?$nazionali['numero4']:0),
				array('data'=>array_key_exists('numero_ag_4',$nazionali) && is_numeric($nazionali['numero_ag_4'])?$nazionali['numero_ag_4']:0),
				array('data'=>array_key_exists('numero_attadv4',$nazionali) && is_numeric($nazionali['numero_attadv4'])?$nazionali['numero_attadv4']:0)		
			);
			$statistiche[]=array(
				array('data'=>'Gola'),
				array('data'=>array_key_exists('numero5',$nazionali) && is_numeric($nazionali['numero5'])?$nazionali['numero5']:0),
				array('data'=>array_key_exists('numero_ag_5',$nazionali) && is_numeric($nazionali['numero_ag_5'])?$nazionali['numero_ag_5']:0),
				array('data'=>array_key_exists('numero_attadv5',$nazionali) && is_numeric($nazionali['numero_attadv5'])?$nazionali['numero_attadv5']:0)		
			);
		}
		foreach($dati as $reg){
			$testo="Totale: ".($reg['attive_ag']+$reg['attive_adv'])."<br>  Da agente: ".$reg['attive_ag']
				   ."<br>  Da advisor: ".$reg['attive_adv'];
			$statistiche[]=array(
				array('data'=>$reg['nome'],'rowspan'=>5),
				array('data'=>$reg['numero'],'rowspan'=>5),
				array('data'=>$testo,'rowspan'=>5),
				array('data'=>'Intestino'),
				array('data'=>array_key_exists('numero1',$reg) && is_numeric($reg['numero1'])?$reg['numero1']:0),
				array('data'=>array_key_exists('numero_ag_1',$reg) && is_numeric($reg['numero1'])?$reg['numero_ag_1']:0),
				array('data'=>array_key_exists('numero_attadv1',$reg) && is_numeric($reg['numero_attadv1'])?$reg['numero_attadv1']:0)
			);
			$statistiche[]=array(
				array('data'=>'Gambe'),
				array('data'=>array_key_exists('numero2',$reg) && is_numeric($reg['numero2'])?$reg['numero2']:0),
				array('data'=>array_key_exists('numero_ag_2',$reg) && is_numeric($reg['numero_ag_2'])?$reg['numero_ag_2']:0),
				array('data'=>array_key_exists('numero_attadv2',$reg) && is_numeric($reg['numero_attadv2'])?$reg['numero_attadv2']:0)		
			);
			$statistiche[]=array(
				array('data'=>'Naso'),
				array('data'=>array_key_exists('numero3',$reg) && is_numeric($reg['numero3'])?$reg['numero2']:0),
				array('data'=>array_key_exists('numero_ag_3',$reg) && is_numeric($reg['numero_ag_3'])?$reg['numero_ag_3']:0),
				array('data'=>array_key_exists('numero_attadv3',$reg) && is_numeric($reg['numero_attadv3'])?$reg['numero_attadv3']:0)		
			);		
			$statistiche[]=array(
				array('data'=>'Stomaco'),
				array('data'=>array_key_exists('numero4',$reg) && is_numeric($reg['numero4'])?$reg['numero4']:0),
				array('data'=>array_key_exists('numero_ag_4',$reg) && is_numeric($reg['numero_ag_4'])?$reg['numero_ag_4']:0),
				array('data'=>array_key_exists('numero_attadv4',$reg) && is_numeric($reg['numero_attadv4'])?$reg['numero_attadv4']:0)		
			);
			$statistiche[]=array(
				array('data'=>'Gola'),
				array('data'=>array_key_exists('numero5',$reg) && is_numeric($reg['numero5'])?$reg['numero5']:0),
				array('data'=>array_key_exists('numero_ag_5',$reg) && is_numeric($reg['numero_ag_5'])?$reg['numero_ag_5']:0),
				array('data'=>array_key_exists('numero_attadv5',$reg) && is_numeric($reg['numero_attadv5'])?$reg['numero_attadv5']:0)		
			);
		}
		
		if($this->input->post('csv_agente')|| $this->input->post('csv_province')||$this->input->post('csv_regioni') 
			|| $this->input->post('csv_advisor') || $this->input->post('csv_microbrick')){
			$exp="Clienti;Attivi;Attivati_ag;Attivati_adv;Intestino_confermati;Intestino_agente;Intestino_att_adv;Gambe_confermati;Gambe_agente;Gambe_att_adv;Naso_confermati;Naso_agente;Naso_att_adv;Stomaco_confermati;Stomaco_agente;Stomaco_att_adv;Gola_confermati;Gola_agente;Gola_att_adv";
			if($this->input->post('csv_agente')){
				$exp="Agente;".$exp;
				$exp.="\n";
				}
			if($this->input->post('csv_advisor')){
				$exp="Advisor;".$exp;
				$exp.="\n";
				}	
				
			if($this->input->post('csv_province')){
				$exp="Provincia;".$exp;
				$exp.="\n";
			}
			if($this->input->post('csv_microbrick')){
				$exp="Microbrick;".$exp;
				$exp.="\n";
			}
			if($this->input->post('csv_regioni')){
				$exp="Regione;".$exp;
				$exp.="\n";
				$exp.="Italia;".$nazionali['numero'].";".($nazionali['attive_ag']+$nazionali['attive_adv']).";".
					$nazionali['attive_ag'].";".$nazionali['attive_adv'].";".$nazionali['numero1'].";".$nazionali['numero_ag_1'].";".$nazionali['numero_attadv1'].
					";".$nazionali['numero2'].";".$nazionali['numero_ag_2'].";".$nazionali['numero_attadv2'].";".$nazionali['numero3'].";".$nazionali['numero_ag_3'].";".$nazionali['numero_attadv3']
					.";".$nazionali['numero4'].";".$nazionali['numero_ag_4'].";".$nazionali['numero_attadv4'].";".$nazionali['numero5'].";".$nazionali['numero_ag_5'].";".$nazionali['numero_attadv5']."\n";
			}
			foreach($dati as $riga){
				$exp.=$riga['nome'].";".$riga['numero'].";".($riga['attive_ag']+$riga['attive_adv']).";".
					$riga['attive_ag'].";".$riga['attive_adv'].";".$riga['numero1'].";".$riga['numero_ag_1'].";".$riga['numero_attadv1'].
					";".$riga['numero2'].";".$riga['numero_ag_2'].";".$riga['numero_attadv2'].";".$riga['numero3'].";".$riga['numero_ag_3'].";".$riga['numero_attadv3']
					.";".$riga['numero4'].";".$riga['numero_ag_4'].";".$riga['numero_attadv4'].";".$riga['numero5'].";".$riga['numero_ag_5'].";".$riga['numero_attadv5']."\n";
			}
			force_download('esportazione.csv',$exp);
		}else{
			$data['data_mainbox']['statistiche']=$statistiche;
			$this->load->view('template',$data);
		}
		
	}
	
	
	
	
}