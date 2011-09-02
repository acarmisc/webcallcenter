<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Agente', 'default');

/**
 * BaseAgente
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $id
 * @property integer $utente_id
 * @property Utente $Utente
 * @property Doctrine_Collection $Farmacie
 * @property Doctrine_Collection $Advisors
 * @property Doctrine_Collection $Province
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAgente extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('agente');
        $this->hasColumn('id', 'string', 10, array(
             'type' => 'string',
             'length' => 10,
             'fixed' => true,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('utente_id', 'integer', 4, array(
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
        $this->hasOne('Utente', array(
             'local' => 'utente_id',
             'foreign' => 'id',
        	 'onUpdate' => 'CASCADE'));

        $this->hasMany('Farmacia as Farmacie', array(
             'local' => 'id',
             'foreign' => 'agente_id'));
        
        $this->hasMany('Advisor as Advisors', array(
             'refClass' => 'Farmacia',
             'local' => 'agente_id',
             'foreign' => 'advisor_id'));
       
        $this->hasMany('Provincia as Province', array(
        	  'refClass' => 'Farmacia',
        	  'local' => 'agente_id',
        	  'foreign' => 'provincia_id'));
        
        
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($softdelete0);
    }
}