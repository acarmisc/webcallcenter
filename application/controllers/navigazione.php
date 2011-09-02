<?php
//classe statica che mantiene lo stato della sessione di navigazione
/*
 * le variabili sono mantenute in array raggiungibili tramite 'percorsi' che specificano le chiavi. Ad esempio
 * /var1/var2/var3 indica di considerare la variabile flash_datavar1, che è un array con una chiave var2. Il valore 
 * associato a var 2 è un array, che ha una chiave var3. Sia var1, sia var2 sia var3 sono array e tutti
 * hanno una chiave 'data', che restituisce o un valore o un array di valori (questo è free-form, dipendente
 * dal significato della sua chiave).
 * Tipicamente var1 è il controller dela pagina di provenienza e $var1['data'] sono le variabili associate
 * alla pagina in generale o principale. $var1['var2'] è una porzione della pagina principale con
 * $var1['var2']['data'] i dati associati. var1,var2...varn NON devono mai chiamarsi 'data'.
 */
class Navigazione{
	private static $navigazione;
	
	private function __construct(){}
	
	public static function inizializza(){
		$CI=& get_instance();
		$CI->load->library('session');
		if($CI->session->userdata('navigazione')){
			self::$navigazione=& $CI->session->userdata('navigazione');
		}else{
			$navigazione=array();
			$CI->session->set_userdata('navigazione',$navigazione);
			self::$navigazione=$navigazione;
		}
	}
	
	public static function getVariabile($percorso){
		$percorso=explode('/',$percorso);
		$dati=self::$navigazione;
		foreach ($percorso as $nome){
			if($nome==null) continue;
			if(isset($dati[$nome])) {
				$dati=$dati[$nome];
			}else{
				$dati=null;
				break;
			}
		}
		if($dati) return $dati['data'];
		else return null;
	}
	public static function setVariabile($percorso,$data){
		$varCorrente=& get_instance()->session->userdata('navigazione');
		$variabili=explode("/",$percorso);
		foreach($variabili as $variabile){
			if($variabile==null)continue;
			if(!isset($varCorrente[$variabile])){
				$varCorrente[$variabile]=array();
			}
			$varCorrente=$varCorrente[$variabile];
		}
		$varCorrente['data']=$data;
	}
	public static function annullaVariabile($percorso){
		$varCorrente=self::$navigazione;
	$variabili=explode("/",$percorso);
		foreach($variabili as $variabile){
			if($variabile==null)continue;
			if(!isset($varCorrente[$variabile])){
				return;
			}
			unset($varCorrente[$variabile]);
		}
	}
}