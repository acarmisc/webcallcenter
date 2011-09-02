<?php
class Eventi_4_viewTable extends Doctrine_Table
{
	public static function getInstance()
    {
        return Doctrine_Core::getTable('Eventi_4_view');
    }
	
    public function getTotaleAgente(){
    	$q=$this->createQuery()->
    		select('SUM(ag_4)')->
    		from('Eventi_4_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
    
    public function getTotaleAdvisor(){
    	$q=$this->createQuery()->
    		select('SUM(ad_4)')->
    		from('Eventi_4_view');
    	$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
    }
	
}