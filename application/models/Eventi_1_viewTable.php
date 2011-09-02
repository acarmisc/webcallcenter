<?php
class Eventi_1_viewTable extends Doctrine_Table
{
	public static function getInstance()
    {
        return Doctrine_Core::getTable('Eventi_1_view');
    }
	
    public function getTotaleAgente(){
    	$q=$this->createQuery()->
    		select('SUM(ag_1)')->
    		from('Eventi_1_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
    
    public function getTotaleAdvisor(){
    	$q=$this->createQuery()->
    		select('SUM(ad_1)')->
    		from('Eventi_1_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
	
}