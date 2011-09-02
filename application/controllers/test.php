<?php
class Test extends CI_Controller{
	function __construct(){
		parent::__construct();
	}
	/*private function altera(& $farmacia,$stato){
		$farmacia->stato;
		$r=new RichiestaTipoevento();
		$r->tipoevento_id=4;
		$r->numero_eventi=40;
		$farmacia->RichiesteTipoevento[]=$r;
	}*/
	public function index(){
		
		/*$r=Doctrine_Core::getTable('Microbrick')->statistiche();
		print_r($r);*/
		$r=Doctrine_Core::getTable('Farmacie_attivate_advisor_view')->findAll();
		print_r($r);

		/*$f=Doctrine_Core::getTable('Farmacia')->findOneById('21217');
		$stato=$f->stato;
		$this->altera($f,'non interessato');
		$f->save();
		$this->altera($f,$stato);
		$f->save();*/
		/*
		$a=new Utente();
		$a->login='adv';
		$a->nome_completo='adv adv';
		$a->password='adv';
		$a->email='adv@lucia.it';
		$a->numtel='123456';
		$a->numcel='1234567';
		$a->note='advisor';
		$a->Advisor->id='adv30';
		$a->save();
		
		
		
		$a=new Utente();
		$a->login='ag';
		$a->nome_completo='ag ag';
		$a->password='ag';
		$a->email='ag@lucia.it';
		$a->numtel='123456';
		$a->numcel='1234567';
		$a->note='agente';
		$a->Agente->id='ag40';
		$a->save();

		$q=Doctrine_Core::getTable('Utente')->findAll();
		foreach($q as $u){
			$pezzi=explode(' ',$u->nome_completo);
			$login=strtolower($pezzi[count($pezzi)-1]);
			$password=strtolower($pezzi[0].strlen($pezzi[0]));
			$u->login=$login;
			$u->password=$password;
			$u->save();
		}
		
	echo "ciao";
		$a=Doctrine_Core::getTable('Advisor')->findOneById('25');
		$agenti=$a->Agenti;
		echo count($agenti) ."<br>";
	 	echo $agenti[0]->Utente->nome_completo;	
	 	$u=new Utente();
	 	$u->login='ghu';
	 	$u->password='ghu';
	 	$u->nome_completo='Antonio Ghu';
	 	$g=Doctrine_Core::getTable('Gruppo')->findOneById(1);
	 	$u->Gruppi[]=$g;
	 	$u->save();
	 	*/
		
		/*$richieste=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiesteFarmacia('42994',false);
		print_r($richieste);
		if(is_null($richieste[1])) echo "ciao"; 
		echo $richieste[1];*/
	}
}