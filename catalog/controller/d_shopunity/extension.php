<?php
class ControllerDShopunityExtension extends Controller {
	public function __construct($registry) {
		parent::__construct($registry);
		// Paths and Files
		$this->base_dir = substr_replace(DIR_SYSTEM, '/', -8);
		$this->mboot_script_dir = DIR_SYSTEM .'mbooth/xml/';

	}

	/**	
	 * Get extension data with index.php?route=d_shopunity/extension&codename=d_shopunity&secret=test
	 */
	public function index() {

		if(isset($this->request->get['codename']) && isset($this->request->get['secret'])){
			//validate secret
			if($this->validateSecret($this->request->get['secret'])){
				$this->load->model('d_shopunity/mbooth');

				$json = $this->model_d_shopunity_mbooth->getExtension($this->request->get['codename']);
				if(empty($json)){
					$json['error'] = "Error! extension not found";
				}
			}else{
				$json['error'] = "Error! Secret is invalid";

			}
		}else{
			$json['error'] = "Error! codename or secret is missing";
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}

	/**	
	 * Dowbload with index.php?route=d_shopunity/extension/download&codename=d_shopunity&secret=test
	 */
	public function download(){
		if(isset($this->request->get['codename']) && isset($this->request->get['secret'])){

			//validate secret
			if($this->validateSecret($this->request->get['secret'])){
				$this->load->model('d_shopunity/mbooth');

				$json = $this->model_d_shopunity_mbooth->downloadExtension($this->request->get['codename']);

			}else{
				$json['error'] = "Error! Secret is invalid";

			}
		}else{
			$json['error'] = "Error! codename or secret is missing";
		}

		if(!empty($json['error'])){
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	/**	
	 * Delete with index.php?route=d_shopunity/extension/uninstall&codename=d_shopunity&secret=test
	 */
	public function uninstall(){

		// if(isset($this->request->get['codename']) && isset($this->request->get['secret'])){

		// 	if($this->validateSecret($this->request->get['secret'])){
		// 		$this->load->model('module/d_mbooth');

		// 		$json = $this->model_module_d_mbooth->deleteExtension($this->request->get['codename']);

		// 	}else{
		// 		$json['error'] = "Error! Secret is invalid";

		// 	}
		// }else{
		// 	$json['error'] = "Error! no codename or secret is missing";
		// }
		// if(!empty($json['error'])){
		// 	$this->response->addHeader('Content-Type: application/json');
		// 	$this->response->setOutput(json_encode($json));
		// }
	}

	public function validateSecret($secret){
		return true;
	}
}
