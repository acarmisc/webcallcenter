<?php
class Sicurezza {
	private static $info_utente;
	private static $gruppi_utente; //array contenente i soli nomi dei gruppi e, eventualmente Advisor o Agente
	
	public static function verificaUtente($redirect){
	/*
	 * Se l'utente non risulta loggato, lo reindirizzo sulla home page, a meno che $redirect non sia false
	 * $redirect deve essere false quando la funzione viene invocata dalla home (per evitare loop)
	 */
		if(!$u=Utente_Corrente::user()){
			$CI=& get_instance();
			$CI->load->library('session');
			$CI->session->sess_destroy();
			delete_cookie('ci_session');
			if($redirect) redirect('home','refresh');
			return false;
		}
		self::$info_utente=Utente_Corrente::informazioni();
		
		foreach(self::$info_utente['gruppi'] as $gruppo){
			self::$gruppi_utente[]=$gruppo[1];
		}
		if(isset(self::$info_utente['cod_advisor']) && self::$info_utente['cod_advisor']!=''){
			self::$gruppi_utente[]='Advisor';
		}
		if(isset(self::$info_utente['cod_agente']) && self::$info_utente['cod_agente']!=''){
			self::$gruppi_utente[]='Agente';
		}
		return true;
	}
	public static function aggiornaUtente(){
		Utente_Corrente::refresh();
		self::verificaUtente(false);
	}
	
	//in base ai gruppi dell'utente, restituisce un array link della toolbar.
	public static function vociToolbar(){
		$voci=array();
		$voci[]=anchor('trovaClienti','Ricerca Clienti');
		if(in_array('Utenti di Sede',self::$gruppi_utente)){
			$voci[]=anchor('calendario','Ricerca Agente-ADV');
			$voci[]=anchor('reportStatistiche','Report');
			$voci[]=anchor('reportFarmacie','Report eventi futuri');
			$voci[]=anchor('reportAdvisor_Farmacie','Report farmacie per advisor');
			$voci[]=anchor('c=magazzino&operatore_id='.self::$info_utente['id'],'gestione spedizioni');
		}
		if(in_array('Area Manager',self::$gruppi_utente)){
			$voci[]=anchor('calendario','Ricerca Agente-ADV');
			$voci[]=anchor('reportStatistiche','Report');
			$voci[]=anchor('reportFarmacie','Report eventi futuri');
			$voci[]=anchor('reportAdvisor_Farmacie','Report farmacie per advisor');
			$voci[]=anchor('assegnazioneFarmacie','Assegnazione farmacie');
			
		}
		if(in_array('Advisor',self::$gruppi_utente)){
			$voci[]=anchor('calendario','Agenda personale');
			$voci[]=anchor('c=magazzino&m=advisor_view&id='.self::$info_utente['cod_advisor'],'spedizioni');
		}
		if(in_array('Agente',self::$gruppi_utente)){
			$voci[]=anchor('calendario','Ricerca Advisor');
		}
			
		$voci[]=anchor('userProfile','Profilo personale');
		$voci[]=anchor('home/logout','Esci');
		return $voci;
	}
	
	/*
	 * se l'utente è un advisor, dopo il login deve essere reindirizzato alla sua agenda, altrimenti al
	 * form di ricerca cliente.
	 */
	public static function attivitaIniziale(){

		if(in_array('Advisor',self::$gruppi_utente)){
			redirect('calendario','refresh');
		}else{
			redirect('riassunto','refresh');
		}
			
	}
	
	/*
	 * Verifica se l'utente corrente può visualizzare le attività dell'advisor passato come argomento.
	 * Se l'utente è autorizzato restituisce true, false altrimenti
	 * Analogamente per la funzione successiva, relativa a modifica, inserimento e cancellazione
	 */
	public static function permettiVisualizzaAttivitaAdvisor($cod_advisor){
		//Se l'utente è un advisor allora deve visualizzare solo le sue attivita
		if(self::$info_utente['cod_advisor']!=''){
			if(self::$info_utente['cod_advisor']==$cod_advisor) return true;
		}
		
		//Se l'utente è un agente e, nello specifico l'agente dell'advisor...è autorizzato
		if(self::$info_utente['cod_agente']!=''){
			//se non esiste un advisor con il codice fornito restituisco false
			if(! $advisor=Doctrine_Core::getTable('Advisor')->findOneById($cod_advisor)) return false ;
			//tutti gli agenti cui un advisor fa capo (vista l'organizzazione del db potenzialmente più di uno=
			$agenti=$advisor->getAgenti();
			//gestisco una potenziale anomalia nei dati del db
			if(count($agenti)==0) return false;
			foreach ($agenti as $id => $nome_completo){
				//se trovo il codice agente dell'utente corrente, allora può visualizzare le attività
				if($id==self::$info_utente['cod_agente']) return true;
			}
			/*non ho trovato l'utente corrente, che è un agente, fra gli agenti dell'advisor e quindi non può
			 * visualizzare le attività */
			return false;
		}
		//Tutti gli altri utenti, non advisor e non agenti, possono visualizzare le attività
		return true;	
	}

