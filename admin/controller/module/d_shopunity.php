<?php
/*
 *	location: admin/controller
 */

class ControllerModuleDShopunity extends Controller {
	private $id = 'd_shopunity';
	private $route = 'module/d_shopunity';
	private $sub_versions = array('lite', 'light', 'free');
	private $mbooth = '';
	private $config_file = '';
	private $prefix = '';
	private $store_id = 0;
	private $error = array(); 

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('module/d_shopunity');

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

		//dependencies
		$this->load->language($this->route);
		$this->load->model('module/d_shopunity');
		$this->load->model('setting/setting');
		$this->load->model('extension/module');
		
		if(!$this->model_module_d_shopunity->isLogged()){

			$this->response->redirect($this->url->link('module/d_shopunity/login', 'token=' . $this->session->data['token'], 'SSL'));
		}
		//save post
		// if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

		// 	$this->model_setting_setting->editSetting($this->id, $this->request->post, $this->store_id);
		// 	$this->session->data['success'] = $this->language->get('text_success');

		// 	$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			
		// }

		// styles and scripts
		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');

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

		// Notification
		foreach($this->error as $key => $error){
			$data['error'][$key] = $error;
		}

		// Heading
		$this->document->setTitle($this->language->get('heading_title_main'));
		$data['heading_title'] = $this->language->get('heading_title_main');
		$data['text_edit'] = $this->language->get('text_edit');
		
		// Variable
		$data['id'] = $this->id;
		$data['route'] = $this->route;
		$data['store_id'] = $this->store_id;
		$data['stores'] = $this->model_module_d_shopunity->getStores();
		$data['mbooth'] = $this->mbooth;
		$data['config'] = $this->config_file;
		$data['support_email'] = $this->support_email;

		$data['version'] = $this->model_module_d_shopunity->getVersion($data['mbooth']);
		$data['token'] =  $this->session->data['token'];

		//language
		$data['tab_extension'] =  $this->language->get('tab_extension');
		$data['tab_market'] =  $this->language->get('tab_market');
		$data['tab_account'] =  $this->language->get('tab_account');
		$data['tab_backup'] =  $this->language->get('tab_backup');

