<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->content_data['userData']=$this;
        
        if(!$this->session->userdata('is_login')){
            redirect(base_url());
        }
        $this->active ='';
        $this->load->model('Common_model','common');
    }

	public function index()
	{
		$this->active = 'employees';
		$this->load->view('employees');
	}

	public function occupationList()
	{
		$in     = $this->input->post();
        $output = array(
            "iTotalRecords" => 0, 
            "iTotalDisplayRecords" => 0, 
            "data" => array(), 
            "download_link" => "", 
            "resp" => ""
        );

        $column      = [
            'id'               => 'o.id',
            'occupation'       => 'o.occupation',
            'created_at'       => 'o.created_at',
            'first_name'       => 'ua.first_name',
            'last_name'        => 'ua.last_name',
        ];
        $cols = [];

        foreach ($column AS $k => $v) {
            $cols[] = $v . ' AS ' . $k;
        }
        $ptable = 'occupation as o JOIN user_accounts ua on o.created_by = ua.id';

        $where = '';
        $order = '';
        if (isset($in['search'])) {
            if ($in['search']['value'] != '') {
                $search = '';
                foreach ($in['columns'] as $c) {
                    if (is_array($c['data'])) {
                        $c['data'] = @$c['data']['_'];
                    }
                    $dd        = explode(".", $c['data']);
                    $c['data'] = $dd[0];
                    if (array_key_exists($c['data'], $column)) {
                        $search .= $search == '' ? ' (' : ' OR ';
                        if ($in['search']['regex'] == 'true') {
                            $search .= $column[$c['data']] . " REGEXP '" . $in['search']['value'] . "' ";
                        } else {
                            $search .= $column[$c['data']] . " LIKE '%" . $in['search']['value'] . "%' ";
                        }
                    }
                }

                if ($search != '') {
                    $search .= ')';
                    $where .= ($where == '' ? ' WHERE ' : ' AND ') . $search;
                }
            }
        }

        if (isset($in['order'])) {
            foreach (@$in['order'] as $odr) {
                $_odr = '';
                $i    = $odr['column'];
                if ($in['columns'][$i]['orderable']) {
                    if (is_array($in['columns'][$i]['data'])) {
                        $in['columns'][$i]['data'] = @$in['columns'][$i]['data']['_'];
                    }
                    $_col = $in['columns'][$i]['data'];
                    if (array_key_exists($_col, $column)) {
                        // $_odr .= ($_odr != '' ? ' , ': '') . $column[$_col] .' '. $odr['dir'];
                        $_odr .= ($_odr != '' ? ' , ' : '') . $_col . ' ' . $odr['dir'];
                    }
                }
                if ($_odr != '') {
                    $order = ($order == '' ? ' ORDER BY ' : ' , ') . $_odr;
                }
            }
        }
        $stable = '';
        $param  = [
            'db'      => 'read',
            'pcolumn' => $column['id'],
            'ptable'  => $ptable,
            'stable'  => $stable,
            'column'  => $cols,
            'order'   => $order,
            'where'   => $where,
            'limit'   => '',
        ];

        $output["iTotalRecords"]        = $this->common->countTableData($param);
        $param["where"]                 = $where;
        $param['limit']                 = ' LIMIT ' . $in['start'] . ',' . $in['length'];
        $result                         = $this->common->getTableData($param);
        $output["iTotalDisplayRecords"] = $output["iTotalRecords"];

        !$result && $result = [];
        foreach ($result as $res) {
            // $res->DT_RowClass = 'success';
            $res->DT_RowId    = $res->id;
            $output['data'][] = $res;
        }

        die(json_encode($output));
	}

    public function get_new_employee_html(){

        $db = $this->load->database('default',true);
        $db->select('*')->from('occupation');

        $data = [];

        $qry = $db->get();
        if ($qry->num_rows() > 0) {
            $data['all_occupation'] = $qry->result();
        } else {
            $data['all_occupation'] = [];
        }

        $this->load->view('new_employee', $data);
    }

    public function addEmployee()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['first_name']){
            $error = true;
            $error_msg[] = 'first_name';
        }

        if(!$data['last_name']){
            $error = true;
            $error_msg[] = 'last_name';
        }

        if(!$data['mobile']){
            $error = true;
            $error_msg[] = 'mobile';
        }

        if(!$data['age']){
            $error = true;
            $error_msg[] = 'age';
        }

        if(!$data['gender']){
            $error = true;
            $error_msg[] = 'gender';
        }

        if(!$data['address']){
            $error = true;
            $error_msg[] = 'address';
        }

        if(!$data['city']){
            $error = true;
            $error_msg[] = 'city';
        }

        if(!$data['state']){
            $error = true;
            $error_msg[] = 'state';
        }

        if(!$data['experience']){
            $error = true;
            $error_msg[] = 'experience';
        }

        if(!$data['approx_salary']){
            $error = true;
            $error_msg[] = 'approx_salary';
        }

        if(!$data['bio']){
            $error = true;
            $error_msg[] = 'bio';
        }

        if($error){
            die(json_encode(['status'=>'201','msg'=>$error_msg]));
        }

        $param  = [
            'db'      => 'read',
            'ptable'  => 'user_accounts',
            'column'  => ['id'],
            'where'   => " where username = '".$data['mobile']."'",
        ];
        
        if($this->common->getTableData($param)){
            die(json_encode(['status'=>'202','msg'=>'Mobile number is already existed']));
        }

        $userAccountData = [];

        $data['username'] = $data['mobile'];
        unset($data['mobile']);

        foreach ($data as $key => $value) {
            if(in_array($key,['username','first_name','last_name','email','profile_pic'])){
                if($value){
                    $userAccountData[$key] = $value;
                }
            }
        }

        $userAccountData['role'] = '2';
        $id = $this->common->insert(['table' => 'user_accounts', 'data' => $userAccountData]);

        if(!$id){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        foreach ($data['location'] as $value) {
            
            if($value){
                $this->common->insert(['table' => 'employee_locations', 'data' => ['user_id'=>$id,'location'=>$value]]);
            }
        }

        unset($data['username']);
        unset($data['first_name']);
        unset($data['last_name']);
        unset($data['email']);
        unset($data['profile_pic']);
        unset($data['location']);

        $data['user_id'] = $id;

        $this->common->insert(['table' => 'employees', 'data' => $data]);

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Add new Employee',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($this->input->post()),
            'res_data'  => json_encode($id),
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'Employee created successfuly']));
    }

    public function updateOccupation()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['occupation']){
            $error = true;
            $error_msg[] = 'occupation';
        }

        if($error){
            die(json_encode(['status'=>'201','msg'=>$error_msg]));
        }

        $param  = [
            'db'      => 'read',
            'ptable'  => 'occupation',
            'column'  => ['id'],
            'where'   => " where occupation = '".$data['occupation']."'",
        ];
        
        if($this->common->getTableData($param)){
            die(json_encode(['status'=>'202','msg'=>'Occupation is already existed']));
        }

        $newdata = [
            'occupation' => ucfirst($data['occupation']),
            'created_by' => $admin_id
        ];

        $where = [
            'id' => $data['occupation_id']
        ];

        if(!$this->common->update(['table' => 'occupation', 'data' => $newdata, 'where' => $where])){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Update occupation',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($data),
            'res_data'  => json_encode($newdata),
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'Occupation updateed successfuly']));
    }

    public function removeOccupation()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['occupationId']){
            die(json_encode(['status'=>'201','msg'=>'Something went wrong!']));
        }
        
        $db     = $this->load->database('default',true);
        $sql    = "DELETE FROM occupation WHERE id = ".$data['occupationId'];
        $db->query($sql);

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Remove occupation',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($data),
            'res_data'  => 'Occupation removed',
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'Occupation removed successfuly']));
    }
}
