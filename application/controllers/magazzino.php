<?php
class Magazzino extends CI_Controller {

	public function __construct()
       {
            parent::__construct();
            
       }

	public function index($msg = null){
	
		//if (isset($_POST['username'])) Callcenter::check_login();
		
		
		if(isset($_GET['operatore_id'])){
			$this->session->set_userdata('operatore_id',$_GET['operatore_id']);
		}
	
		$this->db->select('magazzino.*, utente.nome_completo, advisor.id as adv_id');
		$this->db->join('advisor','advisor.id = magazzino.codice_adv');
		$this->db->join('utente','utente.id = advisor.utente_id');
		$this->db->order_by('magazzino.id DESC');
		$this->db->where('sonof','');
		$query = $this->db->get('magazzino');


	
		$this->load->view('magazzino/top');
		$this->load->view('magazzino/index',array('msg'=>$msg,'orders'=>$query->result()));
		$this->load->view('magazzino/bottom');
	
	}
	
	
	public function login(){
	
	}
	
	public function just_list(){
	
		$this->db->select('magazzino.*, utente.nome_completo, advisor.id as adv_id');
		$this->db->join('advisor','advisor.id = magazzino.codice_adv');
		$this->db->join('utente','utente.id = advisor.utente_id');
		$this->db->order_by('magazzino.id DESC');
		$query = $this->db->get('magazzino');


		$this->load->view('magazzino/list',array('orders'=>$query->result()));
	
	}
	
	public function save_order(){
	
		if($this->db->insert('magazzino',$_POST)){
		
			$this->index('saved');
		
		}else{
		
			$this->index('err');
			
		}
	
	}
	
	public function update_order(){
	
		$this->db->update('magazzino',array($_GET['t']=>$_GET['v']), array(('id')=>$_GET['id']));
		$this->advisor_view($_GET['adv']);
	
	}
	
	public function delete_order(){
	
		$this->db->delete('magazzino', array('id'=>$_GET['id']));
		$this->just_list();
	
	}
	
	public function it_arrives(){
	
		$this->db->update('magazzino',array('arrivato'=>1),array('id'=>$_GET['id']));
		// e poi!??!
		
	}
	
	public function per_advisor_list(){
		
		if(isset($_GET['id'])){
			$id = $_GET['id'];
		}else{
			$id = $this->uri->segment(3);
		}
		
		$this->db->select('magazzino.*, utente.nome_completo, advisor.id as adv_id');
		$this->db->join('advisor','advisor.id = magazzino.codice_adv');
		$this->db->join('utente','utente.id = advisor.utente_id');
		$this->db->order_by('magazzino.id DESC');
		
		$query = $this->db->get_where('magazzino',array('codice_adv'=>$id));
		
		echo '<a href="?c=magazzino">&#8249; indietro</a>';
		
		$this->load->view('magazzino/list',array('orders'=>$query->result()));	
	}
	
	public function advisor_view($adv = null){
		
		if($adv){
			$id = $adv;
		}else{
			if(isset($_GET['id'])){
				$id = $_GET['id'];
			}else{
				$id = $this->uri->segment(3);
			}
		}
		
		
		$this->db->select('magazzino.*, utente.nome_completo, advisor.id as adv_id');
		$this->db->join('advisor','advisor.id = magazzino.codice_adv');
		$this->db->join('utente','utente.id = advisor.utente_id');
		$this->db->order_by('magazzino.id DESC');
		
		$query = $this->db->get_where('magazzino',array('codice_adv'=>$id));
		
		//echo '<a href="?c=magazzino">&#8249; indietro</a>';
		$this->load->view('magazzino/top');
		$this->load->view('magazzino/advisor_view',array('orders'=>$query->result()));	
		$this->load->view('magazzino/bottom');
	}
	
	function newone(){
		$this->load->view('magazzino/new');
	}
	
}

?>