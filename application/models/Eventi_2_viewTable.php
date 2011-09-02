<?php
class Eventi_2_viewTable extends Doctrine_Table
{
	public static function getInstance()
    {
        return Doctrine_Core::getTable('Eventi_2_view');
    }
	
    public function getTotaleAgente(){
    	$q=$this->createQuery()->
    		select('SUM(ag_2)')->
    		from('Eventi_2_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
    
    public function getTotaleAdvisor(){
    	$q=$this->createQuery()->
    		select('SUM(ad_2)')->
    		from('Eventi_2_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
	
}