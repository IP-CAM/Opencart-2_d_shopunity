<?php
/*
 *	location: admin/controller
 */

class ControllerDShopunityExtension extends Controller {
	private $id = 'd_shopunity';
	private $route = 'd_shopunity/extension';
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
		$data['profile'] = $this->load->controller('d_shopunity/account/profile');

		$data['store_extensions'] = $this->model_d_shopunity_extension->getStoreExtensions();
		$data['local_extensions'] = $this->model_d_shopunity_extension->getLocalExtensions();
		$data['unregestered_extensions'] = $this->model_d_shopunity_extension->getUnregisteredExtensions();

		
   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

   		$this->response->setOutput($this->load->view($this->route.'.tpl', $data));
	}

	public function item(){
		$this->load->language('module/d_shopunity');
   		$this->load->language('d_shopunity/extension');

		if(!$this->model_d_shopunity_account->isLogged()){
			$this->response->redirect($this->url->link('module/d_shopunity', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(!isset($this->request->get['extension_id'])){
			$this->session->data['error'] = $this->language->get('error_extension_not_found');
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$extension_id = $this->request->get['extension_id'];

		$this->load->model('d_shopunity/store');
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
   		$data['version'] = $this->model_module_d_shopunity->getVersion($this->mbooth);
		$data['text_edit'] = $this->language->get('text_edit');

		//language
		$data['tab_extension'] =  $this->language->get('tab_extension');
		$data['tab_market'] =  $this->language->get('tab_market');
		$data['tab_account'] =  $this->language->get('tab_account');
		$data['tab_backup'] =  $this->language->get('tab_backup');

		$data['href_extension'] =  $this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_market'] =  $this->url->link('d_shopunity/market', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_account'] =  $this->url->link('d_shopunity/account', 'token=' . $this->session->data['token'], 'SSL');
		$data['href_backup'] = $this->url->link('d_shopunity/backup', 'token=' . $this->session->data['token'], 'SSL');

		$data['button_logout'] =  $this->language->get('button_logout');
		$data['logout'] = $this->url->link('d_shopunity/account/logout', 'token=' . $this->session->data['token'], 'SSL');
		$data['store_info'] = $this->model_d_shopunity_store->getCurrentStore();

		
		$data['extension'] = $this->model_d_shopunity_extension->getExtension($extension_id);
		if(isset($data['extension']['developer'])){
			$data['developer'] = $this->load->controller('d_shopunity/developer/profile', $data['extension']['developer']);
		}else{
			$data['developer'] = '';
		}
		
		$extension_recurring_price_id = (isset($data['extension']['price'])) ? $data['extension']['price']['extension_recurring_price_id'] : 0;

		$data['purchase'] = $this->url->link('d_shopunity/extension/purchase', 'token=' . $this->session->data['token'] . '&extension_id=' . $extension_id , 'SSL');
		$data['install'] = $this->url->link('d_shopunity/extension/install', 'token=' . $this->session->data['token']  . '&extension_id=' . $extension_id , 'SSL');


   		$data['header'] = $this->load->controller('common/header');
   		$data['column_left'] = $this->load->controller('common/column_left');
   		$data['footer'] = $this->load->controller('common/footer');

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
		$download = $this->model_d_shopunity_extension->getExtensionDownload($extension_id);

		if(!empty($download['error']) || empty($download['download'])){
			$this->session->data['error'] = 'Error! We cound not get the download link';
			$this->response->redirect($this->url->link('d_shopunity/extension/item', 'token=' . $this->session->data['token'] . '&extension_id='.$extension_id , 'SSL'));
		}

		//download the extension to system/mbooth/download
		$extension_zip = $this->model_d_shopunity_extension->downloadExtension($download['download']);

		//unzip the downloaded file to system/mbooth/download and remove the zip file
		$extracted = $this->model_d_shopunity_extension->extractExtension($extension_zip);



		$result = array();
		// if(file_exists(DIR_SYSTEM . 'mbooth/xml/'.$this->request->post['mbooth'])){
		// 	$result = $this->model_module_mbooth->backup_files_by_mbooth($this->request->post['mbooth'], 'update');
		// }

		$result = $this->model_d_shopunity_extension->installExtension($result);

		$this->session->data['success'] = 'Extension #' . $this->request->get['extension_id'].' installed';

		if(!empty($result['error'])) {
			$this->session->data['error'] = $this->language->get('error_install') . "<br />" . implode("<br />", $result['error']);
		}

		if(!empty($result['success'])) {
			$this->session->data['success'] .=  "<br />" . implode("<br />", $result['success']);
		}

		$this->response->redirect($this->url->link('d_shopunity/extension/item', 'token=' . $this->session->data['token'] . '&extension_id=' . $extension_id , 'SSL'));

	}

	public function uninstall(){
		if(!isset($this->request->get['codename'])){
			$this->session->data['error'] = 'Error! codename missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$this->load->model('d_shopunity/extension');

		$result = $this->model_d_shopunity_extension->deleteExtension($this->request->get['codename']);

		$this->session->data['success'] = 'Extension #' . $this->request->get['codename'].' uninstalled';

		// if(!empty($result['error'])) {
		// 	$this->session->data['error'] = $this->language->get('error_delete') . "<br />" . implode("<br />", $result['error']);
		// }

		// if(!empty($result['success'])) {
		// 	$this->session->data['success'] .=  "<br />" . implode("<br />", $result['success']);
		// }

		$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));

	}

	public function download(){
		$this->load->language('d_shopunity/extension');
		if(!isset($this->request->get['codename'])){
			$this->session->data['error'] = 'Error! codename missing';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$this->load->model('d_shopunity/extension');

		$mbooth = $this->model_d_shopunity_extension->getMboothByCodename($this->request->get['codename']);

		if(empty($mbooth)){
			$this->session->data['error'] = 'Error! extension wit this codename does not exist';
			$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'] , 'SSL'));
		}

		$result = $this->model_d_shopunity_extension->zipExtension($this->request->get['codename']);

		if(!empty($result['error'])) {
			$this->session->data['error'] = $this->language->get('error_download') . "<br />" . implode("<br />", $result['error']);
		}else if(!empty($result['success'])) {
			$this->session->data['success'] = $this->language->get('success_download');
		}

		$this->response->redirect($this->url->link('d_shopunity/extension', 'token=' . $this->session->data['token'], 'SSL')); 
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

}