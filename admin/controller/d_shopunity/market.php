<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityMarket extends Controller {
	
	private $codename = 'd_shopunity';
	private $route = 'd_shopunity/market';
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

		//documentation http://t4t5.github.io/sweetalert/
		$this->document->addStyle('view/javascript/d_shopunity/library/sweetalert/sweetalert.css');
		$this->document->addScript('view/javascript/d_shopunity/library/sweetalert/sweetalert.min.js');

		$this->document->addStyle('view/stylesheet/shopunity/bootstrap.css');
		$this->document->addStyle('view/stylesheet/d_shopunity/d_shopunity.css');
		$this->document->addScript('view/javascript/d_shopunity/d_shopunity.js');
		
   		$this->load->language('d_shopunity/extension');
   		$this->load->model('d_shopunity/extension');

		//REFACTOR
		$filter_data = array(
			'status' => 1);
		$data['extensions'] = $this->model_d_shopunity_extension->getExtensions($filter_data);
		$data['categories'] = $this->load->controller('d_shopunity/market/categories'); 

   		$data['content_top'] = $this->load->controller('module/d_shopunity/content_top');
   		$data['content_bottom'] = $this->load->controller('module/d_shopunity/content_bottom');

   		$this->response->setOutput($this->load->view($this->route.'.tpl', $data));
	}

	public function categories(){
		$this->load->model('d_shopunity/category');

		$data['categories'] = $this->model_d_shopunity_category->getCategories();

		return $this->load->view('d_shopunity/categories.tpl', $data);

	}
}



