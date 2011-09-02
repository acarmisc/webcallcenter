<?php
class TestFrammenti extends CI_Controller{
/*
public function index(){
$farma_id='43073';
$eventi_richiesti=array('intestino'=>0,
									'gambe'=>0,
									'naso'=>0,
									'stomaco'=>0,
									'gola'=>0);
			$eventi_arr=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiestaFarmaciaTipo($farma_id,1);
			print_r($eventi_arr);
			foreach($eventi_arr as $ev){
				if(count($ev)==1){
					$eventi_richiesti['intestino']=$ev[0]['numero_eventi'];
				}else{
					foreach($ev as $e){
						if($e['inserito_da_agente']) continue;
						$eventi_richiesti['intestino']=$e['numero_eventi'];
					}
				}
			}
			$eventi_arr=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiestaFarmaciaTipo($farma_id,2);
			foreach($eventi_arr as $ev){
				if(count($ev)==1){
					$eventi_richiesti['gambe']=$ev[0]['numero_eventi'];
				}else{
					foreach($ev as $e){
						if($e['inserito_da_agente']) continue;
						$eventi_richiesti['gambe']=$e['numero_eventi'];
					}
				}
			}
			$eventi_arr=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiestaFarmaciaTipo($farma_id,3);
			foreach($eventi_arr as $ev){
				if(count($ev)==1){
					$eventi_richiesti['naso']=$ev[0]['numero_eventi'];
				}else{
					foreach($ev as $e){
						if($e['inserito_da_agente']) continue;
						$eventi_richiesti['naso']=$e['numero_eventi'];
					}
				}
			}
			$eventi_arr=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiestaFarmaciaTipo($farma_id,4);
			foreach($eventi_arr as $ev){
				if(count($ev)==1){
					$eventi_richiesti['stomaco']=$ev[0]['numero_eventi'];
				}else{
					foreach($ev as $e){
						if($e['inserito_da_agente']) continue;
						$eventi_richiesti['stomaco']=$e['numero_eventi'];
					}
				}
			}
			$eventi_arr=Doctrine_Core::getTable('RichiestaTipoevento')->getRichiestaFarmaciaTipo($farma_id,5);
			foreach($eventi_arr as $ev){
				if(count($ev)==1){
					$eventi_richiesti['gola']=$ev[0]['numero_eventi'];
				}else{
					foreach($ev as $e){
						if($e['inserito_da_agente']) continue;
						$eventi_richiesti['gola']=$e['numero_eventi'];
					}
				}
			}
			print_r($eventi_richiesti);
			
	}	
public function index(){
		$q=Doctrine_Core::getTable('Advisor')->createQuery()->
			select('ad.id,adut.nome_completo,ag.id,agut.nome_completo,adut.id')->
			from('Advisor ad')->
			leftjoin('ad.Utente adut')->
			leftjoin('ad.Agenti ag')->
			leftjoin('ag.Utente agut');
		$r=$q->fetchArray();

		$ag=Doctrine_Core::getTable('Agente')->findOneById('F33');
		$advisors=$ag->getAdvisors();

		$ad=Doctrine_Core::getTable('Advisor')->findOneById('ADV26');
		$result=array();
		$result['id']=$ad->id;
		$result['nome_completo']=$ad->Utente->nome_completo;
	
		print_r($result);

	}*/
public function index(){
	$dati=Doctrine_Core::getTable('Regione')->statisticheNazionali();
	print_r($dati);
}
}