<?php
require_once 'sicurezza.php';
class GestioneCliente extends CI_Controller{
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
		$data['title']='Pharma3D-Gestione Cliente';
		$data['main_box']='gestioneCliente_view';
		$farma_id='';
		/*se non si proviene dal risultato di una ricerca o dal calendario... si viene reindirizzati al form di ricerca
		if(!($this->input->get('farma') || $this->input->post('nuovoApp') || $this->session->flashdata('from')=='attivita')){
			redirect('trovaClienti','refresh');
		}*/
		$tuttiEventi=Doctrine_Core::getTable('Tipoevento')->elencoRidotto();
		$tipi_evento[0]='';
		foreach($tuttiEventi as $eve){
			$tipi_evento[$eve['id']]=$eve['nome'];
		}
		$tutteAttivita=Doctrine_Core::getTable('Tipoattivita')->elencoRidotto();
		$tipi_attivita[0]='';
		foreach($tutteAttivita as $tipo){
			$tipi_attivita[$tipo['id']] = $tipo['nome'];
		}
		if($this->input->post('cod_cliente')){
			$farma_id=$this->input->post('cod_cliente');
		}else if($this->input->post('id_farmacia')){
			$farma_id=$this->input->post('id_farmacia');
		}else if($this->input->post('farmacia_id')){
			$farma_id=$this->input->post('farmacia_id');
		}else {
			
			$uri_farma=$this->uri->uri_to_assoc(3);
			if(isset($uri_farma['farma']))$farma_id=$uri_farma['farma'];
		}
		$permesso_modifiche=Sicurezza::permettiScriviAttivitaCliente($farma_id);
		$data['data_mainbox']['mostraPulsanteNuovo']=$permesso_modifiche;
		$data['data_mainbox']['mostraPulsanteSalva']=$permesso_modifiche;
		$data['data_mainbox']['mostraPulsanteSalvaNote']=$permesso_modifiche;
		$data['data_mainbox']['mostraNuovo']=false;		
		//se richiesto, si visualizza il form per una nuova attività
		if($this->input->post('nuovo')){
			$farma_id=$this->input->post('nuovo');
			$data['data_mainbox']['mostraNuovo']=true;
			$data['data_mainbox']['data_nuovaAttivita']['farmacia_id']=$farma_id;
			$data['data_mainbox']['data_nuovaAttivita']['tipi_attivita']=$tipi_attivita;
			$data['data_mainbox']['data_nuovaAttivita']['tipi_evento']=$tipi_evento;
		}
		//se richiesto, si aggiorna (eventualmente chiudendola) una attivita o se ne crea una nuova
		if($this->input->post('salvaNuovoApp') || $this->input->post('btModificaAttivita') ){
			$this->salvaAttivita($farma_id);
		}
		//se richiesto, si elimina una attivita
			if($this->input->post('btEliminaAttivita')){
			$this->eliminaAttivita();
		}
		//visualizzazione dello storecheck
		if($this->input->post('storeCheckForm')){
			$this->inizializzaStorecheck($data,$farma_id);		
		}
		//se richiesto aggiorno la farmacia, salvandone le note
		if($this->input->post('salvaNote')){
			$this->salvaFarmacia($farma_id);
		}
		
