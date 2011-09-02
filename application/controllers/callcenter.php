<?php
class Callcenter extends CI_Controller {

	public function __construct()
       {
            parent::__construct();
            
       }

	public function login(){
	
		if (isset($_POST['username'])) Callcenter::check_login();
	
		$this->load->view('callcenter/top');
		$this->load->view('callcenter/login');
		$this->load->view('callcenter/bottom');
	
	}
	
	public function check_login(){
		$query = $this->db->get_where('operatori', array('username'=>$_POST['username'], 'password'=>md5($_POST['password'])));
		if ($query->result() > 0){
			$res = $query->result();
			$this->session->set_userdata('uid',$res[0]->id);
			$this->session->set_userdata('uname',$res[0]->username);
			redirect('c=callcenter&m=welcome');
		}else{
			redirect('c=callcenter&m=login');
			}
	}

	public function index()
	{
		if($this->session->userdata('uid') == ''){
			Callcenter::login();  
		}else{
			Callcenter::welcome();
		} 
	} //ser 234
	
	public function welcome(){
	
		$this->db->select('attivita.giorno, attivita.tipoattivita_id, attivita.stato as attstat, tipoattivita.nome, attivita.id as attid, farmacia.*');
		$this->db->join('farmacia', 'farmacia.id = attivita.farmacia_id');
		$this->db->join('tipoattivita','attivita.tipoattivita_id = tipoattivita.id');
		$this->db->order_by('giorno DESC');
		$this->db->where('giorno >',date('Y-m-d',time()));	
		$this->db->where('giorno <=',date('Y-m-d',time()+604800));	
		$this->db->where('tipoattivita_id !=',2);
		$this->db->where('attivita.deleted_at',NULL);
		
		
		// enable for groupping calls by farmacia_id
		/*
			$this->db->group_by('farmacia_id');
		*/
		
		$query = $this->db->get('attivita');
			
		$this->load->view('callcenter/top');
		$this->load->view('callcenter/index', array('query'=>$query));
		$this->load->view('callcenter/bottom');
	}
	
	public function open_call($id = null){
	
		if(isset($id)){
			$query = $this->db->get_where('calling',array('id'=>$id));
			foreach($query->result() as $r){
				$a = $r->attivita_id;		
			}	
		}else{
			$a = $_GET['id'];
		}
		
		$this->db->select('attivita.*, tipoattivita.nome');
		$this->db->join('tipoattivita','attivita.tipoattivita_id = tipoattivita.id');

		$query = $this->db->get_where('attivita',array('attivita.id'=>$a));
		$attivita = $query->result();
		
		$query = $this->db->get_where('farmacia',array('id'=>$attivita[0]->farmacia_id));
		$farmacia = $query->result();

		// enable for groupping calls by farmacia_id
		/*
		$this->db->select('attivita.*, tipoattivita.nome');
		$this->db->join('tipoattivita','attivita.tipoattivita_id = tipoattivita.id');
		
		$this->db->where('farmacia_id',$attivita[0]->farmacia_id);
		$this->db->where('giorno',$attivita[0]->giorno);
		$query = $this->db->get('attivita');
		$attivita = $query->result();
		*/
		
		// open call to db
		
		$data = array('operatore'=>$this->session->userdata('uid'),
						'start_timestamp'=>time(),
						'esito'=>'current',
						'farmacia_id'=>$farmacia[0]->id,
						'attivita_id'=>$attivita[0]->id);
		
		if(isset($id)){
			$cid = $id;	
		}else{
			$query_sec = $this->db->get_where('calling',array('operatore'=>$this->session->userdata('uid'),
															'farmacia_id'=>$farmacia[0]->id,
															'attivita_id'=>$attivita[0]->id,
															'stato'=>1));
			if($query_sec->num_rows() > 0){
				$res = $query_sec->result();
				$cid = $res[0]->id;
			}else{
				$query = $this->db->insert('calling',$data);
			
				$cid = $this->db->insert_id();
			}
			
		}
		
		$this->session->set_userdata('cid',$cid);
		
		$this->load->view('callcenter/top');
		
		$this->load->view('callcenter/call', array('attivita'=>$attivita, 'farmacia'=>$farmacia,'cid'=>$cid));
	
		$this->load->view('callcenter/bottom');
		
	}
	
	function reopen_call(){
		$this->open_call($_GET['id']);
	}
	
	function save_call(){
		
		$this->db->update('calling',$_POST,array('id' => $_POST['id']));
		$this->db->update('calling',array('end_timestamp'=>time()),array('id' => $_POST['id']));
		
		$this->index();
		
	}
	
