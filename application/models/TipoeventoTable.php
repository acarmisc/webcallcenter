<?php 
class TipoeventoTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object TipoeventoObject
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Tipoevento');
    }
    
   //Restituisce un array di array (id,nome) con id la sigla della provincia
    public function elencoRidotto(){
    	$q=$this->createQuery()->
    		select('id, nome ')->
    		orderBy('nome');
    	return $q->fetchArray();
    }
}