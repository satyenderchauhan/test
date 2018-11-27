<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->content_data['userData']=$this;
        
        if(!$this->session->userdata('is_login')){
            redirect(base_url());
        }

        $this->active ='';
    }

	public function index()
	{
        $this->active = 'dashboard';
        $this->load->view('dashboard');
	}
}
