<?php
class ReportpreeventoTable extends Doctrine_Table{
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Reportpreevento');
    }

    public function getPartecipazione(){
    	return array('ottima','media','poca');
    }
    
   public function getNumeroPreeventi(){
   		$q=$this->createQuery()->
   			select('COUNT(*)')->
   			from('Reportpreevento')->
   			where('deleted_at is null');
   		$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
   }
}