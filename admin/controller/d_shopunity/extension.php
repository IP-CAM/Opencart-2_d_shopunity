<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityExtension extends Controller {

	private $codename = 'd_shopunity';
	private $route = 'd_shopunity/extension';
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

   		$this->load->language('d_shopunity/extension');
   		$this->load->model('d_shopunity/extension');

   		$data['text_tester_status_1'] = $this->language->get('text_tester_status_1');
   		$data['text_tester_status_2'] = $this->language->get('text_tester_status_2');
   		$data['text_tester_status_3'] = $this->language->get('text_tester_status_3');
   		$data['text_tester_status_4'] = $this->language->get('text_tester_status_4');
   		$data['text_tester_status_5'] = $this->language->get('text_tester_status_5');
   		$data['text_tester_status_6'] = $this->language->get('text_tester_status_6');

		$data['store_extensions'] = $this->model_d_shopunity_extension->getStoreExtensions();
		$data['local_extensions'] = $this->model_d_shopunity_extension->getLocalExtensions();
		$data['unregestered_extensions'] = $this->model_d_shopunity_extension->getUnregisteredExtensions();

		$data['profile'] = $this->load->controller('d_shopunity/account/profile');

   		$data['content_top'] = $this->load->controller('module/d_shopunity/content_top');
   		$data['content_bottom'] = $this->load->controller('module/d_shopunity/content_bottom');

   		$this->response->setOutput($this->load->view($this->route.'.tpl', $data));
	}

	public function item(){
		

   		//is logged in
		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('d_shopunity/account/login', 'token=' . $this->session->data['token'], 'SSL'));
		}
		//extension id provided
		if(!isset($this->request->get['extension_id'])){
			$this->session->data['error'] = $this->language->get('error_extension_not_found');
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$extension_id = $this->request->get['extension_id'];

		$this->load->model('d_shopunity/store');
   		$this->load->model('d_shopunity/extension');

   		$this->load->language('module/d_shopunity');
   		$this->load->language('d_shopunity/extension');


		$data['extension'] = $this->model_d_shopunity_extension->getExtension($extension_id);

		if(isset($data['extension']['developer'])){
			$data['developer'] = $this->load->controller('d_shopunity/developer/profile', $data['extension']['developer']);
		}else{
			$data['developer'] = '';
		}
		
		//$extension_recurring_price_id = (isset($data['extension']['price'])) ? $data['extension']['price']['extension_recurring_price_id'] : 0;

		$data['purchase'] = $this->url->link('d_shopunity/extension/purchase', 'token=' . $this->session->data['token'] . '&extension_id=' . $extension_id , 'SSL');
		$data['install'] = $this->url->link('d_shopunity/extension/install', 'token=' . $this->session->data['token']  . '&extension_id=' . $extension_id , 'SSL');

   		$data['content_top'] = $this->load->controller('module/d_shopunity/content_top');
   		$data['content_bottom'] = $this->load->controller('module/d_shopunity/content_bottom');

   		$this->response->setOutput($this->load->view($this->route.'_item.tpl', $data));
	}

	public function purchase(){
		if(!isset($this->request->get['extension_id'])){
			$this->session->data['error'] = 'Error! extension_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		if(!isset($this->request->get['extension_recurring_price_id'])){
			$this->session->data['error'] = 'Error! extension_recurring_price_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$extension_id = $this->request->get['extension_id'];
		$extension_recurring_price_id = $this->request->get['extension_recurring_price_id'];
		$this->load->model('d_shopunity/extension');

		$purchase = $this->model_d_shopunity_extension->purchaseExtension($extension_id, $extension_recurring_price_id);

		if(!empty($purchase['error'])){
			$this->session->data['error'] = $purchase['error'];

		}elseif(!empty($purchase['success'])){
			$this->session->data['success'] = $purchase['success'];

			//create an invoice
			$this->load->model('d_shopunity/billing');
	   		$result = $this->model_d_shopunity_billing->addInvoice();

			if(!empty($result['error'])){
				$this->session->data['error'] = $result['error'];
			}elseif(!empty($result['invoice_id'])){
				$this->session->data['success'] = $result['success'];

				//make a purchase
				$invoice_id = $result['invoice_id'];
		   		$invoice = $this->model_d_shopunity_billing->payInvoice($invoice_id);

		   		if(!empty($invoice['error'])){
					$this->session->data['error'] = $invoice['error'];
				}elseif(!empty($invoice['success'])){
					$this->session->data['success'] = $invoice['success'];
				}
			}
		}

		$this->response->redirect($this->url->link('d_shopunity/extension/item', 'token=' . $this->session->data['token'] . '&extension_id=' . $extension_id , 'SSL'));

	}

	public function install(){
		if(!isset($this->request->get['extension_id'])){
			$this->session->data['error'] = 'Error! extension_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$extension_id = $this->request->get['extension_id'];
		$this->load->model('d_shopunity/extension');
		$this->load->model('d_shopunity/mbooth');

		try{

			$extension = $this->model_d_shopunity_extension->getExtension($extension_id);

			if(!$extension){
				$this->session->data['error'] = 'Error! this extension was not found on shopunity: '.$download['error'];
				$this->response->redirect($this->url->link('d_shopunity/extension/item', 'token=' . $this->session->data['token'] . '&extension_id='.$extension_id , 'SSL'));
			}

			$download = $this->model_d_shopunity_extension->getExtensionDownload($extension_id);

			if(!empty($download['error']) || empty($download['download'])){
				$this->session->data['error'] = 'Error! We cound not get the download link: '.$download['error'];
				$this->response->redirect($this->url->link('d_shopunity/extension/item', 'token=' . $this->session->data['token'] . '&extension_id='.$extension_id , 'SSL'));
			}

			$error_download = json_decode(file_get_contents($download['download']),true);
			if(isset($error_download['error'])){
				$this->session->data['error'] = 'Error! getExtensionDownload failed: '.json_encode($error_download['error']);
				$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
			}

			//download the extension to system/mbooth/download
			$extension_zip = $this->model_d_shopunity_mbooth->downloadExtensionFromServer($download['download']);
			if(isset($extension_zip['error'])){
				$this->session->data['error'] = 'Error! downloadExtensionFromServer failed: '.json_encode($extension_zip['error']);
				$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
			}

			//unzip the downloaded file to system/mbooth/download and remove the zip file
			$extracted = $this->model_d_shopunity_mbooth->extractExtension($extension_zip);
			if(isset($extracted['error'])){
				$this->session->data['error'] = 'Error! extractExtension failed: ' .json_encode($extracted['error']) . ' download from '.$download['download'];
				$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
			}

			$result = array();

			//BACKUP REFACTOR
			// if(file_exists(DIR_SYSTEM . 'mbooth/xml/'.$this->request->post['mbooth'])){
			// 	$result = $this->model_module_mbooth->backup_files_by_mbooth($this->request->post['mbooth'], 'update');
			// }

			$result = $this->model_d_shopunity_mbooth->installExtension($result);

			if(!empty($result['error'])) {
				$this->session->data['error'] = $this->language->get('error_install') . "<br />" . implode("<br />", $result['error']);
			}

			if(!empty($result['success'])) {

				$result = $this->model_d_shopunity_mbooth->installDependencies($extension['codename'], $result);

				$this->session->data['success'] = 'Extension #' . $this->request->get['extension_id'].' installed';
				$this->session->data['success'] .=  "<br />" . implode("<br />", $result['success']);
			}
		}catch(Exception $e){
			$this->session->data['error'] = $e->getMessage();
		}

		$this->response->redirect($this->url->link('d_shopunity/extension/item', 'token=' . $this->session->data['token'] . '&extension_id=' . $extension_id , 'SSL'));

	}

	public function test(){
		if(!isset($this->request->get['extension_download_link_id'])){
			$this->session->data['error'] = 'Error! extension_download_link_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		if(!isset($this->request->get['extension_id'])){
			$this->session->data['error'] = 'Error! extension_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$extension_download_link_id = $this->request->get['extension_download_link_id'];
		$extension_id = $this->request->get['extension_id'];
		$this->load->model('d_shopunity/extension');
		$this->load->model('d_shopunity/mbooth');

		try{

			$extension = $this->model_d_shopunity_extension->getExtension($extension_id);

			$download = $this->model_d_shopunity_extension->getExtensionDownloadByDownloadLinkId($extension_id, $extension_download_link_id);

			if(!empty($download['error']) || empty($download['download'])){
				$this->session->data['error'] = 'Error! We cound not get the download link: '.$download['error'];
				$this->response->redirect($this->url->link('d_shopunity/extension/item', 'token=' . $this->session->data['token'] . '&extension_id='.$extension_id , 'SSL'));
			}

			$error_download = json_decode(file_get_contents($download['download']),true);
			if(isset($error_download['error'])){
				$this->session->data['error'] = 'Error! getExtensionDownload failed: '.json_encode($error_download['error']);
				$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
			}

			//start testing
			//download the extension to system/mbooth/download
			$extension_zip = $this->model_d_shopunity_mbooth->downloadExtensionFromServer($download['download']);

			//unzip the downloaded file to system/mbooth/download and remove the zip file
			$extracted = $this->model_d_shopunity_mbooth->extractExtension($extension_zip);
			
			$result = array();

			//BACKUP REFACTOR
			// if(file_exists(DIR_SYSTEM . 'mbooth/xml/'.$this->request->post['mbooth'])){
			// 	$result = $this->model_module_mbooth->backup_files_by_mbooth($this->request->post['mbooth'], 'update');
			// }

			$result = $this->model_d_shopunity_mbooth->installExtension($result);

			
			if(!empty($result['success'])) {

				$result = $this->model_d_shopunity_mbooth->installDependencies($extension['codename'], $result);

				$this->session->data['success'] = 'Extension #' . $this->request->get['extension_id'].' installed';
				$this->session->data['success'] .=  "<br />" . implode("<br />", $result['success']);
			}

			if(!empty($result['error'])) {
				$this->session->data['error'] = $this->language->get('error_install') . "<br />" . implode("<br />", $result['error']);
			}

		}catch(Exception $e){
			$this->session->data['error'] = $e->getMessage();
		}

		$this->response->redirect($this->url->link('d_shopunity/extension/item', 'token=' . $this->session->data['token'] . '&extension_id=' . $extension_id , 'SSL'));

	}

	public function uninstall(){
		if(!isset($this->request->get['codename'])){
			$this->session->data['error'] = 'Error! codename missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$this->load->model('d_shopunity/mbooth');

		$result = $this->model_d_shopunity_mbooth->uninstallExtension($this->request->get['codename']);
		if(empty($result['error'])){
			$result = $this->model_d_shopunity_mbooth->deleteExtension($this->request->get['codename']);
		}
		
		if(!empty($result['error'])) {
			$this->session->data['error'] = $this->language->get('error_delete') . "<br />" . implode("<br />", $result['error']);
		}

		if(!empty($result['success'])) {
			$this->session->data['success'] = 'Extension #' . $this->request->get['codename'].' uninstalled';
			$this->session->data['success'] .=  "<br />" . implode("<br />", $result['success']);
		}

		$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));

	}

	public function download(){
		$this->load->language('d_shopunity/extension');
		if(!isset($this->request->get['codename'])){
			$this->session->data['error'] = 'Error! codename missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$this->load->model('d_shopunity/extension');
		$this->load->model('d_shopunity/mbooth');

		$mbooth = $this->model_d_shopunity_mbooth->getExtension($this->request->get['codename']);

		if(empty($mbooth)){
			$this->session->data['error'] = 'Error! extension with this codename does not exist';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$result = $this->model_d_shopunity_mbooth->downloadExtension($this->request->get['codename']);

		if(!empty($result['error'])) {
			$this->session->data['error'] = $this->language->get('error_download') . "<br />" . implode("<br />", $result['error']);
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL')); 
		}

	}

	public function suspend(){
		if(!isset($this->request->get['store_extension_id'])){
			$this->session->data['error'] = 'Error! store_extension_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$this->load->model('d_shopunity/extension');
		$purchase = $this->model_d_shopunity_extension->suspendExtension($this->request->get['store_extension_id']);

		if(!empty($purchase['error'])){
			$this->session->data['error'] = $purchase['error'];
		}elseif(!empty($purchase['success'])){
			$this->session->data['success'] = $purchase['success'];
		}

		$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL'));
	}


	public function submit(){
		if(!isset($this->request->get['extension_id'])){
			$this->session->data['error'] = 'Error! extension_id missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}
		try{
			$this->load->model('d_shopunity/extension');
			$result = $this->model_d_shopunity_extension->submitExtension($this->request->get['extension_id']);

			if(!empty($result['error'])){
				$this->session->data['error'] = $result['error'];
			}elseif(!empty($result['success'])){
				$this->session->data['success'] = $result['success'];
			}
		}catch(Exception $e){
			$this->session->data['error'] = $e->getMessage();
		}

		$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL'));
	}

	public function json(){
		$this->load->language('d_shopunity/extension');
		if(!isset($this->request->get['codename'])){
			$this->session->data['error'] = 'Error! codename missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		try{
			$this->load->model('d_shopunity/mbooth');
			$json = $this->model_d_shopunity_mbooth->getExtensionJson($this->request->get['codename']);

			if(empty($json)){
				$json['error'] = 'Error! extension.json not found';
			}
		}catch(Exception $e){
			$json['error'] = $e->getMessage();
		}

		$this->response->setOutput(json_encode($json));
	}

}