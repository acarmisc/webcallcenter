<?php

/**
 * BaseLinea
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $id
 * @property string $descrizione
 * @property Doctrine_Collection $Farmacia
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseLinea extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('linea');
        $this->hasColumn('id', 'string', 10, array(
             'type' => 'string',
             'primary' => true,
             'fixed' => 1,
             'length' => '10',
             ));
        $this->hasColumn('descrizione', 'string', 20, array(
             'type' => 'string',
             'default' => '',
             'length' => '20',
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Farmacia', array(
             'local' => 'id',
             'foreign' => 'linea_id'));
    }
}