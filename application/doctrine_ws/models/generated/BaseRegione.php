<?php

/**
 * BaseRegione
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property enum $ripartizione_geografica
 * @property string $nome
 * @property Doctrine_Collection $Province
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseRegione extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('regione');
        $this->hasColumn('ripartizione_geografica', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'Nord-Occidentale',
              1 => 'Nord-Orientale',
              2 => 'Centrale',
              3 => 'Meridionale',
              4 => 'Insulare',
             ),
             'notnull' => false,
             ));
        $this->hasColumn('nome', 'string', 32, array(
             'type' => 'string',
             'fixed' => 0,
             'default' => '',
             'notnull' => true,
             'length' => '32',
             ));

        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'InnoDB');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Provincia as Province', array(
             'local' => 'id',
             'foreign' => 'regione_id'));
    }
}