		//se richiesto imposto lo stato a 'non interessato'
		if($this->input->post('disinteressa')){
			$this->rendiNonInteressato($farma_id);
		}
		//se richiesto salvo uno storecheck
		if($this->input->post('salvaStorecheck')){
			$this->salvaStorecheck($farma_id);
		}
		/*
		//Se richiesto, visualizzo il report di un'attivita
		if($this->input->post('btReportAttivita')){
			$farma_id=$this->input->post('farmacia_id');
		}
		//se richiesto salvo il report di un'attivita
		if($this->input->post('salva_reportattivita')){
			$this->salvaReportAttivita();
		}*/
		$this->inizializzaFarmacia($data,$farma_id);
		$data['data_mainbox']['eventi_richiesti']=$this->recuperaRichiesteEventi($farma_id);
		$data['data_mainbox']['data_tabellaAttivita']['permesso_modifiche']=$permesso_modifiche;
		$data['data_mainbox']['data_tabellaAttivita']['permesso_cancella']=Sicurezza::permettiCancellaAttivitaCliente($farma_id);
		$data['data_mainbox']['data_tabellaAttivita']['tipi_attivita']=$tipi_attivita;
		$data['data_mainbox']['data_tabellaAttivita']['tipi_evento']=$tipi_evento;
		$this->load->view('template',$data);
		
	}
	
	private function inizializzaFarmacia(& $data,$farma_id){
		//La farmacia viene ricercata in base al suo id
		$risultato=Doctrine_Core::getTable('Farmacia')->ricercaFarmaciaPerId($farma_id);
		//Creo un array maggiormente friendly per la creazione della view (nomi delle chiavi!)
		$farmacia['cod_cliente']=$risultato['id'];
		$farmacia['rag_sociale']=$risultato['denominazione'];
		$farmacia['indirizzo']=$risultato['indirizzo'];
		$farmacia['cap']=$risultato['cap'];
		$farmacia['localita']=$risultato['localita'];
		$farmacia['telefono']=$risultato['numtel'];
		$farmacia['fax']=$risultato['numfax'];
		$farmacia['email']=$risultato['email'];
		$farmacia['regione']=$risultato['regione'];
		$farmacia['provincia']=$risultato['provincia'];
		$farmacia['advisor']=$risultato['advisor'];
		$farmacia['agente']=$risultato['agente'];
		$farmacia['stato']=$risultato['stato'];
		$farmacia['isf']=$risultato['isf'];
		$farmacia['num_isf']=$risultato['num_isf'];
		$farmacia['flusso_lavoro']=$risultato['flusso_lavoro'];
		$farmacia['note']=$risultato['note'];
		$farmacia['note_boehringer']=$risultato['note_boehringer'];
		$data['data_mainbox']['farmacia']=$farmacia;
		//Si recuperano le attività della farmacia;
		$risultati=Doctrine_Core::getTable('Attivita')->getAttivitaFarmacia($farmacia['cod_cliente']);
		$attivita=array();
		$dateString='%d/%m/%Y';
		foreach($risultati as $ris){
			$attivita[]=array(
				'attivita_id'=>$ris['id'],
				'stato'=>$ris['stato'],
				'giorno'=> mdate($dateString,mysql_to_unix($ris['giorno'])),
				'creata'=>mdate($dateString,mysql_to_unix($ris['created_at'])),
				'aggiornata'=>mdate($dateString,mysql_to_unix($ris['updated_at'])),
				'tipoattivita_id'=>$ris['tipoattivita_id'],
			    'ora_inizio'=>$ris['ora_inizio'],
			    'tipoevento_id'=>$ris['tipoevento_id'],
			    'farmacia_id'=>$ris['farmacia_id'],
			    'data_chiusura' =>$ris['stato']=='aperta'?'':mdate($dateString,mysql_to_unix($ris['data_chiusura'])),
			//I report sono stati previsti (dal 15/04/2011) solo per gli eventi.
				'ha_report'=>$ris['tipoattivita_id']==3?true:false
			);

			//ulteriori campi da mostrare
			
		}
		$data['data_mainbox']['attivita']=$attivita;	
	}
	
	private function salvaStorecheck($farma_id){
		//$farma_id=$this->input->post('farmacia_id');
		//se conosco l'id dello storecheck ne aggiorno uno esistenze, altrimenti ne creo uno
		if(!$rep=Doctrine_Core::getTable('Reportstorecheck')->findOneById($this->input->post('reportstorecheck_id'))){
			$rep=new Reportstorecheck();
		}
		$rep->farmacia_id=$farma_id;
		$rep->num_vetrine_terra =$this->input->post('n_vetrine_da_terra');
		$rep->uso_pc =$this->input->post('uso_pc')?1:0;
		$rep->sito_internet =$this->input->post('sito_internet')?1:0;
		$rep->analisi =$this->input->post('analisi')?1:0;
		$rep->spazio_servizio =$this->input->post('spazio_libero_servizio')?1:0;
		$rep->aree_dedicate =$this->input->post('aree_dedicate')?1:0;
		$rep->orario_continuato =$this->input->post('orario_continuato')?1:0;
		$rep->aperto24h =$this->input->post('apertura_24')?1:0;
		$rep->giorno_chiusura =$this->input->post('giorno_chiusura');
		$rep->collaboratore_riferimento =$this->input->post('collab_rif_servizi');
		$rep->titolare =$this->input->post('titolare');
		$rep->starchannel=$this->input->post('starchannel');
		$rep->starwindow =$this->input->post('starwindow')?1:0;
		$rep->vetrina_almanacco =$this->input->post('almanacco')?1:0;
		$rep->note =$this->input->post('note');
		$rep->fascia_negozio =$this->input->post('fascia_negozio');
		$rep->vicinanza =$this->input->post('collocazione');
		$rep->coll_laureati =$this->input->post('collaboratori_laureati');
		$rep->coll_nonlaureati =$this->input->post('collaboratori_non_laureati');
		$rep->mq_calpestabili =$this->input->post('mq_calpestabili');
		$rep->num_vetrine_finestra =$this->input->post('n_vetrine_finestra');
		$rep->insegna_tradizionale =$this->input->post('insegna_tradizionale')?1:0;
		$rep->insegna_elettronica =$this->input->post('insegna_elettronica')?1:0;
		$rep->insegna_accessori =$this->input->post('insegna_accessori')?1:0;
		$rep->insegna_catene =$this->input->post('insegna_catene')?1:0;
		$rep->banconi_vendita =$this->input->post('num_banconi');
		$rep->num_ingressi =$this->input->post('num_ingressi');
		$rep->cup =$this->input->post('cup')?1:0;
		$rep->localizzazione =$this->input->post('localizzazione');
		$rep->storico_modifiche.=date('Y-m-d');
		
		//recupero i servizi
		$rep->servizi='';
		$servizi=$this->input->post('serv');

		for($i=0;$i<36;$i++){
			if(isset($servizi[$i])){
				$rep->servizi.=';1';
			}else{
				$rep->servizi.=';0';
			}
		}
		$rep->servizi=substr($rep->servizi,1);
		$rep->save();
		$this->aggiornaGiacenze($rep);
		$rep->save();
		$this->salvaRichiesteEventi($farma_id);	
	}

	private function inizializzaStorecheck(& $data,$farmacia_id){
		$data['data_mainbox']['visualizzaStoreCheck']=true;
		//si recuperano i dati dello storecheck relativo alla farmacia
		$r=Doctrine_Core::getTable('Reportstorecheck')->findByFarmacia_id($farmacia_id);
		//O la farmacia non ha storecheck o ne ha uno solo
		if(count($r)==0){
			$store=new Reportstorecheck();
		}else{
			$store=$r[0];
		}
		$store_arr=$store->toArray();
		//evita di salvare accidentalmente uno storecheck vuoto
		unset($store);
		//creo un array amichevole per la view
		$store['reportstorecheck_id']=$store_arr['id'];
		$store['farmacia_id']=$farmacia_id;
		$store['collaboratori_laureati']=$store_arr['coll_laureati'];
		$store['collaboratori_non_laureati']=$store_arr['coll_nonlaureati'];
		$store['fascia_negozio']=$store_arr['fascia_negozio'];
		$store['mq_calpestabili']=$store_arr['mq_calpestabili'];
		$store['n_vetrine_finestra']=$store_arr['num_vetrine_finestra'];
		$store['n_vetrine_da_terra']=$store_arr['num_vetrine_terra'];
		$store['insegna_tradizionale']=$store_arr['insegna_tradizionale'];
		$store['insegna_elettronica']=$store_arr['insegna_elettronica'];
		$store['insegna_accessori']=$store_arr['insegna_accessori'];
		$store['insegna_catene']=$store_arr['insegna_catene'];
		$store['num_banconi']=$store_arr['banconi_vendita'];
		$store['num_ingressi']=$store_arr['num_ingressi'];
		$store['uso_pc']=$store_arr['uso_pc'];
		$store['sito_internet']=$store_arr['sito_internet'];
		$store['analisi']=$store_arr['analisi'];
		$store['spazio_libero_servizio']=$store_arr['spazio_servizio'];
		$store['aree_dedicate']=$store_arr['aree_dedicate'];
		$store['orario_continuato']=$store_arr['orario_continuato'];
		$store['apertura_24']=$store_arr['aperto24h'];
		$store['cup']=$store_arr['cup'];
		$store['localizzazione']=$store_arr['localizzazione'];
		$store['collocazione']=$store_arr['vicinanza'];
		$store['giorno_chiusura']=$store_arr['giorno_chiusura'];
		$store['starchannel']=$store_arr['starchannel'];
		$store['starwindow']=$store_arr['starwindow'];
		$store['almanacco']=$store_arr['vetrina_almanacco'];
		$store['note']=$store_arr['note'];
		$store['titolare']=$store_arr['titolare'];
		$store['collab_rif_servizi']=$store_arr['collaboratore_riferimento'];
		
		$eventi_richiesti=$this->recuperaRichiesteEventi($farmacia_id);
		$data['datamain_box']['eventi_richiesti']=$eventi_richiesti;
		if($store_arr['id']){
		$giacenze_prodotti=$this->recuperaGiacenze($store_arr['id']);
		}else{
			$giacenze_prodotti=array('dulcofibre'=>0,
									'antistax'=>0,
									'rinogutt'=>0,
									'buscopan'=>0,
									'zerinol'=>0);
		}
		$data['data_mainbox']['giacenze_prodotti']=$giacenze_prodotti;
	
		$giorniChiusura=Doctrine_Core::getTable('Reportstorecheck')->getGiorniChiusuraEnum();
		array_unshift($giorniChiusura,'');
		$statoStarchannel=Doctrine_Core::getTable('Reportstorecheck')->getStarchannelEnum();
		array_unshift($statoStarchannel,'');
		$fasceNegozio=Doctrine_Core::getTable('Reportstorecheck')->getFasceNegozioEnum();
		array_unshift($fasceNegozio,'');
		$fasceMagazzino=Doctrine_Core::getTable('Reportstorecheck')->getFasceMagazzinoEnum();
		array_unshift($fasceMagazzino,'');
		$vicinanze=Doctrine_Core::getTable('Reportstorecheck')->getVicinanzaEnum();
		array_unshift($vicinanze,'');
		$fasceCalpestabili=Doctrine_Core::getTable('Reportstorecheck')->getMqCalpestabiliEnum();
		array_unshift($fasceCalpestabili,'');
		$localizzazioni=Doctrine_Core::getTable('Reportstorecheck')->getLocalizzazioneEnum();
		array_unshift($localizzazioni,'');
		$data['data_mainbox']['storecheck']=$store;
		$data['data_mainbox']['giorniChiusura']=$giorniChiusura;
		$data['data_mainbox']['statoStarchannel']=$statoStarchannel;
		$data['data_mainbox']['fasceNegozio']=$fasceNegozio;
		$data['data_mainbox']['fasceMagazzino']=$fasceMagazzino;
		$data['data_mainbox']['vicinanze']=$vicinanze;
		$data['data_mainbox']['fasceCalpestabili']=$fasceCalpestabili;
		$data['data_mainbox']['localizzazioni']=$localizzazioni;
		$data['data_mainbox']['eventi_richiesti']=$eventi_richiesti;
		$data['data_mainbox']['giorni_chiusura']=$giorniChiusura;
		$data['data_mainbox']['star_channel']=$statoStarchannel;	
	}
	
	private function recuperaRichiesteEventi($farma_id){
		$eventi_richiesti=array();
		$richiesti_advisor=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiesteFarmacia($farma_id,false);
		$richiesti_agente=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiesteFarmacia($farma_id,true);
		if(is_null($richiesti_advisor[1])){
			$eventi_richiesti['intestino']=$richiesti_agente[1];
		}else{
			$eventi_richiesti['intestino']=$richiesti_advisor[1];
		}
		if(is_null($richiesti_advisor[2])){
			$eventi_richiesti['gambe']=$richiesti_agente[2];
		}else{
			$eventi_richiesti['gambe']=$richiesti_advisor[2];
		}
		if(is_null($richiesti_advisor[3])){
			$eventi_richiesti['naso']=$richiesti_agente[3];
		}else{
			$eventi_richiesti['naso']=$richiesti_advisor[3];
		}
		if(is_null($richiesti_advisor[4])){
			$eventi_richiesti['stomaco']=$richiesti_agente[4];
		}else{
			$eventi_richiesti['stomaco']=$richiesti_advisor[4];
		}
		if(is_null($richiesti_advisor[5])){
			$eventi_richiesti['gola']=$richiesti_agente[5];
		}else{
			$eventi_richiesti['gola']=$richiesti_advisor[5];
		}
		foreach($eventi_richiesti as $chiave => $valore){
			if(is_null($valore)) $eventi_richiesti[$chiave]=0;
		}
		return $eventi_richiesti;
	}
	
	private function salvaRichiesteEventi($farma_id){
		//creo array(tipoevento=>eventi richiesti) con i dati del form
		$nuovi=array();
		$nuovi['1']=$this->input->post('intestino');
		$nuovi['2']=$this->input->post('gambe');
		$nuovi['3']=$this->input->post('naso');
		$nuovi['4']=$this->input->post('stomaco');
		$nuovi['5']=$this->input->post('gola');
		
		foreach($nuovi as $tipo => $numero){

			//se esiste un record inserito dall'advisor va aggiornato
			$richiesta=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiestaFarmaTipo($farma_id,$tipo,false);
			if($richiesta){
				$richiesta->numero_eventi=is_null($numero)?0:$numero;
				$richiesta->save();
			}
			//se non esistono dati inseriti dall'advisor
			else{ 
				$farmacia=Doctrine_Core::getTable('Farmacia')->findOneById($farma_id);
				//se esiste una richiesta inserita dall'agente
				if($richiesta=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiestaFarmaTipo($farma_id,$tipo,true)){
					//se è cambiato qualcosa inserisco la richiesta dell'advisor
					if($richiesta->numero_eventi != $numero ){

						$r=new RichiestaTipoevento();
						$r->farmacia_id=$farma_id;
						$r->tipoevento_id=$tipo;
						$r->numero_eventi=$numero;
						$r->inserito_da_agente=false;
						$r->save();
						if($farmacia->stato!='attivo' && $farmacia->stato!='attivato da ADV'){
							$farmacia->stato='attivato da ADV';
							$farmacia->save();
						}
					}
				}
				//se l'agente non aveva inserito nulla crea una richiesta dell'advisor
				else{
					$r=new RichiestaTipoevento();
					$r->farmacia_id=$farma->id;
					$r->tipoevento_id=$tipo;
					$r->numero_eventi=$numero;
					$r->inserito_da_agente=false;
					$r->save();	
					if($farmacia->stato!='attivo' && $farmacia->stato!='attivato da ADV'){
							$farmacia->stato='attivato da ADV';
							$farmacia->save();
						}				
				}
				
			}
		}
	}
	
	private function salvaAttivita($farma_id){
		//se la variabile post attivita_id contiene un valore, si aggiorna una attività
		if($this->input->post('attivita_id')){
			$a=Doctrine_Core::getTable('Attivita')->findOneById($this->input->post('attivita_id'));
		//altrimenti si crea una nuova attivita
		}else{
			$a=new Attivita();
			$a->farmacia_id=$farma_id;
		}
		$giorno=$this->input->post('giorno');
		$giorno=explode("/",$giorno);
		$giorno=$giorno[2]."-".$giorno[1]."-".$giorno[0];
		$a->giorno=$giorno;
		$a->ora_inizio=$this->input->post('ora_inizio');
		$a->tipoattivita_id=$this->input->post('tipoattivita_id');
		$a->tipoevento_id=$this->input->post('tipoevento_id');
		if($this->input->post('stato')=='chiusa'){
			$a->stato='chiusa';
			$chiusura_db=$a->data_chiusura;
			$chiusura_dbe=explode('-',$chiusura_db);
			if(! checkdate($chiusura_dbe[1],$chiusura_dbe[2],$chiusura_dbe[0]) || strtotime($chiusura_db)===false){
				$a->data_chiusura=date('Y-m-d');
			}
		}else{
			$a->stato='aperta';
			$a->data_chiusura='0000-00-00';
		}

		$a->save();
		
	}
	
	private function eliminaAttivita(){
		if($a=Doctrine_Core::getTable('Attivita')->findOneById($this->input->post('attivita_id'))){
			$a->delete();
		}
		
	}
	
	private function recuperaGiacenze($storecheck_id){
		$giacenze=array();
		$giac=Doctrine_Core::getTable('Giacenzaprodotto')->getGiacenzeStorecheck($storecheck_id);
		$giacenze['dulcofibre']=$giac['1']?$giac['1']:0;
		$giacenze['antistax']=$giac['2']?$giac['2']:0;
		$giacenze['rinogutt']=$giac['3']?$giac['3']:0;
		$giacenze['buscopan']=$giac['4']?$giac['4']:0;
		$giacenze['zerinol']=$giac['5']?$giac['5']:0;
		return $giacenze;
	}
	
	private function aggiornaGiacenze($storecheckObj){
		$giacenze=& $storecheckObj->GiacenzaProdotti;
		$nuove_giac=array();
		if($this->input->post('giac_dulcofibre')){
			$g=new Giacenzaprodotto();
			$g->reportstorecheck_id=$storecheckObj->id;
			$g->prodotto_id=1;
			$g->quantita=$this->input->post('giac_dulcofibre');
			$nuove_giac[$g->prodotto_id]=$g;
		}
			if($this->input->post('giac_antistax')){
			$g=new Giacenzaprodotto();
			$g->reportstorecheck_id=$storecheckObj->id;
			$g->prodotto_id=2;
			$g->quantita=$this->input->post('giac_antistax');
			$nuove_giac[$g->prodotto_id]=$g;			
		}
			if($this->input->post('giac_rinogutt')){
			$g=new Giacenzaprodotto();
			$g->prodotto_id=3;
			$g->reportstorecheck_id=$storecheckObj->id;
			$g->quantita=$this->input->post('giac_rinogutt');
			$nuove_giac[$g->prodotto_id]=$g;
		}
			if($this->input->post('giac_buscopan')){
			$g=new Giacenzaprodotto();
			$g->reportstorecheck_id=$storecheckObj->id;
			$g->prodotto_id=4;
			$g->quantita=$this->input->post('giac_buscopan');
			$nuove_giac[$g->prodotto_id]=$g;
		}
			if($this->input->post('giac_zerinol')){
			$g=new Giacenzaprodotto();
			$g->reportstorecheck_id=$storecheckObj->id;
			$g->prodotto_id=5;
			$g->quantita=$this->input->post('giac_zerinol');
			$nuove_giac[$g->prodotto_id]=$g;
		}
		foreach($giacenze as $giac){
			if(isset($nuove_giac[$giac->prodotto_id])){
				$giac->quantita=$nuove_giac[$giac->prodotto_id]->quantita;
				unset($nuove_giac[$giac->prodotto_id]);
			}
		}
		foreach($nuove_giac as $ng) {
			$ng->link('Reportstorecheck',array($storecheckObj->id));
			$ng->save();
		}
		
	}
	
	public function salvaFarmacia($farma_id){
		$farmacia=Doctrine_Core::getTable('Farmacia')->findOneById($farma_id);
		print_r($farmacia);
		$farmacia->note=$this->input->post('note');
		//$farmacia->save();
	}
	
	public function rendiNonInteressato($farma_id){
		$farmacia=Doctrine_Core::getTable('Farmacia')->findOneById($farma_id);
		$farmacia->stato='non interessato';
		$farmacia->save();
	}
