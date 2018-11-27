<?php 

class Api_model extends CI_Model 
{
    public function login($p)
    {
        $db = $this->load->database('default',true);
        return $db->select('user_accounts.id, user_accounts.user_type, user_accounts.table_reference')
        ->where('user_accounts.username', $p['username'])
        ->where('user_accounts.password', md5($p['password']))
        // ->where('user_accounts.status', 1)
        ->get('user_accounts')->row();
    }

    public function select($p)
    {
        $db = $this->load->database('default',true);
        $db->select($p['select']);
        $qry = $db->get_where($p['table'],$p['where']);
        
        if($qry->num_rows() > 0) {
            return $qry->result();
        } else {
            return false;
        }
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

    public function isUserActive($userId)
    {
        $db = $this->load->database('default',true);
        $db->select('id')->from('user_accounts')->where('id', $userId)->where('status', 1);

        $qry = $db->get();
        if ($qry->num_rows() > 0) {
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

    public function getGyms($ownerId)
    {
        $db = $this->load->database('default',true);
        return $db->select('gyms.id as gym_id, gyms.name, gyms.address, gyms.mobile, gyms.morning_from, gyms.morning_to, gyms.evening_from, gyms.evening_to, gyms.registered_dt, gyms.status')
        ->join('gym_owners', 'gyms.id=gym_owners.gym_id')
        ->where('gym_owners.owner_id', $ownerId)
        ->get('gyms')->result();
    }
}