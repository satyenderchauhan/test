<?php 

class Secure_model extends CI_Model {

    public function signin($p)
    {
       	$db = $this->load->database('default',true);
		return $db->select('user_accounts.id, owners.first_name, owners.last_name, owners.mobile, owners.email, owners.profile_pic, user_accounts.user_type')

		->join('owners', 'owners.user_account_id=user_accounts.id')
		// ->join('document_types', 'doc_types.type_id=document_types.id')
		// ->join('loan_documents', 'loan_documents.id=doc_types.document_id')
		// ->where('loan_documents.is_active',1)
		->where('user_accounts.username', $p['username'])
		->where('user_accounts.password', md5($p['password']))
		->where('user_accounts.user_type', 10)
		->get('user_accounts')->row();
    }
}
