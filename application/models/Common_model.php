<?php 

class Common_model extends CI_Model 
{
    public function countTableData($param = false)
    {
        $db = $this->load->database('default',true);
        // Validate Requiere param
        if( ! $param || ! isset($param['ptable']) || $param['ptable'] == '' || ! isset($param['pcolumn']) || $param['pcolumn'] == ''){
            return false;
        }

        // Optional Params
        $where = "";
        if(isset($param['where']))
        {
            $where  = $param['where'];
        }

        // Create a Query
        if(isset($param['stable']) && $param['stable'] != '')
        {
            $in = explode(".",$param['pcolumn']);
            if(isset($in[1]))
            $in = $in[1];
            else
            $in = $param['pcolumn'];
            $sql = "SELECT COUNT(DISTINCT(cc." . $in . ")) AS foundRows FROM (SELECT " . $param["pcolumn"] . " FROM " . $param["ptable"] . " " . $where . " UNION ALL SELECT " . $param["pcolumn"] ." FROM " . $param["stable"] . " " . $where . ") AS cc";
        }
        else
        {
            $sql = "SELECT COUNT(DISTINCT(" . $param["pcolumn"] . ")) as foundRows FROM ". $param["ptable"] . " " . $where;
        }
        
        if(isset($param['group']) && $param['group'] != '')
        {
            $sql.= $param['group'];
        }
        $result= $db->query($sql)->result();

        if($result[0]->foundRows > 0 && isset($result[0]->foundRows)){
            return $result[0]->foundRows;
        }
        else{
            return 0;
        }
    }

    public function getTableData($param)
    {
        $db = $this->load->database('default',true);

        if( ! $param || ! isset($param['ptable']) || $param['ptable'] == '') {
            return false;
        }

        // Optional Params
        $column = "*";
        $where  = "";
        $order  = "";
        $limit  = "";
        $group  = "";

        if(isset($param['column']) && is_array($param['column']) && !empty($param['column'])){
            $column = str_replace(" , ", " ", implode(", ", $param['column']));
        }

        if(isset($param['where']) && $param['where'] != ''){
            $where  = $param['where'];
        }

        if(isset($param['order']) && $param['order'] != '')
        {
            $order  = $param['order'];
        }

        if(isset($param['limit']) && $param['limit'] != '')
        {
            $limit  = $param['limit'];
        }

        if(isset($param['group']) && $param['group'] != '')
        {
            $group  = $param['group'];
        }

        // Create a Query
        $sql    = "SELECT " . $column;
        $sql    .= " FROM " . $param['ptable'] . " " . $where;

        if(isset($param['stable']) && $param['stable'] != '')
        {
            $sql .= " UNION ALL SELECT " . $column . " FROM ". $param['stable'] . " " . $where;
        }

        $sql .= " " . $group . " " . $order . " " . $limit;
    
        return $db->query($sql)->result();
    }

    public function insert($p)
    {
        $db = $this->load->database('default',true);
        $qry = $db->insert($p['table'],$p['data']);
        
        if($db->affected_rows() > 0) {
            return $db->insert_id();
        } else {
            return false;
        }
    }

    public function update($p)
    {
        $db = $this->load->database('default',true);
        $qry = $db->update($p['table'], $p['data'], $p['where']);
        
        if($db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUsername($uname)
    {
        $db = $this->load->database('default',true);
        $db->select('id')->from('user_accounts')->where('username', $uname);

        $qry = $db->get();
        if ($qry->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkMobile($table, $mob)
    {
        $db = $this->load->database('default',true);
        $db->select('id')->from($table)->where('mobile', $mob);

        $qry = $db->get();
        if ($qry->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getMenu()
    {
        $db = $this->load->database('default',true);
        $db->select('*')->from('menu')->where('is_active', '1');

        $qry = $db->get();
        if ($qry->num_rows() > 0) {
            return $qry->result();
        } else {
            return false;
        }
    }
}