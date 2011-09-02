<?php
class ReporteventoTable extends Doctrine_Table{
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Reportevento');
    }
    
    public function getGradiSoddisfazione(){
    	return array('molto soddifatto','soddisfatto','insoddisfatto');
    }
    
   public function getNumeroEventi(){
   		$q=$this->createQuery()->
   			select('COUNT(*)')->
   			from('Reportevento')->
   			where('deleted_at is null');
   		$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
   }
}