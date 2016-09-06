<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityDeveloper extends Controller {

	private $codename = 'd_shopunity';
	private $route = 'd_shopunity/developer';
	private $extension = array();

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->model('d_shopunity/mbooth');
		$this->load->model('d_shopunity/account');
		$this->load->model('d_shopunity/extension');

		$this->extension = $this->model_d_shopunity_mbooth->getExtension($this->codename);
	}

	public function index(){

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/account/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$account = $this->config->get('d_shopunity_account');

		if(empty($account['developer'])){
			$this->response->redirect($this->url->link('d_shopunity/account/login', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$developer_id = $account['developer']['developer_id'];

   		$this->load->language('d_shopunity/extension');
   		$this->load->language('d_shopunity/tester');
   		$this->load->model('d_shopunity/developer');

   		$data['text_tester_status_1'] = $this->language->get('text_tester_status_1');
   		$data['text_tester_status_2'] = $this->language->get('text_tester_status_2');
   		$data['text_tester_status_3'] = $this->language->get('text_tester_status_3');
   		$data['text_tester_status_4'] = $this->language->get('text_tester_status_4');
   		$data['text_tester_status_5'] = $this->language->get('text_tester_status_5');
   		$data['text_tester_status_6'] = $this->language->get('text_tester_status_6');
   		
		$data['extensions'] = $this->model_d_shopunity_developer->getExtensions($developer_id);

   		$data['content_top'] = $this->load->controller('module/d_shopunity/content_top');
   		$data['content_bottom'] = $this->load->controller('module/d_shopunity/content_bottom');

   		$this->response->setOutput($this->load->view($this->route.'.tpl', $data));
	}

	public function profile($developer){
		$this->document->addStyle('view/stylesheet/d_shopunity/d_shopunity.css');
		$data['developer'] = $developer;

		return $this->load->view($this->route.'_profile.tpl', $data);
	}
}