<?php
class Farmacie_visitate_viewTable extends Doctrine_Table
{
	public static function getInstance()
    {
        return Doctrine_Core::getTable('Farmacie_visitate_view');
    }
	
    public function getNumeroFarmacieVisitate(){
    	$q=$this->createQuery()->
    		select('COUNT(*)')->
    		from('Farmacie_visitate_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
    
	
}