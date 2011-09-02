<?php
class Doctrine_Tools extends CI_Controller{
	function create_tables(){
		Doctrine::loadModels(APPPATH.'/doctrine_ws/models');
		echo "Assicurati che le tabelle non esistano già";
		echo "<br>";
		echo "<form action='' method='POST'>";
		echo '<input type="submit" name="action" value="Crea tabelle"/>';
		if($this->input->post('action')){
			Doctrine::createTablesFromModels(APPPATH.'doctrine_ws/models/');
			//Doctrine::createTablesFromModels();
			echo "Fatto!";
		}
		echo '</form>';
	}
	function create_models_fromDb(){
		echo 'verificare che la cartella dei model sia vuota<br>';
		echo '<form action="" method="POST">';
		echo '<input type="submit" name="action" value="Genera modello/i" />';
		echo '</form>';
		if ($this->input->post('action')=='Genera modello/i'){
			$dest=APPPATH.'/doctrine_ws/models/';
			Doctrine::generateModelsFromDb($dest,array('generateTableClasses' => true));		
		}
	}
	function create_models(){
		Doctrine::loadModels(APPPATH.'/doctrine_ws/models');
		echo "specificare un file yml, altrimenti verranno creati i modelli per tutti gli yml presenti";
		echo '<form action="" method="POST">';
		echo '<input type="text" name="file" />';
		echo '<input type="submit" name="action" value="Genera modello/i" />';
		echo '</form>';
		if ($this->input->post('action')=='Genera modello/i'){
			$file=APPPATH.'/doctrine_ws/schema/';
			$dest=APPPATH.'/doctrine_ws/models/';
			if($this->input->post('file')){
				$file.=$this->input->post('file');
			}
			Doctrine::generateModelsFromYaml($file,$dest,array('generateTableClasses' => true));		
		}
	}
	function create_yaml(){
		Doctrine::loadModels(APPPATH.'/doctrine_ws/models');
		echo "specificare un file model, altrimenti verranno creati gli yaml per tutti i model presenti";
		echo '<form action="" method="POST">';
		echo '<input type="text" name="file" />';
		echo '<input type="submit" name="action" value="Genera yaml" />';
		echo '</form>';
		if ($this->input->post('action')=='Genera yaml'){
			$file=APPPATH.'/doctrine_ws/models/';
			$dest=APPPATH.'/doctrine_ws/schema/generato.yml';
			if($this->input->post('file')){
				$file.=$this->input->post('file');
			}
			Doctrine::generateYamlFromModels($dest,$file);		
		}
			
	}
		
	function load_fixtures(){
		Doctrine::loadModels(APPPATH.'/doctrine_ws/models');
		echo 'This will delete all exiting data!<br />
		<form action="" method="POST">
		<input type="submit" name="action" value="Load Fixtures" /> <br /><br />
		</form>';
		
		if ($this->input->post('action')=='Load Fixtures'){
			//necessario per evitare problemi di relazioni se si eliminano i dati già presenti
			Doctrine_Manager::connection()->execute( 'SET FOREIGN_KEY_CHECKS = 0');
			//Se si desidera caricare uno specifico file yml, fornire esattamente quello. Aggiungere true per aggiungere i dati e non sostituirli.
			Doctrine::loadData(APPPATH.'/doctrine_ws/fixtures');
			echo "Done!";
		}
	}
}