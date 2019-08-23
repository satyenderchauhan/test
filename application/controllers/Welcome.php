<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
    {
    	parent::__construct();
        $this->load->model('Common_model','common');
    }

	public function index()
	{
		$data = [
			'occupations' => []
		];

		$param  = [
            'db'      => 'read',
            'ptable'  => 'occupation',
            'column'  => ['*'],
        ];
        
        $all_occupations = $this->common->getTableData($param);

        if($all_occupations){
            $data['occupations'] = $all_occupations;
        }

		$this->load->view('front/home',$data);
	}
}