	/*
	 * vedi commento funzione precedente
	 */
	public static function permettiScriviAttivitaAdvisor($cod_advisor){
		//se l'utente corrente non può visualizzare le attività non può neanche modificarle
		if(! self::permettiVisualizzaAttivitaAdvisor($cod_advisor)) return false;
		//se l'utente corrente è l'advisor stesso, ovviamente può modificare le sue attivita
		if(self::$info_utente['cod_advisor']==$cod_advisor) return true;
		//Il call center può modificare le attività
		if(in_array('Field Force CHC',self::$info_utente['gruppi']))return true;
		//Nessun altro può modificare le attivita
		return false;
	}
	
		/*
	 * Verifica se l'utente corrente può visualizzare le attività del cliente passato come argomento.
	 * Se l'utente è autorizzato restituisce true, false altrimenti
	 * Analogamente per la funzione successiva, relativa a modifica, inserimento e cancellazione
	 */
	public static function permettiVisualizzaAttivitaCliente($cod_cliente){
		//se l'utente è membro di un gruppo qualsiasi, può sicuramente visualizzare il cliente
		if(count(self::$info_utente['gruppi'])>0){
			return true;
		}
		//se l'utente è un advisor, può visualizzare solamente se si tratta di un suo cliente
		if(self::$info_utente['cod_advisor']!=''){
			$result=Doctrine_Core::getTable('Farmacia')->ricercaFarmaciaPerId($cod_cliente);
			if($result && $result['advisor_id']==self::$info_utente['cod_advisor']) return true;
		}
		//se l'utente è un agente, può visualizzare solamente se si tratta di un cliente di un suo advisor
			if(self::$info_utente['cod_agente']!=''){
			$result=Doctrine_Core::getTable('Farmacia')->ricercaFarmaciaPerId($cod_cliente);
			if($result && $result['agente_id']==self::$info_utente['cod_agente']) return true;
		}
		//In tutti i rimanenti casi, non può visualizzare i dati della farmacia
		return false;
	}
	
	public static function permettiScriviAttivitaCliente($cod_cliente){
		//se l'utente corrente non può visualizzare le attività non può neanche modificarle
		if(! self::permettiVisualizzaAttivitaCliente($cod_cliente)) return false;
		//se è un membro del gruppo 'Field Force CHC' può modificare i dati del cliente
		if(in_array('Field Force CHC',self::$gruppi_utente)) return true;
		//se è l'advisor della farmacia, può modificare le attività
		$result=Doctrine_Core::getTable('Farmacia')->ricercaFarmaciaPerId($cod_cliente);
		if(self::$info_utente['cod_advisor'] && self::$info_utente['cod_advisor']==$result['advisor_id']) return true;
		//In tutti gli altri casi l'utente non può modificare le attivita del cliente
		return false;
	}
	
	public static function permettiCancellaAttivitaCliente($cod_cliente){
		if(in_array('Area Manager',self::$gruppi_utente)) return true;
		//se è l'advisor della farmacia, può modificare le attività
		$result=Doctrine_Core::getTable('Farmacia')->ricercaFarmaciaPerId($cod_cliente);
		if(self::$info_utente['cod_advisor'] && self::$info_utente['cod_advisor']==$result['advisor_id']) return true;
		//In tutti gli altri casi l'utente non può modificare le attivita del cliente
		return false;
	}
	
	/*
	 * FUNZIONI CALL_BACK
	 * Le funzioni che seguono restituiscono nomi di funzioni private dei singoli controlli. Il nome restituito
	 * dipende dai permessi dell'utente.
	 */	
	
	/*
	 *Funzione utilizzate dal form trovaClienti. Fa sì che ogni utente abbia a disposizione nei menu a 
	 *tendina solo regioni, province, agenti ed advisor compatibili con il proprio stato.
	 */
	public static function scegliElenchi(){
		if(in_array('Advisor',self::$gruppi_utente) ){
			return 'elenchiAdvisor';
		}elseif (in_array('Agente',self::$gruppi_utente)){
			return 'elenchiAgente';
		}
		return 'elenchiCompleti';
	}
	
	/*
	 * Funzione utilizzata dal form calendario, per far sì che un advisor veda solamente il proprio, che gli
	 * agenti possano scegliere fra quelli dei rispettivi advisor e gli altri utenti a quelli di tutti.
	 */
	public static function scegliAdvisors(){
		if(in_array('Advisor',self::$gruppi_utente)){
			return 'singoloAdvisor';
		}elseif(in_array('Agente',self::$gruppi_utente)){
			return 'advisorsPerAgente';
		}else{
			return 'elencoAdvisors';
		}
	}
	
}