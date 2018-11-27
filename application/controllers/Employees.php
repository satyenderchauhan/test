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

	public function employeeList()
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
            'id'               => 'ua.id',
            'username'         => 'ua.username',
            'first_name'       => 'e.first_name',
            'last_name'        => 'e.last_name',
            'address'          => 'e.address',
            'mobile'           => 'e.mobile',
            'email'            => 'e.email',
            'created_dt'       => 'DATE_FORMAT(ua.created_dt, "%h %d %M %Y")',
            'status'       	   => 'ua.status',
        ];
        $cols = [];

        foreach ($column AS $k => $v) {
            $cols[] = $v . ' AS ' . $k;
        }
        $ptable = 'employees as e JOIN user_accounts as ua on ua.id = e.user_account_id ';

        $where = ' where ua.user_type = 2 ';
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

    public function addOwner()
    {
        $data = $this->input->post();
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
        if(!$data['email']){
            $error = true;
            $error_msg[] = 'email';
        }
        if(!$data['address']){
            $error = true;
            $error_msg[] = 'address';
        }
        if(!$data['username']){
            $error = true;
            $error_msg[] = 'username';
        }
        if(!$data['password']){
            $error = true;
            $error_msg[] = 'password';
        }

        if($error){
            die(json_encode(['status'=>'201','msg'=>$error_msg]));
        }

        if($this->common->checkMobile('owners', $data['mobile'])){
            die(json_encode(['status'=>'202','msg'=>'Mobile is already existed']));
        }

        if($this->common->checkUsername($data['username'])){
            die(json_encode(['status'=>'202','msg'=>'Username is already existed. Please try another Username']));
        }

        $newdata = [
            'username' => $data['username'],
            'user_type' => 1,
            'table_reference' => 'owners',
            'password' => md5($data['password']),
        ];

        $userId = $this->common->insert(['table' => 'user_accounts', 'data' => $newdata]);
        
        if(!$userId){
            die(json_encode(['status'=>'202','msg'=>'Something Went Wrong']));
        }

        unset($data['username']);
        unset($data['password']);

        $data['user_account_id'] = $userId;
        $resp = $this->common->insert(['table' => 'owners', 'data' => $data]);

        if(!$resp){
            die(json_encode(['status'=>'202','msg'=>'Something Went Wrong #1']));
        }
        die(json_encode(['status'=>'200','msg'=>'Owner Created Successfuly']));
    }

    public function changeStatus()
    {
        $data = $this->input->post();
        $error = false;

        if(!$data['employee_id']){
            die(json_encode(['status'=>'201','msg'=>'Something Went Wrong #1']));
        }
        if($data['status'] == 'Deactivate'){
            $status = 0;
        }
        elseif($data['status'] == 'Activate'){
            $status = 1;
        }else{
            die(json_encode(['status'=>'201','msg'=>'Something Went Wrong #2']));
        }

        $this->common->update([
            'table'=>'user_accounts',
            'data'=>['status'=>$status ],
            'where'=>['id'=>$data['employee_id'] ]
        ]);

        die(json_encode(['status'=>'200','msg'=> 'Employee '.$data['status'].'d Successfuly']));
    }
}
