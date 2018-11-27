<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Api extends REST_Controller
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('v1/Api_model','api');
    }    
    
    public function login_get()
    {
        $data = $this->get();
        
        if (!isset($data['username']) || $data['username'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        if (!isset($data['password']) || $data['password'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        $resp = $this->api->login($data);

        if(!$resp){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid username password'], 200);
        }

        if($resp->status != 1){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Your account has been deactivated, Please contact admin.'], 200);
        }

        if(!$resp->table_reference){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        $this->checkActivation($resp->id);

        $newData=[
            'table' => $resp->table_reference,
            'where' => [
                'user_account_id' => $resp->id
            ],
            'select'  => ['first_name','last_name','mobile','email','address','profile_pic','updated_dt']
        ];

        $userDetails = $this->api->select($newData);
        
        if(!$userDetails){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'User details not found'], 200);
        }

        $userDetails[0]->user_id    = $resp->id;
        $userDetails[0]->user_type  = $resp->user_type;
        $this->response(['status' => 200, 'message' => 'Success', 'response' => $userDetails], 200);
    }
    
    public function getGymsList_post()
    {
        $data = $this->post();
        
        if (!isset($data['owner_id']) || $data['owner_id'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        $is_active = $this->api->isUserActive($data['owner_id']);

        if(!$is_active){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'User in not Active'], 200);
        }

        $gyms = $this->api->getGyms($data['owner_id']);

        $this->response(['status' => 200, 'message' => 'Gym list', 'response' => $gyms], 200);
    }

    public function addNewGym_post()
    {
        $data = $this->post();
        
        if (!isset($data['owner_id']) || $data['owner_id'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        if (!isset($data['gym_name']) || $data['gym_name'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid gym name'], 200);
        }

        if (!isset($data['gym_address']) || $data['gym_address'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid gym address'], 200);
        }

        if (!isset($data['gym_number']) || $data['gym_number'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid gym number'], 200);
        }

        $is_active = $this->api->isUserActive($data['owner_id']);

        if(!$is_active){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'User in not Active'], 200);
        }

        $newData = [
            'name' => $data['gym_name'],
            'address' => $data['gym_address'],
            'number' => $data['gym_number'],
            'morning_from' => $data['morning_from'],
            'morning_to' => $data['morning_to'],
            'evening_from' => $data['evening_from'],
            'evening_to' => $data['evening_to']
        ];

        $gym_id = $this->api->insert(['table'=>'gyms','data'=>$newData]);

        if(!$gym_id){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Unable to add gym'], 200);
        }

        $newData2 = [
            'gym_id' => $gym_id,
            'owner_id' => $data['owner_id']
        ];

        $res = $this->api->insert(['table'=>'gym_owners','data'=>$newData2]);

        if(!$res){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Unable to add gym #2'], 200);
        }

        $this->response(['status' => 200, 'message' => 'Gym added Successfuly', 'response' => ''], 200);
    }

    public function editGym_post()
    {
        $data = $this->post();
        
        if (!isset($data['owner_id']) || $data['owner_id'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        if (!isset($data['gym_id']) || $data['gym_id'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid gym'], 200);
        }

        if (!isset($data['gym_name']) || $data['gym_name'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid gym name'], 200);
        }

        if (!isset($data['gym_address']) || $data['gym_address'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid gym address'], 200);
        }

        if (!isset($data['gym_number']) || $data['gym_number'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid gym number'], 200);
        }

        $is_active = $this->api->isUserActive($data['owner_id']);

        if(!$is_active){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'User in not Active'], 200);
        }

        $newData=[
            'table' => 'gym_owners',
            'where' => [
                'owner_id' => $data['owner_id'],
                'gym_id' => $data['gym_id']
            ],
            'select'  => ['id']
        ];

        $details = $this->api->select($newData);

        if(!$details){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        $newData2 = [
            'name' => $data['gym_name'],
            'address' => $data['gym_address'],
            'number' => $data['gym_number'],
            'morning_from' => $data['morning_from'],
            'morning_to' => $data['morning_to'],
            'evening_from' => $data['evening_from'],
            'evening_to' => $data['evening_to']
        ];

        $gym_upd = $this->api->update(['table'=>'gyms','data'=>$newData2, 'where'=>['id'=>$data['gym_id']]]);

        if(!$gym_upd){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Unable to update gym'], 200);
        }

        $this->response(['status' => 200, 'message' => 'Gym updated Successfuly', 'response' => ''], 200);
    }

    public function changeGymStatus_post()
    {
        $data = $this->post();
        
        if (!isset($data['owner_id']) || $data['owner_id'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        if (!isset($data['gym_id']) || $data['gym_id'] == NULL) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid gym'], 200);
        }

        if (!isset($data['status']) || $data['status'] == NULL || !in_array($data['status'], ['0', '1'])) {
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Invalid status'], 200);
        }

        $is_active = $this->api->isUserActive($data['owner_id']);

        if(!$is_active){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'User in not Active'], 200);
        }

        $newData=[
            'table' => 'gym_owners',
            'where' => [
                'owner_id' => $data['owner_id'],
                'gym_id' => $data['gym_id']
            ],
            'select'  => ['id']
        ];

        $details = $this->api->select($newData);

        if(!$details){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'You are not authorizied user'], 200);
        }

        $gym_upd = $this->api->update(['table'=>'gyms','data'=>['status' => $data['status']], 'where'=>['id'=>$data['gym_id']]]);

        if(!$gym_upd){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Unable to update gym'], 200);
        }

        $this->response(['status' => 200, 'message' => 'Status changed Successfuly', 'response' => ''], 200);
    }

    public function checkActivation($userId)
    {
        $db = $this->load->database('default',true);
        $user = $db->select('user_accounts.id, user_accounts.user_type, user_accounts.status')
        ->where('id', $userId)
        ->get('user_accounts')->result()[0];
        
        if($user->status != 1){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'User in not Active'], 200);
        }

        if($user->user_type == 2){
            //EMPLOYEE 
            $isGymActivated = $this->checkIsGymActivated($userId);
            $isOwnerActivated = $this->checkIsOwnerActivated($isGymActivated);
        }

        if($user->user_type == 3){
            //TRAINNERS 
            $isGymActivated = $this->checkIsGymActivatedTrainer($userId);
            $isOwnerActivated = $this->checkIsOwnerActivated($isGymActivated);
        }

        if($user->user_type == 4){
            //USER 
            $isGymActivated = $this->checkIsGymActivatedUser($userId);
            $isOwnerActivated = $this->checkIsOwnerActivated($isGymActivated);
        }
    }

    public function checkIsGymActivated($userId)
    {
        $db = $this->load->database('default',true);
        $gym = $db->select('gyms.id, gyms.status')
        ->join('gym_employees', 'gyms.id = gym_employees.gym_id')
        ->where('gym_employees.employee_id', $userId)
        ->get('gyms')->result();

        if(!$gym || !isset($gym[0]->status) || $gym[0]->status != 1){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Your Gym in not Active'], 200);
        }
        return $gym[0]->id;
    }

    public function checkIsOwnerActivated($gymId)
    {
        $db = $this->load->database('default',true);
        $owner = $db->select('user_accounts.id, user_accounts.status')
        ->join('gym_owners', 'gym_owners.owner_id = user_accounts.id')
        ->where('gym_owners.gym_id', $gymId)
        ->get('user_accounts')->result();

        if(!$owner || !isset($owner[0]->status) || $owner[0]->status != 1){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Your Gym Owner in not Active'], 200);
        }
    }

    public function checkIsGymActivatedTrainer($userId)
    {
        $db = $this->load->database('default',true);
        $gym = $db->select('gyms.id, gyms.status')
        ->join('gym_trainers', 'gyms.id = gym_trainers.gym_id')
        ->where('gym_trainers.trainer_id', $userId)
        ->get('gyms')->result();

        if(!$gym || !isset($gym[0]->status) || $gym[0]->status != 1){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Your Gym in not Active'], 200);
        }
        return $gym[0]->id;
    }

    public function checkIsGymActivatedUser($userId)
    {
        $db = $this->load->database('default',true);
        $gym = $db->select('gyms.id, gyms.status')
        ->join('gym_users', 'gyms.id = gym_users.gym_id')
        ->where('gym_users.user_id', $userId)
        ->get('gyms')->result();

        if(!$gym || !isset($gym[0]->status) || $gym[0]->status != 1){
            $this->response(['status' => 201, 'message' => 'Validation error', 'response' => 'Your Gym in not Active'], 200);
        }
        return $gym[0]->id;
    }
}