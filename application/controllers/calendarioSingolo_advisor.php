<?php
require_once 'sicurezza.php';
class CalendarioSingolo_advisor extends CI_Controller {
	private $risposta_vuota=false;
	
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
		//se non si dispone di tutti e tre i dati, genero una risposta vuota
		if(!($richiesta['cod_advisor'] && $richiesta['quale'] && $richiesta['corrente'])){
			echo '<p></p>';
		return;
		}
		//se $quale non vale 'next', 'curr' o 'prev', genero una risposta vuota
		if($richiesta['quale']!='next' && $richiesta['quale']!='curr' && $richiesta['quale']!='prev'){
			echo '<p></p>';
		return;
		}
		//se $corrente non è un'indicazione di un mese in formato yyyymm, genero una risposta vuota
		$anno_corrente=substr($richiesta['corrente'],0,4);
		$mese_corrente=substr($richiesta['corrente'],4,2);
		//anno deve essere un numero compreso fra 2000 e 2100, mese deve essere un numero compreso fra 1 e 12 (con un eventuale 0 iniziale)
		if(! is_numeric($anno_corrente) && ($anno_corrente<2000 || $anno_corrente>2100)){
			echo '<p></p>';
		return;			
		}
		if(! is_numeric($mese_corrente) || ltrim($mese_corrente,'0') < 1 || ltrim($mese_corrente,0) > 12){
			echo '<p></p>';
		return;			
		}
		//si determinano le date del primo e dell'ultimo giorno del mese coinvolto
		$date_mese=$this->calcolaDate($richiesta['quale'],$anno_corrente,$mese_corrente);
		$inizio=$date_mese['anno']."-".$date_mese['mese'].'-01';
		$fine=$date_mese['anno']."-".$date_mese['mese'].'-'.$date_mese['ultimo'];
		$date=Doctrine_Core::getTable('Attivita')->
			getGiorniAttivitaAdvisorPeriodo($richiesta['cod_advisor'],$inizio,$fine);

		/*
		 * Costruisco un array di eventi i cui componenti sono giorno(non data)=>giorno. Il template del calendario
		 * provvederà ad inserire la chiamata a funzione Javascript per caricare gli eventi del giorno.
		 */ 
		$giorni=array();
		foreach($date as $giorno){
			if(! is_null($giorno['giorno']) && $giorno['giorno']!=''){
				$valore=ltrim(substr($giorno['giorno'],8,2),0);
				$occupazione=$giorno['occupazione']>100?100:$giorno['occupazione'];
				if($valore!='00') $giorni[$valore]='<div class="p'.$occupazione.'"><a href="#" onClick="getAttivita(\''.str_replace('-','',$giorno['giorno']).'\',\''.$richiesta['cod_advisor'].'\')">';
			}
		}

		$config_calendar['template'] = '{table_open}<table class="calendar">{/table_open}
   			{week_day_cell}<th class="day_header">{week_day}</th>{/week_day_cell}
    		{cal_cell_content}{content}{day}</a></div>{/cal_cell_content}
    		{cal_cell_content_today}<div class="today">{content}{day}</a></div></div>{/cal_cell_content_today}
    		{cal_cell_no_content}<span class="day_listing">{day}</span>&nbsp;{/cal_cell_no_content}
    		{cal_cell_no_content_today}<div class="today"><span class="day_listing">{day}</span></div>{/cal_cell_no_content_today}';
		if($richiesta['quale']!='curr'){
			$config_calendar['show_next_prev']=true;
			$config_calendar['template'].=' {heading_previous_cell}<th><a href="#" onClick="caricaCalendari(\'prev\',\''.$date_mese['anno'].$date_mese['mese'].'\',\''.$richiesta['cod_advisor'].'\',\''.$richiesta['posizione'].'\')">&lt;&lt;</a></th>{/heading_previous_cell}';
			$config_calendar['template'].=' {heading_next_cell}<th><a href="#" onClick="caricaCalendari(\'next\',\''.$date_mese['anno'].$date_mese['mese'].'\',\''.$richiesta['cod_advisor'].'\',\''.$richiesta['posizione'].'\')">&gt;&gt;</a></th>{/heading_next_cell}';
		} 	
	$this->load->library('calendar');
	$this->calendar->initialize($config_calendar); 
	$data['giorni']=str_replace('-','',$giorni);
	$data['anno']=$date_mese['anno'];
	$data['mese']=$date_mese['mese'];
		
		
		
		
		
		
		
		$this->load->view('calendarioSingolo_advisor_view',$data);
	}
	/*
	 * Restituisce un array i cui indici sono 'anno','mese' e 'ultimo', dove il valore di ultimo è il numero dell'ultimo giorno del mese.+
	 * il parametro $quale può essere 'prev','curr' e 'next', mentre $anno e $mese sono l'anno e il mese correnti.
	 * I valori del mese restituito dipendono da $quale: quelli del mese precedente a $corrente se vale 'prev', del mese corrente se vale
	 * 'curr' o del mese successivo se vale 'next'
	 */
	private function calcolaDate($quale,$anno,$mese){
		$valore=array();
		switch($quale){
			case 'prev':
				if($mese==1){
					$valore['anno']=$anno - 1;
					$valore['mese']=12;
				}else{
					$valore['anno']=$anno;
					$valore['mese']=$mese - 1;
				}
				break;
			case 'curr':
				$valore['anno']=$anno;
				$valore['mese']=$mese;
				break;
			case 'next':
				if($mese==12){
					$valore['anno']=$anno + 1;
					$valore['mese']=1;
				}else{
					$valore['anno']=$anno;
					$valore['mese']=$mese + 1;
				}
				break;
		}
		$valore['ultimo']=days_in_month($valore['mese'],$valore['anno']);
		return $valore;
		
	}
}