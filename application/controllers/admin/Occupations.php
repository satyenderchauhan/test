<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Occupations extends CI_Controller {

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
		$this->active = 'admin/occupations';
		$this->load->view('admin/occupation');
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

    public function addOccupation()
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

        if(!$this->common->insert(['table' => 'occupation', 'data' => $newdata])){

            die(json_encode(['status'=>'202','msg'=>'Something went wrong, Please try again!']));
        }

        /********************************  FOR LOG ************************/
        $logData = [
            'event'     => 'Add new occupation',
            'user_id'   => $admin_id,
            'req_data'  => json_encode($data),
            'res_data'  => json_encode($newdata),
        ];
        $this->common->insert(['table' => 'logs', 'data' => $logData]);
        /********************************  FOR LOG ************************/

        die(json_encode(['status'=>'200','msg'=>'Occupation created successfuly']));
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