		$data['href_extension'] =  $this->url->link('module/d_shopunity/index', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_market'] =  $this->url->link('module/d_shopunity/market', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_account'] =  $this->url->link('module/d_shopunity/account', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_backup'] = $this->url->link('module/d_shopunity/backup', 'token=' . $this->session->data['token'], 'SSL');

		//get setting
		$data['setting'] = $this->model_module_d_shopunity->getConfigData($this->id, $this->id.'_setting', $this->store_id, $this->config_file);

   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view('d_shopunity/extension.tpl', $data));
   	}

   	public function login($data){

   		if($this->model_module_d_shopunity->isLogged()){

			$this->response->redirect($this->url->link('module/d_shopunity', 'token=' . $this->session->data['token'], 'SSL'));
		}

   		$this->load->language('d_shopunity/login');
   		$this->load->model('module/d_shopunity');
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

   		$this->document->setTitle($this->language->get('heading_title'));
   		$data['heading_title'] = $this->language->get('heading_title');

   		$data['version'] = $this->model_module_d_shopunity->getVersion($this->mbooth);

		$data['text_edit'] = $this->language->get('text_edit');
		$data['button_connect'] = $this->language->get('button_connect');

		$data['href_connect'] = $this->url->link('module/d_shopunity/connect', 'token=' . $this->session->data['token'], 'SSL');
	
   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view($route.'.tpl', $data));
   	}

   	public function connect(){

		$this->response->redirect('https://api.shopunity.net/v1/oauth/authorize?response_type=code&client_id=testclient&state=xyz&redirect_uri='. urlencode($this->url->link('module/d_shopunity/callback', 'token=' . $this->session->data['token'], 'SSL')));

   	}

   	public function callback(){


		$resource = array( 
	    	'grant_type' => 'authorization_code',
	    	'client_id' => 'testclient',
			'code' => $this->request->get['code'],
	        'state' => $this->request->get['state'],
	        'redirect_uri' => urlencode($this->url->link('module/d_shopunity/callback', 'token=' . $this->session->data['token'], 'SSL'))
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL,"https://api.shopunity.net/v1/oauth/token");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($resource));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);

		$json = json_decode($server_output,true);

		if (json_last_error() === JSON_ERROR_NONE) {
			if(isset($json['access_token'])){
				$data = array();
				$data['d_shopunity_oauth'] = $json;
				$this->load->model('setting/setting');
				$this->model_setting_setting->editSetting('d_shopunity', $data);
			}else{
				$this->session->data['error']   = $this->language->get('error_connection_failed');
			}
			
		}else{
			$this->session->data['error']   = $this->language->get('error_not_json');
		}
		$this->response->redirect($this->url->link('module/d_shopunity', 'token=' . $this->session->data['token'], 'SSL'));
		
	}

	public function extension(){
		if($this->model_module_d_shopunity->isLogged()){

			$this->response->redirect($this->url->link('module/d_shopunity', 'token=' . $this->session->data['token'], 'SSL'));
		}

   		$this->load->language('d_shopunity/extension');
   		$this->load->model('module/d_shopunity');
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

   		$this->document->setTitle($this->language->get('heading_title'));
   		$data['heading_title'] = $this->language->get('heading_title');

   		$data['version'] = $this->model_module_d_shopunity->getVersion($this->mbooth);

		$data['text_edit'] = $this->language->get('text_edit');
		$data['button_connect'] = $this->language->get('button_connect');

		$data['href_connect'] = $this->url->link('module/d_shopunity/connect', 'token=' . $this->session->data['token'], 'SSL');
	
   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view($route.'.tpl', $data));
	}

	public function info(){
		$json = array(
			'codename' => '',
			'name' => '',
			'description' => '',
			'demo' => 0,
			'version' => VERSION,
			'url' => HTTP_CATALOG,
			'ssl' => HTTPS_CATALOG,
			'dir' => DIR_CATALOG,
			'image' => '',
			'server_ip' => $this->request->server['SERVER_ADDR'],
			'db_driver' => DB_DRIVER,
			'db_host' => DB_HOSTNAME,
			'db_user' => DB_USERNAME,
			'db_password' => DB_USERNAME,
			'db_name' => DB_DATABASE,
			'db_prefix' => DB_PREFIX,
			'admin_url' => HTTP_SERVER,
			'admin_user' => $this->user->getUserName(),
			'admin_password' => '',

		);

		$this->response->setOutput(json_encode($json));
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

		if(empty($this->request->post[$this->id.'_setting']['select'])){
			$this->error['select'] = $this->language->get('error_select');
			return false;
		}

		if(empty($this->request->post[$this->id.'_setting']['text'])){
			$this->error['text'] = $this->language->get('error_text');
			return false;
		}

		return true;
	}

	public function install() {
		$this->load->model('module/d_shopunity');
		$this->model_module_d_shopunity->setVqmod('a_vqmod_d_shopunity.xml', 1);

		$this->model_module_d_shopunity->installDependencies($this->mbooth);

		$this->getUpdate(1);	  
	}

	public function uninstall() {
		$this->load->model('module/d_shopunity');
		$this->model_module_d_shopunity->setVqmod('a_vqmod_d_shopunity.xml', 0);	
		$this->getUpdate(0);	  
	}


	/*
	*	Ajax: Get the update information on this module. 
	*/
	public function getUpdate($status = 1){
		if($status !== 0){	$status = 1; }

		$json = array();

		$this->load->language($this->route);
		$this->load->model($this->route);

		$current_version = $this->model_module_d_shopunity->getVersion($this->mbooth);
		$info = $this->model_module_d_shopunity->getUpdateInfo($this->mbooth, $status);

		if ($info['code'] == 200) {
			$data = simplexml_load_string($info['data']);

			if ((string) $data->version == (string) $current_version 
				|| (string) $data->version <= (string) $current_version) 
			{
				$json['success']   = $this->language->get('success_no_update') ;
			} 
			elseif ((string) $data->version > (string) $current_version) 
			{
				$json['warning']   = $this->language->get('warning_new_update');

				foreach($data->updates->update as $update)
				{
					if((string) $update->attributes()->version > (string)$current_version)
					{
						$version = (string)$update->attributes()->version;
						$json['update'][$version] = (string) $update[0];
					}
				}
			} 
			else 
			{
				$json['error']   = $this->language->get('error_update');
			}
		} 
		else 
		{ 
			$json['error']   =  $this->language->get('error_failed');
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>