	function close_call(){
		//$this->db->update('calling',array('stato'=>0, 'end_timestamp'=>time(),'esito'=>''), array('id' => $this->session->userdata('cid')));
		$this->db->delete('calling',array('id'=>$this->session->userdata('cid')));
	
		$this->index();
	}
	
	function logout(){
		$this->session->sess_destroy();
		redirect('c=callcenter');
	}
	
	function last_calls(){
		$this->load->view('callcenter/top');

		$this->db->order_by('id DESC');
		$this->db->select('calling.*, operatori.username');
		$this->db->join('operatori','operatori.id = calling.operatore');
		$query = $this->db->get_where('calling',array('farmacia_id'=>$_GET['fid'],'attivita_id'=>$_GET['aid']));
		$calls = $query->result();
		
		$this->load->view('callcenter/last_calls', array('calls'=>$calls));
	
		$this->load->view('callcenter/bottom');
	}
	
	
	function history(){
		$this->load->view('callcenter/top');
		
		$this->db->order_by('id DESC');
		$this->db->limit(100);
		
		$this->db->select('calling.*, operatori.username');
		$this->db->join('operatori','operatori.id = calling.operatore');
		$query = $this->db->get_where('calling',array('operatore'=>$this->session->userdata('uid')));
		$calls = $query->result();
		
		$this->load->view('callcenter/history', array('calls'=>$calls));
	
		$this->load->view('callcenter/bottom');
	}
	
	function updateFarma(){
		if($this->db->update('farmacia',$_POST,array('id'=>$_POST['id']))){
			echo '<span class="positive-msg">Salvato.</span>';
		}else{
			echo '<span class="negative-msg">Errore.</span>';		
		}
	}
	
	function prepare_email(){
		$this->load->view('callcenter/prepare_email');
	}
	
	function emailConfirm(){
		
		$this->load->library('email');
		
		$this->email->from('callcenter@csopharmitalia.it', 'Call Center CSO Pharmitalia');

		$this->email->to($_POST['m_to']); 
//		$this->email->to('andrea.carmisciano@gmail.com'); 

		$this->email->bcc('pharma3d@csopharmitalia.it'); 
		
		$this->email->subject($_POST['m_subject']);
		$this->email->message($_POST['m_message']);	
		
		if($this->email->send()){
			echo '<span style="float:right" class="falseButton" onclick="emailCancel()">
	'.img('img/callcenter/cross-button.png').'
</span>';
			echo '<div id="" class="positive-msg" class="falseButton" onclick="emailCancel()">Messaggio inviato correttamente.</div>';
		}else{
					echo '<span style="float:right" class="falseButton" onclick="emailCancel()">
	'.img('img/callcenter/cross-button.png').'
</span>';
			echo '<div id="" class="negative-msg" class="falseButton" onclick="emailCancel()">Errore nell\'invio del messaggio.</div>';
		}
	}
	
	
	function more_mail(){
		
	$query = $this->db->get_where('attivita', array('id'=>$_GET['aid']));
	foreach($query->result() as $r){
		$app = date('d-m-Y',strtotime($r->giorno)).' dalle ore '.$r->ora_inizio;
	}
		
	$msg = "
Gentilissimo Dr..".$_GET['m_farmacista'].",
in seguito alla telefonata intercorsa siamo a ricordarLe che il nosrto
advisor sar&agrave; a disposizione nella Vs. sede il giorno/i giorni :
".$app."

Le ricordiamo che la presenza di questa nuova professionalit&agrave; &egrave; stata
resa possibile da Boehringer Ingelheim CHC
all interno dell innovativo progetto denominato Pharma 3D.
Qualora sorgessero dei problemi sulla data scelta pu&ograve; contattarci a questo
numero: 3457555101 oppure a questa mail: callcenter@csopharmitalia.it

Distinti Saluti. .";

	$data = array('msg'=>$msg);

	$this->load->view('callcenter/more_mail',$data);

	
	}
	
	
	function openq(){
		$this->db->select('calling.*, farmacia.denominazione');
		$this->db->join('farmacia','farmacia.id = calling.farmacia_id');
		$query = $this->db->get_where('calling', array('calling.id'=>$_GET['cid']));
		
		
		$this->load->view('callcenter/questionario', array('chiamata'=>$query->result()));
	
	}
	
	function updateCollaboratore(){
		$this->db->update('reportstorecheck',array('collaboratore_riferimento'=>$_GET['new']), array('id'=>$_GET['id']));
		echo 'ok';
	}
	
}
?>
