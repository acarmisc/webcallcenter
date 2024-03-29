<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('UtenteGruppo', 'default');

/**
 * BaseUtenteGruppo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $utente_id
 * @property integer $gruppo_id
 * @property Gruppo $Gruppo
 * @property Utente $Utente
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUtenteGruppo extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('utente_gruppo');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('utente_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('gruppo_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'default' => '0',
             'notnull' => false,
             'autoincrement' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Gruppo', array(
             'local' => 'gruppo_id',
             'foreign' => 'id',
        	 'onDelete' => 'SET NULL',
        	 'onUpdate' => 'CASCADE'));

        $this->hasOne('Utente', array(
             'local' => 'utente_id',
             'foreign' => 'id',
        	 'onDelete' => 'SET NULL',
        	 'onUpdate' => 'CASCADE'));
    }
}