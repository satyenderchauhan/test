<?php 
       
 	if(! function_exists('getMenu'))
 	{
		function getMenu(){
			
			$ci =& get_instance();
			$ci->load->model('Common_model','common');
        	return $ci->common->getMenu();
		}
 	}
?>