<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Dokter extends CI_Controller {

	public function __construct(){
		 parent::__construct();
		$this->load->model('DokterM', 'yeah');
		if($this->session->level != 'Dokter'){
			redirect('C_Login');
		};
		
	}

	public function index()
	{
		$this->load->view('dokter/home');
	}
	public function Pasien(){
		$data['pasien'] = $this->yeah->listPasien();
		$this->load->view('dokter/pasien',$data);	
	}
	public function rekamMedis(){
		$idpasien = $this->uri->segment(3);
		$data['rekam'] = $this->yeah->rekamMedis($idpasien);
		$this->load->view('dokter/rekammedis',$data);	

	}
	public function editRekam(){
		$id = $this->uri->segment(3);
		$data['rekam'] = $this->yeah->geteditData('rekammedis',$id,'noRM')->result();
		$this->load->view('dokter/edit_rekam',$data);	
	}
	public function addRekam(){
		$this->load->view('dokter/tambah_rekam');	
	}
	public function tambah_rekam(){
		$data = array(
		'tglPemeriksaan'=> html_escape($this->input->post('tanggal')),
		'anamnesa' => html_escape($this->input->post('ana')),
		'diagnosa' => html_escape($this->input->post('diagnosa')),
		'pengobatan' => html_escape($this->input->post('terapi')),
		'detPengobatan' => html_escape($this->input->post('det')),
		'idDokter' => $this->session->userdata('id_dokter'),
		'idPasien' =>  html_escape($this->input->post('id')),
		);
		if($this->yeah->addData($data,'rekammedis')){
			$message = array('message'=>'Data Rekam Medis berhasil ditambah', 'class'=>'alert alert-primary');
			$this->session->set_flashdata('item', $message);
		}else{
			$message = array('message'=>'Data Rekam Medis gagal ditambahkan', 'class'=>'alert alert-danger');
			$this->session->set_flashdata('item', $message);			
		}
		redirect('C_Dokter/rekamMedis/'.html_escape($this->input->post('id')));

	}
	public function prosesEditR(){

		$data = array(
		'tglPemeriksaan'=> html_escape($this->input->post('tanggal')),
		'anamnesa' => html_escape($this->input->post('ana')),
		'diagnosa' => html_escape($this->input->post('diagnosa')),
		'terapi' => html_escape($this->input->post('terapi')),
		'ketTerapi' => html_escape($this->input->post('detail')),
		'idPasien' =>  html_escape($this->input->post('idp')),
		);
		$id = $this->input->post('idr');
		$id2 = $this->input->post('idp');
		if($this->yeah->editData($data,'rekammedis',$id,'noRM')){
			$message = array('message'=>'Data Rekam Medis berhasil diubah', 'class'=>'alert alert-primary');
			$this->session->set_flashdata('item', $message);
		}else{
			$message = array('message'=>'Data  Rekam Medis gagal diubah', 'class'=>'alert alert-danger');
			$this->session->set_flashdata('item', $message);			
		}
		redirect('C_Dokter/rekamMedis/'.$id2);

	}
	public function hapusRekam(){
		$id = $this->uri->segment(3);
		$id2 = $this->uri->segment(4);
		$this->db->where('noRM',$id);
		$this->db->delete('rekammedis');
		redirect('C_Dokter/rekamMedis/'.$id2);
	}
}