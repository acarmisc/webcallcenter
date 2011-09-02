<?php

/**
 * ProvinciaTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ProvinciaTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object ProvinciaTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Provincia');
    }
    
    //Restituisce un array di array (id,nome) con id la sigla della provincia
    public function elencoRidotto(){
    	$q=$this->createQuery()->
    		select('id, nome ')->
    		orderBy('nome');
    	$r=$q->fetchArray();
    	foreach($r as $p){
    		$result[$p['id']]=$p['nome'];
    	}
    	return $result;
    }
    
   public function statisticheProvinciali(){
		$q=Doctrine_Query::create()->
    		select('pr.id,pr.nome, COUNT(fa.id) as numero')->
    		from('Provincia pr')->
    		innerjoin('pr.Farmacie fa')->
    		groupBy('pr.nome')->
    		orderBy('pr.nome');
    	$r1=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);

    		$q=Doctrine_Query::create()->
    			select('pr.id,pr.nome, COUNT(fa.id) as attive_ag')->
    			from('Provincia pr')->
    			innerJoin('pr.Farmacie f')->
    			leftJoin('f.Farmacie_attivate_agente_view fa')->
    			groupBy('pr.nome')->
    			orderBy('pr.nome');
    		$r2=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    		$q=Doctrine_Query::create()->
    			select('pr.id,pr.nome, COUNT(fa.id) as attive_adv')->
    			from('Provincia pr')->
    			innerJoin('pr.Farmacie f')->
    			leftJoin('f.Farmacie_attivate_advisor_view fa')->
    			groupBy('pr.nome')->
    			orderBy('pr.nome');
    		$r3=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
			$tipi=Doctrine_Core::getTable('Tipoevento')->findAll();
			foreach($tipi as $tipo){
	    		$q=Doctrine_Query::create()->
	    			select('pr.id,pr.nome, SUM(ad_'.$tipo->id.') as numero'.$tipo->id.',SUM(ag_'.$tipo->id.') as numero_ag_'.$tipo->id)->
	    			from('Provincia pr')->
	    			innerjoin('pr.Farmacie f')->
	    			leftJoin('f.Farmacie_attivate_agente_view fa')->
	    			leftjoin('fa.Eventi_'.$tipo->id.'_view ri')->
	    			groupBy('pr.nome')->
	    			orderBy('pr.nome');
			${'s'.$tipo->id}=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
				$z=Doctrine_Query::create()->
					select('pr.id,pr.nome, SUM(ad_'.$tipo->id.') as numero_attadv'.$tipo->id)->
					from('Provincia pr')->
	    			innerjoin('pr.Farmacie f')->
	    			leftJoin('f.Farmacie_attivate_advisor_view fa')->
	    			leftjoin('fa.Eventi_'.$tipo->id.'_view ri')->
	    			groupBy('pr.nome')->
	    			orderBy('pr.nome');
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