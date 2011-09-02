<?php
Doctrine_Manager::getInstance()->bindComponent('Farmacie_visitate_view', 'default');

class Farmacie_visitate_view extends Doctrine_Record{
	public function setTableDefinition(){
		$this->setTableName('farmacie_visitate_view');
		$this->hasColumn('id', 'string', 20, array(
        	'type' => 'string',
            'length' => 20,
            'fixed' => true,
            'unsigned' => false,
            'primary' => true,
            'autoincrement' => false,
         ));
        
	    	
	}
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Farmacia', array(
             'local' => 'id',
             'foreign' => 'id'));
        
       
    }
	
	
	
}
