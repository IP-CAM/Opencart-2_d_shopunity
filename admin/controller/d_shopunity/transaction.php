<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityTransaction extends Controller {

	private $codename = 'd_shopunity';
	private $route = 'd_shopunity/transaction';
	private $extension = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('d_shopunity/mbooth');
		$this->load->model('d_shopunity/account');

		//Mbooth file (example: mbooth_d_shopunity.xml)
		$this->extension = $this->model_d_shopunity_mbooth->getExtension($this->codename);

		//Check if all dependencies are installed
		$this->model_d_shopunity_mbooth->installDependencies($this->codename);
	}


	public function index(){
		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/account/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

   		$this->load->language('d_shopunity/billing');
   		$this->load->model('d_shopunity/billing');

   		
		$data['tab_order'] =  $this->language->get('tab_order');
		$data['tab_invoice'] =  $this->language->get('tab_invoice');
		$data['tab_transaction'] =  $this->language->get('tab_transaction');

		$data['href_order'] =  $this->url->link('d_shopunity/order', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_invoice'] = $this->url->link('d_shopunity/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_transaction'] = $this->url->link('d_shopunity/transaction', 'token=' . $this->session->data['token'], 'SSL');

		$data['transactions'] = $this->model_d_shopunity_billing->getTransactions();
		$data['profile'] = $this->load->controller('d_shopunity/account/profile');
		
   		$data['content_top'] = $this->load->controller('module/d_shopunity/content_top');
   		$data['content_bottom'] = $this->load->controller('module/d_shopunity/content_bottom');

   		$this->response->setOutput($this->load->view($this->route.'.tpl', $data));
	}


}