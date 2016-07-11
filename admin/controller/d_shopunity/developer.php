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
		
		//Mbooth file (example: mbooth_d_shopunity.xml)
		$this->extension = $this->model_d_shopunity_mbooth->getExtension($this->codename);

		//Check if all dependencies are installed
		$this->model_d_shopunity_mbooth->installDependencies($this->codename);
	}

	public function index(){

	}

	public function profile($developer){
		$this->document->addStyle('view/stylesheet/d_shopunity/d_shopunity.css');
		$data['developer'] = $developer;

		return $this->load->view($this->route.'_profile.tpl', $data);
	}
}