<?php
class ControllerModuleDShopunity extends Controller {
	public function __construct($registry) {
		parent::__construct($registry);
		// Paths and Files
		$this->base_dir = substr_replace(DIR_SYSTEM, '/', -8);
		$this->mboot_script_dir = DIR_SYSTEM .'mbooth/xml/';

	}

	public function index() {


	}

	/**	
	 * Dowbload with index.php?route=module/d_shopunity/download&codename=d_shopunity&secret=test
	 * @return [type] [description]
	 */
	public function download(){
		if(isset($this->request->get['codename']) && isset($this->request->get['secret'])){

			//validate secret
			if($this->validateSecret($this->request->get['secret'])){
				$this->load->model('module/d_mbooth');

				$json = $this->model_module_d_mbooth->downloadExtension($this->request->get['codename']);

			}else{
				$json['error'] = "Error! Secret is invalid";

			}
		}
		if(!empty($json['error'])){
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	public function validateSecret($secret){
		return true;
	}
}
