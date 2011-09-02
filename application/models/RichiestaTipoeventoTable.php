<?php
class RichiestaTipoEventoTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object RegioneTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('RichiestaTipoevento');
    }
    /*
     *Per restituisce un array che ha come indice il tipo evento e come valore il numero eventi
     *di quel tipo richiesti dalla farmacia con il campo inserito_da_agente pari a 0 o 1 
     *a seconda che $da_agente valga False o True.Il numero di
     *componenti dell'array è pari al numero di tipi evento disponibili. In caso di assenza di
     *inserimento il numero eventi è pari a null
     */
    public function getRichiesteFarmacia($farmacia_id,$da_agente){
    	//recupero in un array gli id degli eventi
    	$q=Doctrine_Core::getTable('Tipoevento')->createQuery()->
    		select('id');
    	$tipi=$q->fetchArray();
    	$richieste=array();
    	foreach($tipi as $tipo){
    		$richieste[$tipo['id']]=null;
    	}
    	$q=$this->createQuery()->
    		select('tipoevento_id,numero_eventi')->
    		where('farmacia_id = ?',$farmacia_id)->
 			andwhere('inserito_da_agente=?',$da_agente);
 		$results=$q->fetchArray();
    	foreach($results as $richiesta){
    		$richieste[$richiesta['tipoevento_id']]=$richiesta['numero_eventi'];
    	}
    	return $richieste;
    }
    
    public function getRichiestaFarmaTipo($farmacia,$tipoevento,$da_agente){
    	$q=$this->createQuery()->
    		select('*')->
    		where('inserito_da_agente=?',$da_agente)->
    		andwhere('farmacia_id=?',$farmacia)->
    		andwhere('tipoevento_id=?',$tipoevento);
    		$result=$q->execute();
    		if($result && count($result)>0){
    			return $result[0];
    		}else{
    			return null;
    		}
    }
    

}
    