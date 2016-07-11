<?php
/*
 *	location: admin/controller
 */

class ControllerModuleDShopunity extends Controller {
	

	private $codename = 'd_shopunity';
	private $route = 'module/d_shopunity';
	private $extension = array();
	private $store_id = 0;
	//private $config_file = '';

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('d_shopunity/mbooth');
		$this->load->model('d_shopunity/account');
		//$this->load->model('d_shopunity/config');

		//extension.json
		$this->extension = $this->model_d_shopunity_mbooth->getExtension($this->codename);

		//Store_id (for multistore)
		if (isset($this->request->get['store_id'])) { 
			$this->store_id = $this->request->get['store_id']; 
		}

		//Config File (example: d_shopunity)
		//$this->config_file = $this->model_d_shopunity_config->getConfigFile($this->codename);

		//Check if all dependencies are installed
		$this->model_d_shopunity_mbooth->installDependencies($this->codename);

	}

	public function index(){

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL'));
	
	}

	public function content_top(){

		$this->load->language('module/d_shopunity');

		//documentation http://t4t5.github.io/sweetalert/
		$this->document->addStyle('view/javascript/d_shopunity/library/sweetalert/sweetalert.css');
		$this->document->addScript('view/javascript/d_shopunity/library/sweetalert/sweetalert.min.js');

		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addStyle('view/stylesheet/d_shopunity/d_shopunity.css');
		$this->document->addScript('view/javascript/d_shopunity/d_shopunity.js');

		// Breadcrumbs
		$data['breadcrumbs'] = array(); 
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
			);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
			);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL')
			);

		if(!empty($this->session->data['error'])){
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		if(!empty($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

   		$this->document->setTitle($this->language->get('heading_title_main'));
   		$data['heading_title'] = $this->language->get('heading_title_main');
   		$data['version'] = $this->model_d_shopunity_mbooth->getVersion($this->codename);
   		$data['route'] = $this->request->get['route'];
		//language
		$data['tab_extension'] =  $this->language->get('tab_extension');
		$data['tab_market'] =  $this->language->get('tab_market');
		$data['tab_billing'] =  $this->language->get('tab_billing');
		$data['tab_backup'] =  $this->language->get('tab_backup');
		$data['tab_setting'] =  $this->language->get('tab_setting');

		$data['href_extension'] =  $this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_market'] =  $this->url->link('d_shopunity/market', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_billing'] =  $this->url->link('d_shopunity/order', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_backup'] = $this->url->link('d_shopunity/backup', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_setting'] = $this->url->link('d_shopunity/setting', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['button_logout'] =  $this->language->get('button_logout');
		$data['logout'] = $this->url->link('d_shopunity/account/logout', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');

		return $this->load->view('d_shopunity/content_top.tpl', $data);
	}

	public function content_bottom(){

		$data['purchase_url'] = str_replace('&amp;', '&', $this->url->link('d_shopunity/extension/purchase', 'token='.$this->session->data['token'], 'SSL')); 

		$data['footer'] = $this->load->controller('common/footer');
		return $this->load->view('d_shopunity/content_bottom.tpl', $data);
	}



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
		$this->load->model('d_shopunity/vqmod');

		$this->model_d_shopunity_vqmod->setVqmod('a_vqmod_d_shopunity.xml', 1);

		$this->load->model('user/user_group');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/account');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/account');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/extension');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/extension');
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
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'access', $this->id.'/setting');
        $this->model_user_user_group->addPermission($this->model_module_d_shopunity->getGroupId(), 'modify', $this->id.'/setting');

        $this->load->model('d_shopunity/mbooth');
		$this->model_d_shopunity_mbooth->installDependencies($this->codename);

		//$this->getUpdate(1);	  
	}

	public function uninstall() {
		$this->load->model('module/d_shopunity');
		$this->model_module_d_shopunity->setVqmod('a_vqmod_d_shopunity.xml', 0);	
		//$this->getUpdate(0);	  
	}
}
?>