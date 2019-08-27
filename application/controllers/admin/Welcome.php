<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		if($this->session->userdata('is_admin_login')){
    		redirect('../admin/dashboard');
        }

		$this->load->view('admin/login');
	}
}
