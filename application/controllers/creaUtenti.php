<?php
class CreaUtenti extends CI_Controller{
	function __construct(){
		parent::__construct();
	}
	public function index(){
		
	/*
		 * Nota sui gruppi:
		 * $utente['gruppo'] può valere un numero da 1 a 5 con i seguenti significati:
		 *   1 | Field Force CHC 
		 *   2 | Account Agenzia 
		 *   3 | CSO Pharmitalia
		 *   4 | Utenti di Sede 
		 *   5 | Area Manager 
		 * non assegnare un valore o assegnare un valore errato termina immediatamente lo script. 
		 */
		
		//*ripetere compilandole le righe da qui sino al prossimo commento che inizia con * per ogni utente
		/*
		$utente['nome_completo']='Silvia Lavoretano'; //nome e cognome
		$utente['login']='lavoretano'; //non viene fatto alcun controllo su login duplicate
		$utente['password']='silvia';
		$utente['email']=''; //non compare ancora da nessuna parte: può essere ''
		$utente['numtel']=''; //non compare ancora da nessuna parte: può essere ''
		$utente['numcel']=''; //non compare ancora da nessuna parte: può essere ''
		$utente['note']='';	  //non compare ancora da nessuna parte: può essere ''
		$utente['gruppo']='5'; //vedi nota iniziale
		$utenti[]=$utente;
		fine della parte da ripetere per ogni nuovo utente.
		*/
		
		$utente['nome_completo']='Andrea Carmisciano'; //nome e cognome
		$utente['login']='acarmisc';
		$utente['password']='andrea';
		$utente['gruppo']='4';
		$utenti[]=$utente;
		
		foreach($utenti as $ut){
		

			$a=new Utente();
			$a->login=$ut['login'];
			$a->password=$ut['password'];
			$a->nome_completo=$ut['nome_completo'];
			$a->email=$ut['email'];
			$a->numtel=$ut['numtel'];
			$a->numcel=$ut['numcel'];
			$a->note=$ut['note'];
			$a->save();
			$gruppo=Doctrine_Core::getTable('Gruppo')->findOneById($ut['gruppo']);
			$idgruppo=$gruppo->id;
			$a->link('Gruppi',$idgruppo);
			$a->save();
		}
	}
}

?>