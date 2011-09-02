<?php
class MicrobrickTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object ProvinciaTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Microbrick');
    }
    
   public function statistiche(){
		$q=Doctrine_Query::create()->
    		select('mi.id,mi.descrizione as nome, COUNT(fa.id) as numero')->
    		from('Microbrick mi')->
    		leftjoin('mi.Farmacie fa')->
    		groupBy('nome')->
    		orderBy('nome');
    	$r1=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);


    		$q=Doctrine_Query::create()->
    			select('mi.id, COUNT(fa.id) as attive_ag')->
    			from('Microbrick mi')->
    			innerjoin('mi.Farmacie f')->
    			leftJoin('f.Farmacie_attivate_agente_view fa')->    			
    			groupBy('mi.Descrizione')->
    			orderBy('mi.Descrizione');
    		$r2=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
    		$q=Doctrine_Query::create()->
    			select('mi.id, COUNT(fa.id) as attive_adv')->
    			from('Microbrick mi')->
    			leftjoin('mi.Farmacie f')->
    			leftJoin('f.Farmacie_attivate_advisor_view fa')->    			
    			groupBy('mi.Descrizione')->
    			orderBy('mi.Descrizione');
    		$r3=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
			$tipi=Doctrine_Core::getTable('Tipoevento')->findAll();
			foreach($tipi as $tipo){
	    		$q=Doctrine_Query::create()->
	    			select('mi.id, SUM(ad_'.$tipo->id.') as numero'.$tipo->id.',SUM(ag_'.$tipo->id.') as numero_ag_'.$tipo->id)->
	    			from('Microbrick mi')->
	    			innerjoin('mi.Farmacie f')->
	    			leftJoin('f.Farmacie_attivate_agente_view fa')->
	    			leftjoin('fa.Eventi_'.$tipo->id.'_view ri')->
	    			groupBy('mi.Descrizione')->
	    			orderBy('mi.Descrizione');
				${'s'.$tipo->id}=$q->execute(array(),Doctrine_Core::HYDRATE_ARRAY);
				$z=Doctrine_Query::create()->
					select('mi.id, SUM(ad_'.$tipo->id.') as numero_attadv'.$tipo->id)->
	    			from('Microbrick mi')->
	    			innerjoin('mi.Farmacie f')->
	    			leftJoin('f.Farmacie_attivate_advisor_view fa')->
	    			leftjoin('fa.Eventi_'.$tipo->id.'_view ri')->
	    			groupBy('mi.Descrizione')->
	    			orderBy('mi.Descrizione');
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