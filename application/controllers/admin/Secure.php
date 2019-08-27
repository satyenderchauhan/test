<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Secure extends CI_Controller {

	public function signin()
	{
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $form_ret['error'] = 'Y' ; 
            $form_ret['message'] = validation_errors() ; 
            die(json_encode($form_ret));
        }
        else
        {
            $data = $this->input->post();
        	$this->load->model('Secure_model','secure');
			$resp = $this->secure->signin($data);

			if($resp){

                if(!$resp->status){
                    $response = [
                        'redirect_url'  => '',
                        'message'       => 'Your account is currently disable, please contact admin.',
                        'status'        => 202,
                    ];

                    die(json_encode($response));    
                }

            	$sessionData = [
            		'is_admin_login' 	  => true,
            		'user_id'	  => $resp->id,
            		'name'		  => $resp->first_name.' '.$resp->last_name,
            		'mobile'	  => $this->input->post('username'),
                    'email'       => $resp->email,
                    'role'        => $resp->role,
            		'profile_pic' => $resp->profile_pic,
            	];

            	$this->session->set_userdata($sessionData);

            	$response = [
					'redirect_url'	=> 'admin/dashboard',
					'message'		=> 'Login Successful',
					'status'		=> 200,
				];

				die(json_encode($response));
            }else{

            	$response = [
					'redirect_url'	=> '',
					'message'		=> 'Invalid login credential',
					'status'		=> 202,
				];

				die(json_encode($response));	
            }
        }
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url().'admin');
	}
}
