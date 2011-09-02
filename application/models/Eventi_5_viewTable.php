<?php
class Eventi_5_viewTable extends Doctrine_Table
{
	public static function getInstance()
    {
        return Doctrine_Core::getTable('Eventi_5_view');
    }
	
    public function getTotaleAgente(){
    	$q=$this->createQuery()->
    		select('SUM(ag_5)')->
    		from('Eventi_5_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
    
    public function getTotaleAdvisor(){
    	$q=$this->createQuery()->
    		select('SUM(ad_5)')->
    		from('Eventi_5_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
	
}