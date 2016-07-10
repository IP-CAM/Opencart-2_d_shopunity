<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityMarket extends Controller {
	private $id = 'd_shopunity';
	private $route = 'd_shopunity/market';
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
		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('module/d_shopunity', 'token=' . $this->session->data['token'], 'SSL'));
		}

		//documentation http://t4t5.github.io/sweetalert/
		$this->document->addStyle('view/javascript/d_shopunity/library/sweetalert/sweetalert.css');
		$this->document->addScript('view/javascript/d_shopunity/library/sweetalert/sweetalert.min.js');

		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addStyle('view/stylesheet/d_shopunity/d_shopunity.css');
		$this->document->addScript('view/javascript/d_shopunity/d_shopunity.js');
		
		$this->load->language('module/d_shopunity');
   		$this->load->language('d_shopunity/extension');
   		$this->load->model('module/d_shopunity');
   		$this->load->model('d_shopunity/extension');

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
			'href' => $this->url->link($this->route, 'token=' . $this->session->data['token'], 'SSL')
			);

   		$this->document->setTitle($this->language->get('heading_title'));
   		$data['heading_title'] = $this->language->get('heading_title');
		$data['stores'] = $this->model_module_d_shopunity->getStores();
   		$data['version'] = $this->model_module_d_shopunity->getVersion($this->mbooth);
		$data['text_edit'] = $this->language->get('text_edit');

		//language
		$data['tab_extension'] =  $this->language->get('tab_extension');
		$data['tab_market'] =  $this->language->get('tab_market');
		$data['tab_account'] =  $this->language->get('tab_account');
		$data['tab_backup'] =  $this->language->get('tab_backup');
		$data['tab_invoice'] =  $this->language->get('tab_invoice');
		$data['tab_transaction'] =  $this->language->get('tab_transaction');

		$data['href_extension'] =  $this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_market'] =  $this->url->link('d_shopunity/market', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_account'] =  $this->url->link('d_shopunity/account', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_backup'] = $this->url->link('d_shopunity/backup', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_invoice'] = $this->url->link('d_shopunity/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_transaction'] = $this->url->link('d_shopunity/transaction', 'token=' . $this->session->data['token'], 'SSL');

		$data['button_logout'] =  $this->language->get('button_logout');
		$data['logout'] = $this->url->link('d_shopunity/account/logout', 'token=' . $this->session->data['token'], 'SSL');
		$filter_data = array();
		$data['extensions'] = $this->model_d_shopunity_extension->getExtensions($filter_data);
		$data['purchase_url'] = $this->model_module_d_shopunity->ajax($this->url->link('d_shopunity/extension/purchase', 'token='.$this->session->data['token'], 'SSL')); 
		$data['categories'] = $this->load->controller('d_shopunity/market/categories'); 

   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view($this->route.'.tpl', $data));
	}

	public function categories(){
		$this->load->model('d_shopunity/category');

		$data['categories'] = $this->model_d_shopunity_category->getCategories();

		return $this->load->view('d_shopunity/categories.tpl', $data);

	}
}



