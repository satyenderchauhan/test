<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gyms extends CI_Controller {

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
		$this->active = 'gyms';
		$this->load->view('gyms');
	}

	public function gymList()
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
            'id'               => 'g.id',
            // 'unique_id'        => 'g.unique_id',
            'name'             => 'g.name',
            'address'          => 'g.address',
            'number'           => 'g.number',
            'morning_from'     => 'g.morning_from',
            'morning_to'       => 'g.morning_to',
            'evening_from'     => 'g.evening_from',
            'evening_to'       => 'g.evening_to',
            'registered_dt'    => 'DATE_FORMAT(g.registered_dt, "%d %M %Y")',
            'status'       	   => 'g.status',
        ];
        $cols = [];

        foreach ($column AS $k => $v) {
            $cols[] = $v . ' AS ' . $k;
        }
        $ptable = 'gyms as g ';

        $where = '';
        $order = '';
        if (isset($req['search'])) {
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

}