/*	
	private function salvaReportAttivita(){
		$attivita=Doctrine_Core::getTable('Attivita')->findOneById($this->input->post('attivita_id'));
		switch($attivita->tipoattivita_id){
			case 2:
				$report_db=Doctrine_Core::getTable('Reportpreevento')->findOneByAttivita_id($this->input->post('attivita_id'));
				if(!$report_db){
					$report_db=new Reportpreevento();
				}
				$report_db->attivita_id=$this->input->post('attivita_id');
				$report_db->leaf_edu=$this->input->post('leaf_edu');
				$report_db->leaf_prodotto=$this->input->post('leaf_prodotto');
				$report_db->locandine=$this->input->post('locandine');
				$report_db->espositore=	$this->input->post('espositore');
				$report_db->totem=$this->input->post('totem');
				$report_db->ticket=$this->input->post('ticket');
				$report_db->numappuntamenti=$this->input->post('appuntamenti');
				$report_db->partecipazione=$this->input->post('partecipazione_farmacista');
				$report_db->note=$this->input->post('note');
				break;
			case 3:
				$report_db=Doctrine_Core::getTable('Reportevento')->findOneByAttivita_id($richiesta['idAttivita']);
				if($report_db){
					$report_db=new Reportevento();
				}
				$report_db->attivita_id=$this->input->post('attivita_id');
				$report_db->contatti_appuntamento=$this->input->post('contatti_appuntamento');
				$report_db->contatt0i_noappuntamento=$this->input->post('contatti_noappuntamento');
				$report_db->sellout=$this->input->post('sellout');
				$report_db->soddisfazione_farmacista=$this->input->post('soddisfazione_farmacista');
				$report_db->note=$this->input->post('note');
				break;
		}
		$report_db->save();

	}
	*/

	public function test_storecheck(){

		$this->load->view('storecheck_form');
		
	}
	
	public function test_form(){
		$uri_store=$this->uri->uri_to_assoc(3);
		$data['servizi']=array();
		if(isset($uri_store['id']))$storecheck_id=$uri_store['id'];
		if($r=Doctrine_Core::getTable('Reportstorecheck')->findOneById($storecheck_id)){
			if(strlen($r->servizi)>0){
				$data['servizi']=explode(';',$r->servizi);
				for($j=count($data['servizi']);$j<36;$j++){
					$data['servizi'][$j]=0;
				}
			}else{
				for($i=0;$i<36;$i++){
				$data['servizi'][]=0;
				}
			}
		}else{
			for($i=0;$i<36;$i++){
				$data['servizi'][]=0;
			}
		}
		$this->load->view('storecheck_form_servizi',$data);
	}
}
