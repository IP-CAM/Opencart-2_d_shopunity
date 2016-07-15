<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityTester extends Controller {

	private $codename = 'd_shopunity';
	private $route = 'd_shopunity/tester';
	private $extension = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('d_shopunity/mbooth');
		$this->load->model('d_shopunity/account');
		$this->load->model('d_shopunity/extension');

		//Mbooth file (example: mbooth_d_shopunity.xml)
		$this->extension = $this->model_d_shopunity_mbooth->getExtension($this->codename);

		//Check if all dependencies are installed
		$this->model_d_shopunity_mbooth->installDependencies($this->codename);
	}

	public function index(){

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/account/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$account = $this->config->get('d_shopunity_account');

		if(empty($account['tester'])){
			$this->response->redirect($this->url->link('d_shopunity/account/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$tester_id = $account['tester']['tester_id'];

   		$this->load->language('d_shopunity/tester');
   		$this->load->model('d_shopunity/extension');

		$data['extensions'] = $this->model_d_shopunity_extension->getTastableExtensions($tester_id);

   		$data['content_top'] = $this->load->controller('module/d_shopunity/content_top');
   		$data['content_bottom'] = $this->load->controller('module/d_shopunity/content_bottom');

   		$this->response->setOutput($this->load->view($this->route.'.tpl', $data));
	}

	public function approve(){

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/account/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$account = $this->config->get('d_shopunity_account');

		if(empty($account['tester'])){
			$this->response->redirect($this->url->link('d_shopunity/account/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(!isset($this->request->get['extension_download_link_id'])){
			$this->session->data['error'] = 'Error! extension_download_link_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		if(!isset($this->request->get['extension_id'])){
			$this->session->data['error'] = 'Error! extension_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		if(!isset($this->request->get['status'])){
			$this->session->data['error'] = 'Error! status missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$tester_id = $account['tester']['tester_id'];
		$extension_id = $this->request->get['extension_id'];
		$data['extension_download_link_id'] = $this->request->get['extension_download_link_id'];
		$data['status'] = $this->request->get['status'];
		$data['tester_comment'] = '';
		if(!empty($this->request->post['tester_comment'])){
			$data['tester_comment'] = $this->request->post['tester_comment'];
		}

   		$this->load->language('d_shopunity/tester');
   		$this->load->model('d_shopunity/extension');

		$response = $this->model_d_shopunity_extension->approveExtension($tester_id, $extension_id, $data);

   		if(!empty($response['error'])){
			$this->session->data['error'] = $response['error'];
		}elseif(!empty($response['success'])){
			$this->session->data['success'] = $response['success'];
		}

		//refactor
		if($data['status']){
			$this->response->redirect($this->url->link('d_shopunity/tester', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
	}

	
}