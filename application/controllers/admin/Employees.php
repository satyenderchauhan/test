<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller {

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
		$this->active = 'admin/employees';
		$this->load->view('admin/employees');
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
            'id'                    => 'ua.id',
            'first_name'            => 'ua.first_name',
            'last_name'             => 'ua.last_name',
            'mobile'                => 'ua.username',
            'email'                 => 'ua.email',
            'created_dt'            => 'ua.created_dt',
            'status'                => 'ua.status',
            'age'                   => 'e.age',
            'gender'                => 'e.gender',
            'address'               => 'e.address',
            'city'                  => 'e.city',
            'state'                 => 'e.state',
            'pin_code'              => 'e.pin_code',
            'education'             => 'e.education',
            'experience'            => 'e.experience',
            'approx_salary'         => 'e.approx_salary',
            'salary'                => 'e.salary',
            'language_know'         => 'e.language_know',
            'can_drive'             => 'e.can_drive',
            'driving_licence_no'    => 'e.driving_licence_no',
            'have_vehicle'          => 'e.have_vehicle',
            'work_timing'           => 'e.work_timing',
            'bio'                   => 'e.bio',
        ];
        $cols = [];

        foreach ($column AS $k => $v) {
            $cols[] = $v . ' AS ' . $k;
        }
        $ptable = 'employees as e JOIN user_accounts ua on e.user_id = ua.id';

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

    public function get_new_employee_html($uId = false)
    {

        $db = $this->load->database('default',true);
        $db->select('*')->from('occupation');
        $qry = $db->get();

        $data = [];
        if ($qry->num_rows() > 0) {
            $data['all_occupation'] = $qry->result();
        } else {
            $data['all_occupation'] = [];
        }

        $db->select('*')->from('employee_occupation')->where('user_id',$uId);
        $empqry = $db->get();
        if ($empqry->num_rows() > 0) {
            $data['selected_occupation'] = array_column($empqry->result(), 'occupation');
        } else {
            $data['selected_occupation'] = [];
        }

        $db->select('*')->from('employee_locations')->where('user_id',$uId);
        $emplocqry = $db->get();
        if ($emplocqry->num_rows() > 0) {
            $data['selected_location'] = array_column($emplocqry->result(), 'location');
        } else {
            $data['selected_location'] = [];
        }

        $data['emp_id'] = $uId;

        $this->load->view('admin/popup_forms/new_employee', $data);
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

        $userAccountData = [];

        $data['username'] = $data['mobile'];
        unset($data['mobile']);

        foreach ($data as $key => $value) {
            if(in_array($key,['username','first_name','last_name','email'])){
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

        foreach ($data['work_can_do'] as $value) {
            
            if($value){
                $this->common->insert(['table' => 'employee_occupation', 'data' => ['user_id'=>$id,'occupation'=>$value]]);
            }
        }

        unset($data['username']);
        unset($data['first_name']);
        unset($data['last_name']);
        unset($data['email']);
        unset($data['location']);
        unset($data['work_can_do']);
        unset($data['emp_id']);

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

    public function updateEmployee()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['emp_id']){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        if(!$data['first_name']){
            $error = true;
            $error_msg[] = 'first_name';
        }

        if(!$data['last_name']){
            $error = true;
            $error_msg[] = 'last_name';
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

        $userAccountData = [];

        foreach ($data as $key => $value) {
            if(in_array($key,['first_name','last_name','email'])){
                if($value){
                    $userAccountData[$key] = $value;
                }
            }
        }

        $this->common->update(['table' => 'user_accounts', 'data' => $userAccountData, 'where'=>['id'=>$data['emp_id']]]);

        $where = [
            'user_id'   => $data['emp_id']
        ];

        $this->common->delete(['table' => 'employee_locations', 'where' => $where]);

        foreach ($data['location'] as $value) {
            
            if($value){
                $this->common->insert(['table' => 'employee_locations', 'data' => ['user_id'=>$data['emp_id'],'location'=>$value]]);
            }
        }

        $this->common->delete(['table' => 'employee_occupation', 'where' => $where]);
        foreach ($data['work_can_do'] as $value) {
            
            if($value){
                $this->common->insert(['table' => 'employee_occupation', 'data' => ['user_id'=>$data['emp_id'],'occupation'=>$value]]);
            }
        }

        unset($data['first_name']);
        unset($data['last_name']);
        unset($data['email']);
        unset($data['location']);
        unset($data['work_can_do']);
        unset($data['emp_id']);

        $this->common->update(['table' => 'employees', 'data' => $data, 'where'=>$where]);

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Update Employee',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($data),
            'res_data'  => json_encode($data),
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'Employee updateed successfuly']));
    }

    public function changeEmployeeStatus()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['emp_id']){
            die(json_encode(['status'=>'201','msg'=>'Something went wrong!']));
        }

        if(!in_array($data['status'], ['Enable','Disable'])){
            die(json_encode(['status'=>'201','msg'=>'Something went wrong!']));
        }

        $status = '0';
        if($data['status'] == 'Enable'){
            $status = 1;
        }

        $this->common->update(['table' => 'user_accounts', 'data' => ['status'=>$status], 'where'=>['id'=>$data['emp_id']]]);

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Change Employee status',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($data),
            'res_data'  => 'status changed',
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'Employee '.$data['status'].'d successfuly']));
    }

    public function getEmployeeDocs()
    {
        $data = $this->input->post();
        $db = $this->load->database('default',true);
        $db->select('*')->from('employee_documents')->where('user_id',$data['emp_id']);
        $qry = $db->get();

        $emp_id = $data['emp_id'];
        $data = [];
        if ($qry->num_rows() > 0) {
            $data['selected_docs'] = $qry->result();
        } else {
            $data['selected_docs'] = [];
        }

        $data['emp_id'] = $emp_id;
        $this->load->view('admin/popup_forms/employee_docs', $data);
    }

    public function uploadNewDocument()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['emp_id']){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        if(!$data['document_name']){
            $error = true;
            $error_msg[] = 'document_name';
        }

        if($error){
            die(json_encode(['status'=>'201','msg'=>$error_msg]));
        }

        $status = "";
        $msg = "";
        $file_element_name = 'userfile';
         
        if(isset($_FILES["document"]["name"]))  
        {
            if (!is_dir('./assets/employee_docs/'.$data['emp_id'])) {
                mkdir('./assets/employee_docs/' . $data['emp_id'], 0777, TRUE);
            }

            $config['upload_path'] = './assets/employee_docs/'.$data['emp_id'];  
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';  
            $this->load->library('upload', $config);  
            if(!$this->upload->do_upload('document'))  
            {  
                die(json_encode(['status'=>'202','msg'=>$this->upload->display_errors()])); 
            }  
            else  
            {  
                $updData = $this->upload->data();  

                $docData = [
                    'document_name' => $data['document_name'],
                    'user_id'       => $data['emp_id'],
                    'url'           => 'assets/employee_docs/'.$data["emp_id"].'/'.$updData["file_name"],
                ];

                $this->common->insert(['table' => 'employee_documents', 'data' => $docData]);
                die(json_encode(['status'=>'200','msg'=>'Document uploaded successfuly']));
            }  
        }else{

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        } 
    }

    public function updateEmployeeDocStatus()
    {
        $data = $this->input->post();
        $admin_id = $this->session->userdata('user_id');
        $error = false;

        if(!$data['emp']){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        if(!$data['doc']){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        $this->common->update(['table' => 'employee_documents', 'data' => ['is_approved'=>'1','approved_by'=>$admin_id], 'where'=>['id'=>$data['doc'],'user_id'=>$data['emp']]]);

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Change Employee document status',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($data),
            'res_data'  => 'status changed',
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'Document status updateed successfuly']));
    }
}