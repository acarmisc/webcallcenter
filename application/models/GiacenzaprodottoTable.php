<?php

class GiacenzaprodottoTable extends Doctrine_Table{
 	public static function getInstance()
    {
        return Doctrine_Core::getTable('Giacenzaprodotto');
    }
    
    public function getGiacenzeStorecheck($storecheck_id){
    	//recupero in un array gli id degli eventi
    	$q=Doctrine_Core::getTable('Prodotto')->createQuery()->
    		select('id');
    	$prodotti=$q->fetchArray();
    	$giacenze=array();
    	foreach($prodotti as $prodotto){
    		$giacenze[$prodotto['id']]=null;
    	}
    	$q=$this->createQuery()->
    		select('prodotto_id,quantita')->
    		where('reportstorecheck_id = ?',$storecheck_id);
 		$results=$q->fetchArray();
    	foreach($results as $giac){
    		$giacenze[$giac['prodotto_id']]=$giac['quantita'];
    	}
    	return $giacenze;
    }
    
    
}