<?php
class Eventi_3_viewTable extends Doctrine_Table
{
	public static function getInstance()
    {
        return Doctrine_Core::getTable('Eventi_3_view');
    }
	
    public function getTotaleAgente(){
    	$q=$this->createQuery()->
    		select('SUM(ag_3)')->
    		from('Eventi_3_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
    
    public function getTotaleAdvisor(){
    	$q=$this->createQuery()->
    		select('SUM(ad_3)')->
    		from('Eventi_3_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
	
}