<?php
require_once 'sicurezza.php';
class Riassunto extends CI_Controller{
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
		$data['title']='Pharma3D-Situazione complessiva';
		$data['main_box']='riassunto_view';
		$data['data_mainbox']['tab_richieste']['intestazione']=array(
			'',
			'Totale',
			'Intestino',
			'Gambe',
			'Naso',
			'Stomaco',
			'Gola'
		);
		$intestino_ag=Doctrine_Core::getTable('Eventi_1_view')->getTotaleAgente();
		$intestino_ad=Doctrine_Core::getTable('Eventi_1_view')->getTotaleAdvisor();
		$gambe_ag=Doctrine_Core::getTable('Eventi_2_view')->getTotaleAgente();
		$gambe_ad=Doctrine_Core::getTable('Eventi_2_view')->getTotaleAdvisor();
		$naso_ag=Doctrine_Core::getTable('Eventi_3_view')->getTotaleAgente();
		$naso_ad=Doctrine_Core::getTable('Eventi_3_view')->getTotaleAdvisor();
		$stomaco_ag=Doctrine_Core::getTable('Eventi_4_view')->getTotaleAgente();
		$stomaco_ad=Doctrine_Core::getTable('Eventi_4_view')->getTotaleAdvisor();
		$gola_ag=Doctrine_Core::getTable('Eventi_5_view')->getTotaleAgente();
		$gola_ad=Doctrine_Core::getTable('Eventi_5_view')->getTotaleAdvisor();
		$data['data_mainbox']['tab_richieste']['righe'][]=array(
			'Agente',
			$intestino_ag+$gambe_ag+$naso_ag+$stomaco_ag+$gola_ag,
			$intestino_ag,
			$gambe_ag,
			$naso_ag,
			$stomaco_ag,
			$gola_ag
		);
		$data['data_mainbox']['tab_richieste']['righe'][]=array(
			'Advisor',
			$intestino_ad+$gambe_ad+$naso_ad+$stomaco_ad+$gola_ad,
			$intestino_ad,
			$gambe_ad,
			$naso_ad,
			$stomaco_ad,
			$gola_ad
		);
		$data['data_mainbox']['tab_visite']['intestazione']=array(
			'Visitate da advisor',
			'Non visitate da advisor'
		);
		$visitate=Doctrine_Core::getTable('Farmacie_visitate_view')->getNumeroFarmacieVisitate();
		$farmacieAttive=Doctrine_Core::getTable('Farmacia')->getNumeroFarmacieAttive();
		$nonVisitate=$farmacieAttive-$visitate;
		$data['data_mainbox']['tab_visite']['riga']=array($visitate,$nonVisitate);
		$data['data_mainbox']['tab_attivita']['intestazione']=array(
			'Storecheck',
			'Preevento',
			'Campagne',
			'Merchandising'
		);
		$numStorecheck=Doctrine_Core::getTable('Reportstorecheck')->getNumeroStorecheck();
		$numPreevento=Doctrine_Core::getTable('Reportpreevento')->getNumeroPreeventi();
		$numEventi=Doctrine_Core::getTable('Reportevento')->getNumeroEventi();
		$numMerchandising=0;
		$data['data_mainbox']['tab_attivita']['riga']=array(
			$numStorecheck,
			$numPreevento,
			$numEventi,
			$numMerchandising
		);
		$this->load->view('template',$data);
		
		
	}
	
	
}