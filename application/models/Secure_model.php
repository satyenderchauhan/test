<?php 

class Secure_model extends CI_Model {

    public function signin($p)
    {
       	$db = $this->load->database('default',true);
		return $db->select('user_accounts.id, user_accounts.first_name, user_accounts.last_name, user_accounts.email, user_accounts.profile_pic, user_accounts.role, user_accounts.status')

		// ->join('document_types', 'doc_types.type_id=document_types.id')
		// ->join('loan_documents', 'loan_documents.id=doc_types.document_id')
		->where('user_accounts.username', $p['username'])
		->where('user_accounts.password', md5($p['password']))
		->where_in('user_accounts.role', ['1','3'])
		// ->where('user_accounts.status',1)
		->get('user_accounts')->row();
    }
}
