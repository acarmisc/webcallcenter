<?php
require_once 'sicurezza.php';
class Reportattivita extends CI_Controller{
	private $risposta_vuota;
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','html','form','date','cookie'));
		$this->load->library(array('session','table','calendar'));
		//se l'utente non risulta loggato, viene restituita una risposta vuota)
		if(!Sicurezza::verificaUtente(false)){
			$this->risposta_vuota=true;
		}
		$this->data['info_utente']=Utente_Corrente::informazioni();
		
	}
	
	public function index(){
	//se la richiesta proviene da un utente non verificabile...si genera una risposta vuota.
		if($this->risposta_vuota){
			echo '<p></p>';
			return;
		}
		//recupero le informazioni dall'URI
		$richiesta=$this->uri->uri_to_assoc(3);	
		if(!$richiesta['idAttivita']){
			echo '<p></p>';
			return;
		}
		$farmacia_id=$richiesta['farma'];
		//recupero l'attività, restituendo risposta vuota se non la trovo
		if(! $attivita=Doctrine_Core::getTable('Attivita')->findOneById($richiesta['idAttivita'])){
			echo '<p></p>';
			return;
		}else{
			$tipoevento_id=$attivita->tipoevento_id;
		}
		//Se non è stato specificato il tipo di evento restituisco una risposta vuota
		if($tipoevento_id<1 || $tipoevento_id>5) {
			echo '<p></p>';
			return;
		}
		$data['tipoattivita_id']=$attivita->tipoattivita_id;
		if($data['tipoattivita_id']!=2 && $data['tipoattivita_id']!=3){
			echo '<p></p>';
			return;
		}
		$report['farmacia_id']=$farmacia_id;
		switch($attivita->tipoattivita_id){
			case 2:
				$report_db=Doctrine_Core::getTable('Reportpreevento')->findOneByAttivita_id($richiesta['idAttivita']);
				if($report_db){
					$report['attivita_id']=$report_db->attivita_id;
					$report['leaf_edu']=$report_db->leaf_edu;
					$report['leaf_prodotto']=$report_db->leaf_prodotto;
					$report['locandine']=$report_db->locandine;
					$report['espositore']=$report_db->espositore;
					$report['totem']=$report_db->totem;
					$report['ticket']=$report_db->ticket;
					$report['appuntamenti']=$report_db->numappuntamenti;
					$report['partecipazione_farmacista']=$report_db->partecipazione;
					$report['note']=$report_db->note;
				}else{
					$report['attivita_id']=$richiesta['idAttivita'];
					$report['leaf_edu']=0;
					$report['leaf_prodotto']=0;
					$report['locandine']=0;
					$report['espositore']=0;
					$report['totem']=0;
					$report['ticket']=0;
					$report['appuntamenti']=0;
					$report['partecipazione_farmacista']='';
					$report['note']='';
				}
				$data['gradi_partecipazione']=Doctrine_Core::getTable('Reportpreevento')->getPartecipazione();
				array_unshift($data['gradi_partecipazione'],'');
				break;
			case 3:
				$report_db=Doctrine_Core::getTable('Reportevento')->findOneByAttivita_id($richiesta['idAttivita']);
				if($report_db){
					$report['attivita_id']=$report_db->attivita_id;
					$report['contatti_appuntamento']=$report_db->contatti_appuntamento;
					$report['contatti_noappuntamento']=$report_db->contatti_noappuntamento;
					$report['sellout']=$report_db->sellout;
					$report['soddisfazione_farmacista']=$report_db->soddisfazione_farmacista;
					$report['note']=$report_db->note;
					$report['preevento']=$report_db->preevento;
					$report['store_magazzino']=$report_db->store_magazzino;
					if(strlen($report_db->dati_questionari)){
						$report['dati_questionari']=explode(";",$report_db->dati_questionari);
					}else{
						if($tipoevento_id==2){
							$report['dati_questionari']=array(0,0,0,0);
						}else{
							$report['dati_questionari']=array(0,0,0);
						}
					}
					
				}else{
					$report['attivita_id']=$richiesta['idAttivita'];
					$report['contatti_appuntamento']=0;
					$report['contatti_noappuntamento']=0;
					$report['sellout']=0;
					$report['soddisfazione_farmacista']='';
					$report['note']='';
					$report['preevento']=false;
					$report['store_magazzino']=0;
					if($tipoevento_id==2){
						$report['dati_questionari']=array(0,0,0,0);
					}else{
						$report['dati_questionari']=array(0,0,0);
					}
				}
				$data['gradi_soddisfazione']=Doctrine_Core::getTable('Reportevento')->getGradiSoddisfazione();
				array_unshift($data['gradi_soddisfazione'],'');
		}
		$report['tipoevento_id']=$tipoevento_id;
		$data['report']=$report;
		$this->load->view('gestioneCliente_reportattivita_view',$data);
		
	}
	
	public function save(){
		$attivita=Doctrine_Core::getTable('Attivita')->findOneById($this->input->post('attivita_id'));
		switch($attivita->tipoattivita_id){
			case 2:
				$report_db=Doctrine_Core::getTable('Reportpreevento')->findOneByAttivita_id($this->input->post('attivita_id'));
				if(!$report_db){
					$report_db=new Reportpreevento();
				}
				$report_db->attivita_id=$this->input->post('attivita_id');
				$report_db->leaf_edu=$this->input->post('leaf_edu')=='true'?1:0;
				$report_db->leaf_prodotto=$this->input->post('leaf_prodotto')=='true'?1:0;
				$report_db->locandine=$this->input->post('locandine')=='true'?1:0;
				$report_db->espositore=	$this->input->post('espositore')=='true'?1:0;
				$report_db->totem=$this->input->post('totem')=='true'?1:0;
				$report_db->ticket=$this->input->post('ticket')=='true'?1:0;
				$report_db->numappuntamenti=$this->input->post('appuntamenti');
				$report_db->partecipazione=$this->input->post('partecipazione_farmacista');
				$report_db->note=$this->input->post('note');
				break;
			case 3:
				$report_db=Doctrine_Core::getTable('Reportevento')->findOneByAttivita_id($this->input->post('attivita_id'));
				if(!$report_db){
					$report_db=new Reportevento();
				}
				$report_db->attivita_id=$this->input->post('attivita_id');
				$report_db->contatti_appuntamento=$this->input->post('contatti_appuntamento');
				$report_db->contatti_noappuntamento=$this->input->post('contatti_noappuntamento');
				$report_db->sellout=$this->input->post('sellout');
				$report_db->soddisfazione_farmacista=$this->input->post('soddisfazione_farmacista');
				$report_db->note=$this->input->post('note');
				$report_db->preevento=$this->input->post('preevento')=='true'?true:false;
				$report_db->store_magazzino=$this->input->post('store_magazzino');
				$report_db->dati_questionari='';
				foreach($this->input->post('dati_questionari') as $dato){
					$report_db->dati_questionari.=$dato.";";
				}
				$report_db->dati_questionari=trim($report_db->dati_questionari,";");
				break;
		}
		$report_db->save();
	}
	

}