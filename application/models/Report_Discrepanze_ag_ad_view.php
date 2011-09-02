<?php
Doctrine_Manager::getInstance()->bindComponent('Discrepanze_ag_ad_view', 'default');

class Discrepanze_ag_ad_view extends Doctrine_Record{
	
	public function setTableDefinition(){
		$this->setTableName('discrepanze_ag_ad_view');
		$this->hasColumn('id', 'integer', 4, array(
            'type' => 'string',
             'length' => 20,
             'fixed' => true,
             'unsigned' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
                $this->hasColumn('ag_1', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             )); 
        $this->hasColumn('ad_1', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             ));  
        $this->hasColumn('ag_2', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             )); 
        $this->hasColumn('ad_2', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             )); 
        $this->hasColumn('ag_3', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             )); 
        $this->hasColumn('ad_3', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             )); 
        $this->hasColumn('ag_4', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             )); 
        $this->hasColumn('ad_4', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             ));   
        $this->hasColumn('ag_5', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             )); 
        $this->hasColumn('ad_5', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => true,
             'autoincrement' => false,
             )); 
		$this->hasColumn('denominazione', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => true,
             'autoincrement' => false,
             ));  
         $this->hasColumn('localita', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
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
         $this->hasColumn('agente', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
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
        $this->hasOne('Provincia', array(
             'local' => 'provincia_id',
             'foreign' => 'id'));
        
    }
}