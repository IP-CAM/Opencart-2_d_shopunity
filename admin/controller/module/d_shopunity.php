<?php
/*
 *	location: admin/controller
 */

class ControllerModuleDShopunity extends Controller {
	private $id = 'd_shopunity';
	private $codename = 'd_shopunity';
	private $route = 'module/d_shopunity';
	private $sub_versions = array('lite', 'light', 'free');
	private $mbooth = '';
	private $config_file = '';
	private $prefix = '';
	private $store_id = 0;
	private $error = array(); 
	private $client_id = 'testclient';

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('module/d_shopunity');
		$this->load->model('module/d_mbooth');
 
		//Mbooth file (example: mbooth_d_shopunity.xml)
		$this->mbooth = $this->model_module_d_shopunity->getMboothFile($this->id, $this->sub_versions);

		//store_id (for multistore)
		if (isset($this->request->get['store_id'])) { 
			$this->store_id = $this->request->get['store_id']; 
		}

		//Config File (example: d_shopunity)
		$this->config_file = $this->model_module_d_shopunity->getConfigFile($this->id, $this->sub_versions);

		//Check if all dependencies are installed
		$this->model_module_d_mbooth->installDependencies($this->codename);

	}

	public function index(){

		$this->load->model('d_shopunity/account');

		if(!$this->model_d_shopunity_account->isLogged())
		{
			$this->response->redirect($this->url->link('d_shopunity/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL'));
	
	}


	/**

	 Ajax requests

	 **/

   		

   		

   	/**

	Add Assisting functions here 

	 **/
	private function validate($permission = 'modify') {

		if (isset($this->request->post['config'])) {
			return false;
		}

		$this->language->load($this->route);
		
		if (!$this->user->hasPermission($permission, $this->route)) {
			$this->error['warning'] = $this->language->get('error_permission');
			return false;
		}


		return true;
	}

	public function install() {
		$this->load->model('module/d_shopunity');
		$this->load->model('module/d_mbooth');
		$this->model_module_d_shopunity->setVqmod('a_vqmod_d_shopunity.xml', 1);

		$this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/account');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/account');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/extension');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/extension');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/login');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/login');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/market');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/market');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/backup');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/backup');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/order');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/order');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/invoice');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/invoice');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/transaction');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/transaction');

		$this->model_module_d_mbooth->installDependencies($this->codename);

		//$this->getUpdate(1);	  
	}

	public function uninstall() {
		$this->load->model('module/d_shopunity');
		$this->model_module_d_shopunity->setVqmod('a_vqmod_d_shopunity.xml', 0);	
		//$this->getUpdate(0);	  
	}


	/*
	*	Ajax: Get the update information on this module. 
	*/
	// public function getUpdate($status = 1){
	// 	if($status !== 0){	$status = 1; }

	// 	$json = array();

	// 	$this->load->language($this->route);
	// 	$this->load->model($this->route);

	// 	$current_version = $this->model_module_d_shopunity->getVersion($this->mbooth);
	// 	$info = $this->model_module_d_shopunity->getUpdateInfo($this->mbooth, $status);

	// 	if ($info['code'] == 200) {
	// 		$data = simplexml_load_string($info['data']);

	// 		if ((string) $data->version == (string) $current_version 
	// 			|| (string) $data->version <= (string) $current_version) 
	// 		{
	// 			$json['success']   = $this->language->get('success_no_update') ;
	// 		} 
	// 		elseif ((string) $data->version > (string) $current_version) 
	// 		{
	// 			$json['warning']   = $this->language->get('warning_new_update');

	// 			foreach($data->updates->update as $update)
	// 			{
	// 				if((string) $update->attributes()->version > (string)$current_version)
	// 				{
	// 					$version = (string)$update->attributes()->version;
	// 					$json['update'][$version] = (string) $update[0];
	// 				}
	// 			}
	// 		} 
	// 		else 
	// 		{
	// 			$json['error']   = $this->language->get('error_update');
	// 		}
	// 	} 
	// 	else 
	// 	{ 
	// 		$json['error']   =  $this->language->get('error_failed');
	// 	}

	// 	$this->response->setOutput(json_encode($json));
	// }
}
?>