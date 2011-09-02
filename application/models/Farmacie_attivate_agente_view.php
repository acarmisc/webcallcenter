<?php
Doctrine_Manager::getInstance()->bindComponent('Farmacie_attivate_agente_view', 'default');

class Farmacie_attivate_agente_view extends Doctrine_Record{
	public function setTableDefinition(){
		$this->setTableName('farmacie_attivate_agente_view');
		$this->hasColumn('id', 'string', 20, array(
        	'type' => 'string',
            'length' => 20,
            'fixed' => true,
            'unsigned' => false,
            'primary' => true,
            'autoincrement' => false,
         ));
        $this->hasColumn('advisor_id', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('agente_id', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('provincia_id', 'string', 4, array(
             'type' => 'string',
             'length' => 4,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('linea_id', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             'fixed' => true,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('microbrick_id', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             'fixed' => true,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
	    	
	}
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Farmacia', array(
             'local' => 'id',
             'foreign' => 'id'));
        
        $this->hasOne('Advisor', array(
             'local' => 'advisor_id',
             'foreign' => 'id'
        ));

        $this->hasOne('Agente', array(
             'local' => 'agente_id',
             'foreign' => 'id'
        ));

        $this->hasOne('Linea', array(
             'local' => 'linea_id',
             'foreign' => 'id'
        ));

        $this->hasOne('Microbrick', array(
             'local' => 'microbrick_id',
             'foreign' => 'id'
        ));

        $this->hasOne('Provincia', array(
             'local' => 'provincia_id',
             'foreign' => 'id'
        ));
        
                $this->hasOne('Eventi_1_view', array(
             'local' => 'id',
             'foreign' => 'id'));
        
        $this->hasOne('Eventi_2_view', array(
             'local' => 'id',
             'foreign' => 'id'));
        
        $this->hasOne('Eventi_3_view', array(
             'local' => 'id',
             'foreign' => 'id'));
        
        $this->hasOne('Eventi_4_view', array(
             'local' => 'id',
             'foreign' => 'id'));
        
        $this->hasOne('Eventi_5_view', array(
             'local' => 'id',
             'foreign' => 'id'));
    }
	
	
	
}
