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

    public function get_user_data_html()
    {
        $emp_id = $_POST['u_id'];

        if(!$emp_id){
            die('ere');
        }
        
        $db = $this->load->database('default',true);
        
        $db->select('*')->from('employees')->where('user_id',$emp_id);
        $empqry = $db->get();
        if ($empqry->num_rows() > 0) {
            $data['emp_data'] = $empqry->row();
        } else {
            die('aaggaga');
        }

        $db->select('profile_pic')->from('user_accounts')->where('id',$emp_id);
        $empqryImg = $db->get();
        if ($empqryImg->num_rows() > 0) {
            $data['profile_pic'] = $empqryImg->row();
        } else {
            $data['profile_pic'] = '';
        }

        $db->select('*')->from('employee_occupation')->where('user_id',$emp_id);
        $empqry1 = $db->get();
        if ($empqry1->num_rows() > 0) {
            $data['selected_occupation'] = implode(', ',array_column($empqry1->result(), 'occupation'));
        } else {
            $data['selected_occupation'] = [];
        }

        $db->select('*')->from('employee_locations')->where('user_id',$emp_id);
        $emplocqry = $db->get();
        if ($emplocqry->num_rows() > 0) {
            $data['selected_location'] = array_column($emplocqry->result(), 'location');
        } else {
            $data['selected_location'] = [];
        }

        $data['emp_id'] = $emp_id;

        /*echo '<pre>';
        print_r($data);
        die;*/

        $this->load->view('front/popup_forms/emp_data', $data);
    }
}
