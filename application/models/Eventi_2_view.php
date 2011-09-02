<?php
Doctrine_Manager::getInstance()->bindComponent('Eventi_2_view', 'default');

class Eventi_2_view extends Doctrine_Record{
	
    public function setTableDefinition()
    {
        $this->setTableName('eventi_2_view');	
        $this->hasColumn('farmacia_id', 'integer', 4, array(
            'type' => 'string',
             'length' => 20,
             'fixed' => true,
             'unsigned' => false,
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
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Farmacia', array(
             'local' => 'id',
             'foreign' => 'id'));
        
    }
}