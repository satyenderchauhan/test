<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->content_data['userData']=$this;
        
        if(!$this->session->userdata('is_admin_login')){
            redirect(base_url());
        }
        $this->active ='';
        $this->load->model('Common_model','common');
    }

	public function index()
	{
		$this->active = 'admin/users';
		$this->load->view('admin/users');
	}

	public function usersList()
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
            'id'                    => 'ua.id',
            'first_name'            => 'ua.first_name',
            'last_name'             => 'ua.last_name',
            'mobile'                => 'ua.username',
            'email'                 => 'ua.email',
            'created_dt'            => 'ua.created_dt',
            'status'                => 'ua.status'
        ];
        $cols = [];

        foreach ($column AS $k => $v) {
            $cols[] = $v . ' AS ' . $k;
        }
        $ptable = 'user_accounts ua ';

        $where = ' where ua.role = 3';
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

    public function get_new_user_html()
    {
        $data = [];
        $this->load->view('admin/popup_forms/new_user', $data);
    }

    public function get_profile_html()
    {
        $inputData = $this->input->post();
        $admin_id = $this->session->userdata('user_id');

        $db = $this->load->database('default',true);
        $db->select('*')->from('user_accounts')->where('id',$inputData['u_id']);
        $qry = $db->get();
        
        if ($qry->num_rows() > 0) {
            $data['profile'] = $qry->row();
        } else {
            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        $data['user_id'] = $inputData['u_id'];

        $this->load->view('admin/popup_forms/profile', $data);
    }

    public function updateProfile()
    {
        $data = $formData = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$formData['first_name']){
            $error = true;
            $error_msg[] = 'first_name';
        }

        if(!$formData['last_name']){
            $error = true;
            $error_msg[] = 'last_name';
        }

        if(!$formData['email']){
            $error = true;
            $error_msg[] = 'email';
        }

        if(@$formData['password']){
            if(strlen($formData['password']) < 6){
                $error = true;
                $error_msg[] = 'password';
            }
            $formData['password'] = md5($formData['password']);
            $data['password'] = md5($data['password']);
        }

        unset($data['user_id']);

        if($error){
            die(json_encode(['status'=>'201','msg'=>$error_msg]));
        }

        $this->common->update(['table' => 'user_accounts', 'data' => $data, 'where'=>['id'=>$formData['user_id']]]);

         /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Profile updated',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($formData),
            'res_data'  => json_encode($data),
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        if($formData['user_id'] == $admin_id){

            $sessionData = [
                'name'        => $formData['first_name'].' '.$formData['last_name'],
                'email'       => $formData['email'],
            ];
            $this->session->set_userdata($sessionData);
        }

        die(json_encode(['status'=>'200','msg'=>'Profile updated successfuly']));
    }

    public function createNewUser()
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

        if(!$data['email']){
            $error = true;
            $error_msg[] = 'email';
        }

        if(!$data['password']){
            $error = true;
            $error_msg[] = 'password';
        }

        if($error){
            die(json_encode(['status'=>'201','msg'=>$error_msg]));
        }

        $data['username'] = $data['mobile'];
        $data['password'] = md5($data['password']);
        unset($data['confirm_password']);
        unset($data['mobile']);

        if($this->common->checkUsername($data['username'])){
            die(json_encode(['status'=>'202','msg'=>'Mobile is already existed']));
        }

        $data['role'] = '3';
        $data['created_by'] = $admin_id;

        $id = $this->common->insert(['table' => 'user_accounts', 'data' => $data]);

        if(!$id){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        unset($data['password']);

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Add new Employee',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($data),
            'res_data'  => json_encode($id),
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'User created successfuly']));
    }

    public function changeUserStatus()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['usr_id']){
            die(json_encode(['status'=>'201','msg'=>'Something went wrong!']));
        }

        if(!in_array($data['status'], ['Enable','Disable'])){
            die(json_encode(['status'=>'201','msg'=>'Something went wrong!']));
        }

        $status = '0';
        if($data['status'] == 'Enable'){
            $status = 1;
        }

        $this->common->update(['table' => 'user_accounts', 'data' => ['status'=>$status], 'where'=>['id'=>$data['usr_id']]]);

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Change User status',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($data),
            'res_data'  => 'status changed',
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'User '.$data['status'].'d successfuly']));
    }

    public function getUserPerms()
    {
        $input = $this->input->post();

        if(!$input['usr_id']){
            die('Something went wrong, Please try again later.');
        }

        $data = [];
        $db = $this->load->database('default',true);
        $db->select('id,name')->from('menu')->where('is_active', '1');
        $db->order_by('position', 'ASC');
        $menuQry = $db->get();

        if ($menuQry->num_rows() <= 0) {
            die('Something went wrong, Please try again later.');
        }

        $data['all_perms'] = $menuQry->result();

        $db->select('*')->from('user_has_perms')->where('user_id', $input['usr_id']);
        $usrQry = $db->get();

        if ($usrQry->num_rows() > 0) {
            $data['selected_perms'] = array_column($usrQry->result(), 'perm_id');
        } else {
            $data['selected_perms'] = [];
        }

        $data['user_id'] = $input['usr_id'];

        $this->load->view('admin/popup_forms/user_perms', $data);
    }

    public function updateUserPerms()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['user_id']){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        if(!isset($data['perm'])){

            die(json_encode(['status'=>'202','msg'=>'Please select any permission!']));
        }

        $db     = $this->load->database('default',true);
        $sql    = "DELETE FROM user_has_perms WHERE user_id = ".$data['user_id'];
        $db->query($sql);

        foreach ($data['perm'] as $key => $value) {
            
            $this->common->insert(['table' => 'user_has_perms', 'data' => ['user_id'=>$data['user_id'],'perm_id'=>$value]]);
        }

        die(json_encode(['status'=>'200','msg'=>'Permissions updated successfuly']));
    }
}