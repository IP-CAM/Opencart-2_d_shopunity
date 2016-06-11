<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityLogin extends Controller {
	private $id = 'd_shopunity';
	private $route = 'd_shopunity/login';
	private $sub_versions = array('lite', 'light', 'free');
	private $mbooth = '';
	private $config_file = '';
	private $prefix = '';
	private $store_id = 0;
	private $error = array(); 
	private $client_id = 'd_shopunity';

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('module/d_shopunity');
		$this->load->model('d_shopunity/account');

		//Mbooth file (example: mbooth_d_shopunity.xml)
		$this->mbooth = $this->model_module_d_shopunity->getMboothFile($this->id, $this->sub_versions);

		//store_id (for multistore)
		if (isset($this->request->get['store_id'])) { 
			$this->store_id = $this->request->get['store_id']; 
		}

		//Config File (example: d_shopunity)
		$this->config_file = $this->model_module_d_shopunity->getConfigFile($this->id, $this->sub_versions);

		//Check if all dependencies are installed
		$this->model_module_d_shopunity->installDependencies($this->mbooth);

	}


	public function index(){

   		if($this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/account', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->document->addStyle('view/stylesheet/d_shopunity/d_shopunity.css');
		
		$this->load->language('module/d_shopunity');
   		$this->load->language('d_shopunity/login');
   		$this->load->model('module/d_shopunity');
   		$this->load->model('user/user');
   		$route = 'd_shopunity/login';

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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link($route, 'token=' . $this->session->data['token'], 'SSL')
			);

		// Notification
		foreach($this->error as $key => $error){
			$data['error'][$key] = $error;
		}

		if(!empty($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
		if(!empty($this->session->data['error'])){
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}
		

   		$this->document->setTitle($this->language->get('heading_title'));
   		$data['heading_title'] = $this->language->get('heading_title');
   		$data['version'] = $this->model_module_d_shopunity->getVersion($this->mbooth);
		$data['text_edit'] = $this->language->get('text_edit');


		$data['action_connect'] = $this->model_d_shopunity_account->getAuthorizeUrl('d_shopunity/login/callback');
		
		$user = $this->model_user_user->getUser($this->user->getId());
		$data['store_info'] = array(
			'name' => $this->config->get('config_name'),
			'description' => $this->config->get('config_meta_description'),
			'version' => VERSION,
			'url' => HTTP_CATALOG,
			'ssl_url' => HTTPS_CATALOG,
			'dir' => DIR_CATALOG,
			'server_ip' => $this->request->server['SERVER_ADDR'],
			'db_driver' => DB_DRIVER,
			'db_host' => DB_HOSTNAME,
			'db_user' => DB_USERNAME,
			'db_password' => DB_USERNAME,
			'db_name' => DB_DATABASE,
			'db_prefix' => DB_PREFIX,
			'connected' => 1,
			'admin_url' => HTTPS_SERVER,
			'admin_user' => $user['username'],
			'admin_email' => $user['email'],
		);
		$data['button_connect'] = $this->language->get('button_connect');

   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view($route.'.tpl', $data));
   	}

   	public function callback(){

   		$this->load->model('d_shopunity/account');
		$json = $this->model_d_shopunity_account->getToken('d_shopunity/login/callback');
	
		if ($json) {
			if(isset($json['access_token'])){
				$this->model_d_shopunity_account->login($json);
				$this->response->redirect($this->url->link('d_shopunity/account', 'token=' . $this->session->data['token'], 'SSL'));
	
			}else{
				$this->session->data['error']   = $this->language->get('error_connection_failed');
			}
			
		}else{
			$this->session->data['error']   = $this->language->get('error_not_json');
		}
			
		$this->response->redirect($this->url->link('d_shopunity/login', 'token=' . $this->session->data['token'], 'SSL'));
	
	}
}