<?php
Doctrine_Manager::getInstance()->bindComponent('Eventi_5_view', 'default');

class Eventi_5_view extends Doctrine_Record{
	
    public function setTableDefinition()
    {
        $this->setTableName('eventi_5_view');	
        $this->hasColumn('farmacia_id', 'integer', 4, array(
            'type' => 'string',
             'length' => 20,
             'fixed' => true,
             'unsigned' => false,
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
    }
    
    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Farmacia', array(
             'local' => 'id',
             'foreign' => 'id'));
        
    }
}