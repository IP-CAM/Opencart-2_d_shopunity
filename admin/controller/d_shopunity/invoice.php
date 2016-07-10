<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityInvoice extends Controller {
	private $id = 'd_shopunity';
	private $codename = 'd_shopunity';
	private $route = 'd_shopunity/invoice';
	private $sub_versions = array('lite', 'light', 'free');
	private $mbooth = '';
	private $config_file = '';
	private $prefix = '';
	private $store_id = 0;
	private $error = array(); 
	private $client_id = 'd_shopunity';

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('module/d_mbooth');
		$this->load->model('module/d_shopunity');
		$this->load->model('d_shopunity/account');

		//Mbooth file (example: mbooth_d_shopunity.xml)
		$this->mbooth = $this->model_module_d_shopunity->getMboothFile($this->id, $this->sub_versions);

		//store_id (for multistore)
		// if (isset($this->request->get['store_id'])) { 
		// 	$this->store_id = $this->request->get['store_id']; 
		// }

		//Config File (example: d_shopunity)
		$this->config_file = $this->model_module_d_shopunity->getConfigFile($this->id, $this->sub_versions);

		//Check if all dependencies are installed
		//$this->model_module_d_shopunity->installDependencies($this->mbooth);

	}

	public function index(){

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->document->addStyle('view/stylesheet/d_shopunity/d_shopunity.css');
		
		$this->load->language('module/d_shopunity');
   		$this->load->language('d_shopunity/account');
   		$this->load->model('module/d_shopunity');
   		$this->load->model('d_shopunity/billing');

   		// $this->load->model('d_shopunity/account');
   		// $this->load->model('d_shopunity/extension');
   		// $this->load->model('d_shopunity/store');

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

		if(!empty($this->session->data['error'])){
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		if(!empty($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

   		$this->document->setTitle($this->language->get('heading_title'));
   		$data['heading_title'] = $this->language->get('heading_title');
		$data['stores'] = $this->model_module_d_shopunity->getStores();
   		$data['version'] = $this->model_module_d_mbooth->getVersion($this->codename);
		$data['text_edit'] = $this->language->get('text_edit');

		//language
		$data['tab_extension'] =  $this->language->get('tab_extension');
		$data['tab_market'] =  $this->language->get('tab_market');
		$data['tab_account'] =  $this->language->get('tab_account');
		$data['tab_backup'] =  $this->language->get('tab_backup');
		$data['tab_order'] =  $this->language->get('tab_order');
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
		$data['profile'] = $this->load->controller('d_shopunity/account/profile');

		$data['invoices'] = $this->model_d_shopunity_billing->getInvoices();
		
   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view($this->route.'.tpl', $data));
	}

	public function item(){

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(!isset($this->request->get['invoice_id'])){

			$this->session->data['error'] = 'Order_id missing!';
			$this->response->redirect($this->url->link('d_shopunity/invoice', 'token=' . $this->session->data['token'], 'SSL'));

		}

		$invoice_id = $this->request->get['invoice_id'];

		$this->document->addStyle('view/stylesheet/d_shopunity/d_shopunity.css');
		
		$this->load->language('module/d_shopunity');
   		$this->load->language('d_shopunity/account');
   		$this->load->model('module/d_shopunity');
   		$this->load->model('d_shopunity/billing');

   		// $this->load->model('d_shopunity/account');
   		// $this->load->model('d_shopunity/extension');
   		// $this->load->model('d_shopunity/store');

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

		if(!empty($this->session->data['error'])){
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		if(!empty($this->session->data['success'])){
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

   		$this->document->setTitle($this->language->get('heading_title'));
   		$data['heading_title'] = $this->language->get('heading_title');
		$data['stores'] = $this->model_module_d_shopunity->getStores();
   		$data['version'] = $this->model_module_d_mbooth->getVersion($this->codename);
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

		$data['invoice'] = $this->model_d_shopunity_billing->getInvoice($invoice_id);
		$data['profile'] = $this->load->controller('d_shopunity/account/profile');

   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view($this->route.'_item.tpl', $data));
	}

	public function create(){

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->load->model('d_shopunity/billing');

   		$result = $this->model_d_shopunity_billing->addInvoice();

		if(!empty($result['error'])){
			$this->session->data['error'] = $result['error'];
		}elseif(!empty($result['success'])){
			$this->session->data['success'] = $result['success'];
		}

		$this->response->redirect($this->url->link('d_shopunity/account', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function pay(){

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(!isset($this->request->get['invoice_id'])){

			$this->session->data['error'] = 'order_id missing!';
			$this->response->redirect($this->url->link('d_shopunity/invoice', 'token=' . $this->session->data['token'], 'SSL'));

		}

		$invoice_id = $this->request->get['invoice_id'];

   		$this->load->model('d_shopunity/billing');

   		$invoice = $this->model_d_shopunity_billing->payInvoice($invoice_id);

   		if(!empty($invoice['error'])){
			$this->session->data['error'] = $invoice['error'];
		}elseif(!empty($invoice['success'])){
			$this->session->data['success'] = $invoice['success'];
		}

		$this->response->redirect($this->url->link('d_shopunity/invoice', 'token=' . $this->session->data['token'], 'SSL'));

	}

}