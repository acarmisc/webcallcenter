<?php
class ReportstorecheckTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object RegioneTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Reportstorecheck');
    }
    
    //Restituisce i valori di enum di giorno chiusura
    public function getGiorniChiusuraEnum(){
    	return array(
    		1=>'lunedì',
    		2=>'martedì',
    		3=>'mercoledì',
    		4=>'giovedì',
    		5=>'venerdì',
    		6=>'sabato',
    		7=>'domenica',
			8=>'sab-lun',
			9=>'turnazione'
    	);
    }
    
    public function getStarchannelEnum(){
    	return array(
    		1=>'non effettuata',
    		2=>'effettuata con successo',
    		3=>'effettuata senza successo'
    	);
    }
    
    public function getFasceNegozioEnum(){
    	return array(
    		1=>'30-60 mq',
    		2=>'60-120 mq',
    		3=>'120-200 mq',
    		4=>'120-300 mq',
    		5=>'oltre 300 mq'
    		);
    }
    
    public function getFasceMagazzinoEnum(){
    	return array(
    		1=>'non ha magazzino',
    		2=>'sino a 20 mq',
    		3=>'20-50 mq',
    		4=>'oltre 50 mq'
    	);
    }
    
    public function getVicinanzaEnum(){
    	return array(
    		1=>'Aeroporto/Terminal',
    		2=>'Ambulatorio medico',
    		3=>'Centro commerciale',
    		4=>'Casa di cura',
    		5=>'Ospedale',
    		6=>'Stazione ferroviaria',
    		7=>'Terminal portuale',
    		8=>'Fermata BUS/tram/metro',
    		9=>'Centro estetico-benessere',
    		10=>'Palestra-centro fitness',
    		11=>'Circolo ricreativo',
    		12=>'Zona shopping',
    		13=>'Nulla'
    	);
    }
    
    public function getMqCalpestabiliEnum(){
    	return array(
    		1=>'30-60 mq',
    		2=>'60-120 mq',
    		3=>'120-200 mq',
    		4=>'120-300 mq',
    		5=>'oltre 300 mq'
    	);
    }
    	
    public function getLocalizzazioneEnum(){
    	return array(
    		1=>'centro città',
    		2=>'centro storico',
    		3=>'semicentro',
    		4=>'periferia',
    		5=>'ztl',
    		6=>'area pedonale',
    		7=>'località balneare',
    		8=>'turistica',
    		9=>'zona residenziale',
    		10=>'interno centro commerciale'
    	);
    }
    
   public function getNumeroStorecheck(){
   		$q=$this->createQuery()->
   			select('COUNT(*)')->
   			from('Reportstorecheck')->
   			where('deleted_at is null');
   		$r=$q->execute(array(),Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    	return $r;
   }
    
}