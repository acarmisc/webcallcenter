<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Microbrick', 'default');

/**
 * BaseMicrobrick
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $id
 * @property string $descrizione
 * @property string $provincia_id
 * @property string $microarea_id
 * @property Microarea $Microarea
 * @property Provincia $Provincia
 * @property Doctrine_Collection $Farmacie
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMicrobrick extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('microbrick');
        $this->hasColumn('id', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             'fixed' => true,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('descrizione', 'string', 20, array(
             'type' => 'string',
             'length' => 20,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('provincia_id', 'string', 2, array(
             'type' => 'string',
             'length' => 2,
             'fixed' => true,
             'unsigned' => false,
             'primary' => false,
             'default' => '',
             'notnull' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('microarea_id', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             'fixed' => true,
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
        $this->hasOne('Microarea', array(
             'local' => 'microarea_id',
             'foreign' => 'id'));

        $this->hasOne('Provincia', array(
             'local' => 'provincia_id',
             'foreign' => 'id'));

        $this->hasMany('Farmacia as Farmacie', array(
             'local' => 'id',
             'foreign' => 'microbrick_id'));
    }
}