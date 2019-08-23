<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

	public function __construct()
    {
    	parent::__construct();
        $this->load->model('Common_model','common');
    }

	public function index()
	{
        if(!@$_GET['occupation']){
            redirect(base_url());
        }

        $occupation = str_replace('_', ' ', $_GET['occupation']);

        $column      = [
            'id'                => 'ua.id',
            'first_name'        => 'ua.first_name',
            'last_name'         => 'ua.last_name',
            'mobile'            => 'ua.username',
            'email'             => 'ua.email',
            // 'created_dt'        => 'ua.created_dt',
            'age'               => 'e.age',
            'gender'            => 'e.gender',
            'experience'        => 'e.experience',
            'approx_salary'     => 'e.approx_salary',
            'profile_pic'       => 'ua.profile_pic',
        ];
        $cols = [];

        foreach ($column AS $k => $v) {
            $cols[] = $v . ' AS ' . $k;
        }

        $param  = [
            'db'      => 'read',
            'ptable'  => 'employee_occupation as eo JOIN user_accounts as ua on eo.user_id = ua.id JOIN employees as e on ua.id = e.user_id',
            'column'  => $cols,
            'where'   => " where ua.status = 1 AND eo.occupation LIKE '%".$occupation."'",
        ];
        
        $all_emp = $this->common->getTableData($param);

        
        $db = $this->load->database('default',true);
        foreach ($all_emp as $key => $value) {
            
            $emplocqry = $db->select('occupation')->from('employee_occupation')->where('user_id',$value->id);
            $emplocqry = $db->get();
            if ($emplocqry->num_rows() > 0) {
                $value->occupations = implode(', ',array_column($emplocqry->result(), 'occupation'));
            } else {
                $value->occupations = '';
            }
        }

        $data['all_emp'] = $all_emp;

		$this->load->view('front/list',$data);
	}
}
