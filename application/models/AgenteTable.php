<?php

/**
 * AgenteTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class AgenteTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object AgenteTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Agente');
    }
    
//Restituisce un array di array (id=>nome_completo) con id codice dell'Agente e nome il suo nome completo
    public function elencoNomi(){
    	$q=$this->createQuery()->
    		select('a.id, u.nome_completo,u.id ')->
    		from('Agente a')->
    		leftjoin('a.Utente u')->
    		orderBy('u.nome_completo');
		if(!$r=$q->fetchArray()){
			return array();
		}
		foreach($r as $re){
			$result[$re['id']]=$re['Utente']['nome_completo'];
		}
		return $result;
    }
    
	public function getAgentiPerAdvisor($advisor_id){
		$q=$this->createQuery()->
			select('DISTINCT a.*')->
			from('Agente a')->
			leftjoin('a.Farmacia f')->
			where('f.advisor_id=?',$advisor_id);
		if($result=$q->fetchArray()){
			return $result;
		}else{
			return array();
		}
	}
	/*
   public function statistiche(){
		$q=Doctrine_Query::create()->
    		select('ag.id,ut.nome_completo as nome, COUNT(fa.id) as numero')->
    		from('Agente ag')->
    		leftjoin('ag.Utente ut')->
    		leftjoin('ag.Farmacie fa')->
    		groupBy('nome')->
    		orderBy('nome');
    	$r=$q->execute();

    		$p=Doctrine_Query::create()->
    			select('ag.id, ut.nome_completo as nome,COUNT(fa.id) as attive_ag')->
    			from('Agente ag')->
    			innerJoin('ag.Farmacie f')->
    			leftJoin('f.Farmacie_attivate_agente_view fa')->
    			leftjoin('ag.Utente ut')->
    			groupBy('nome')->
    			orderBy('nome');
    		$q=$p->execute();
    		$p=Doctrine_Query::create()->
    			select('ag.id, ut.nome_completo as nome,COUNT(fa.id) as attive_adv')->
    			from('Agente ag')->
    			innerJoin('ag.Farmacie f')->
    			leftJoin('f.Farmacie_attivate_advisor_view fa')->
    			leftjoin('ag.Utente ut')->
    			groupBy('nome')->
    			orderBy('nome');
    		$q=$p->execute();
			$tipi=Doctrine_Core::getTable('Tipoevento')->findAll();
			foreach($tipi as $tipo){
	    		$s=Doctrine_Query::create()->
	    			select('ag.id, ut.nome_completo as nome,SUM(ad_'.$tipo->id.') as numero'.$tipo->id)->
	    			from('Agente ag')->
	    			leftjoin('ag.Utente ut')->
	    			innerjoin('ag.Farmacie fa')->
	    			leftjoin('fa.Eventi_'.$tipo->id.'_view ri')->
	    			groupBy('nome')->
	    			orderBy('nome');
	    		$t=$s->execute();
			}	
		return $q->toArray();
    }*/
public function statistiche(){
		$q=Doctrine_Query::create()->
    		select('ag.id,ut.nome_completo as nome, COUNT(fa.id) as numero')->
    		from('Agente ag')->
    		leftjoin('ag.Utente ut')->
    		innerjoin('ag.Farmacie fa')->
    		groupBy('nome')->
    		orderBy('nome');
    	$r1=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);

    		$q=Doctrine_Query::create()->
    			select('ag.id, ut.nome_completo as nome,COUNT(fa.id) as attive_ag')->
    			from('Agente ag')->
    			innerJoin('ag.Farmacie f')->
    			leftJoin('f.Farmacie_attivate_agente_view fa')->
    			leftjoin('ag.Utente ut')->
    			groupBy('nome')->
    			orderBy('nome');
    		$r2=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    		$q=Doctrine_Query::create()->
    			select('ag.id, ut.nome_completo as nome,COUNT(fa.id) as attive_adv')->
    			from('Agente ag')->
    			innerJoin('ag.Farmacie f')->
    			leftJoin('f.Farmacie_attivate_advisor_view fa')->
    			leftjoin('ag.Utente ut')->
    			groupBy('nome')->
    			orderBy('nome');
    		$r3=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
			$tipi=Doctrine_Core::getTable('Tipoevento')->findAll();
			foreach($tipi as $tipo){
	    		$q=Doctrine_Query::create()->
	    			select('ag.id, ut.nome_completo as nome,SUM(ad_'.$tipo->id.') as numero'.$tipo->id.',SUM(ag_'.$tipo->id.') as numero_ag_'.$tipo->id)->
	    			from('Agente ag')->
	    			leftjoin('ag.Utente ut')->
	    			innerjoin('ag.Farmacie f')->
	    			leftJoin('f.Farmacie_attivate_agente_view fa')->
	    			leftjoin('fa.Eventi_'.$tipo->id.'_view ri')->
	    			groupBy('nome')->
	    			orderBy('nome');
			${'s'.$tipo->id}=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
				  $z=Doctrine_Query::create()->
	    			select('ag.id, ut.nome_completo as nome,SUM(ad_'.$tipo->id.') as numero_attadv'.$tipo->id)->
	    			from('Agente ag')->
	    			leftjoin('ag.Utente ut')->
	    			innerjoin('ag.Farmacie f')->
	    			leftJoin('f.Farmacie_attivate_advisor_view fa')->
	    			leftjoin('fa.Eventi_'.$tipo->id.'_view ri')->
	    			groupBy('nome')->
	    			orderBy('nome');
			${'t'.$tipo->id}=$z->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
			}	
		$r=array();
		foreach($r1 as $chiave => $valore){
			$r[$chiave]=$valore+$r2[$chiave]+$r3[$chiave];
			foreach($tipi as $tipo){
				$r[$chiave]=$r[$chiave]+${'s'.$tipo->id}[$chiave]+${'t'.$tipo->id}[$chiave];
			}
		}
		return $r;
    }
}