<?php

/**
 * Utente
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Utente extends BaseUtente
{
	public function setUp(){
		parent::setUp();
		$this->hasMutator('password','md5Password');
	}
	public function md5Password($value){
		$this->_set('password',md5('S@lt3dPassw0rd'.$value));
	}
}