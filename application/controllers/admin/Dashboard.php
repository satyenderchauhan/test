<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->content_data['userData']=$this;
        
        if(!$this->session->userdata('is_admin_login')){
            redirect(base_url().'admin');
        }

        $this->active ='admin/dashboard';
        $this->load->model('Common_model','common');
    }

	public function index()
	{
        $data = [];

        $data["total_emp"] = $this->common->countTableData(['db'=> 'read', 'pcolumn'=>'id', 'ptable'=>'user_accounts as ua', 'column'=>['id as id'], 'where'=>' WHERE ua.role = 2']);
        
        $data["enabled_emp"] = $this->common->countTableData(['db'=> 'read', 'pcolumn'=>'id', 'ptable'=>'user_accounts as ua', 'column'=>['id as id'], 'where'=>' WHERE ua.role = 2 and ua.status = 1']);
        
        $data["total_occupation"] = $this->common->countTableData(['db'=> 'read', 'pcolumn'=>'id', 'ptable'=>'occupation as o', 'column'=>['id as id']]);

        $this->active = 'dashboard';
        $this->load->view('admin/dashboard',$data);
	}
}
