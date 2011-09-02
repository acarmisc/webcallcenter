<?php
//Classe statica, che consente di accedere ad una istanza dell'utente corrente
class Utente_Corrente {
	private static $user;
	//Il costruttore è privato, per impedire di creare istanze della classe
	private function __construct(){}
	//restituisce la variabile $user
	public static function user($forceReload=false){
		if(!isset(self::$user) || $forceReload){
			$CI=& get_instance();
			$CI->load->library('session');
			if(!$user_id=$CI->session->userdata('id')){
				return false;
			}
			if(!$u=Doctrine::getTable('Utente')->find($user_id)){
				return false;
			}
			self::$user=$u;
		}
		return self::$user;
	}
	
	public static function login($login,$password){
		if($u=Doctrine::getTable('Utente')->findOneByLogin($login)){
			$u_in=new Utente();
			//La password di $u_in è ora la password cifrata e 'saltata'
			$u_in->password=$password;
			//si verifica che la password fornita sia corretta
			if($u_in->password==$u->password){
				//si distrugge l'oggetto User temporaneo, poiché non più necessario
				unset($u_in);
				//si imposta la variabile di sessione id;
				$CI=& get_instance();
				$CI->load->library('session');
				$CI->session->set_userdata('id',$u->id);
				self::$user=$u;
				return true;
			}
			unset($u_in);
		}
		return false;
	}
	
	public function __clone(){
		trigger_error('Metodo clone non consetito.',E_USER_ERROR);
	}
	
	public static function informazioni(){
		//se non c'è un utente corrente, si restituisce null
		if(!isset(self::$user)){
			return null;
		}
		$gruppi=array();
		foreach(self::$user->Gruppi as $gruppo){
			$gruppi[]=array($gruppo->id,$gruppo->nome);
		}
		$cod_advisor=self::$user->Advisor['id'];
		if(!isset($cod_advisor)) $cod_advisor='';
		$cod_agente=self::$user->Agente['id'];
		if(!isset($cod_agente)) $cod_agente='';
	
		return array(
			'id'			=>	self::$user->id,
			'login'			=>	self::$user->login,
			'nome_completo'	=>	self::$user->nome_completo,
			'email'			=>	self::$user->email,
			'numtel'		=>	self::$user->numtel,
			'numcel'		=>	self::$user->numcel,
			'note'			=>	self::$user->note,
			'cod_advisor'	=>	$cod_advisor,
			'cod_agente'	=>	$cod_agente,
			'gruppi'		=>	$gruppi
		);

	}
	public static function refresh(){
		self::user(true);
	}
	
}