<?php
/*
 *	location: admin/model
 *
 */

class ModelDShopunityConfig extends Model {

	private $subversions = array('lite', 'light', 'free');
	/*
	*	Return name of config file.
	*/
	public function getConfigFileName($codename){
		
		if(isset($this->request->post['config'])){
			return $this->request->post['config'];
		}

		$setting = $this->config->get($codename.'_setting');

		if(isset($setting['config'])){
			return $setting['config'];
		}

		$full = DIR_SYSTEM . 'config/'. $codename . '.php';
		if (file_exists($full)) {
			return $codename;
		} 

		foreach ($this->subversions as $subversion){
			if (file_exists(DIR_SYSTEM . 'config/'. $codename . '_' . $subversion . '.php')) {
				return $codename . '_' . $subversion;
			}
		}
		
		return false;
	}

	/*
	*	Return list of config files that contain the codename of the module.
	*/
	public function getConfigFileNames($codename){
		$files = array();
		$results = glob(DIR_SYSTEM . 'config/'. $codename .'*');
		foreach($results as $result){
			$files[] = str_replace('.php', '', str_replace(DIR_SYSTEM . 'config/', '', $result));
		}
		return $files;
	}

	/*
	*	Get config file values and merge with config database values
	*
	* REFACTOR NEEDED
	*/
	public function getConfigData($id, $config_key, $store_id, $config_file = false){
		if(!$config_file){
			$config_file = $this->config_file;
		}
		if($config_file){
			$this->config->load($config_file);
		}

		$result = ($this->config->get($config_key)) ? $this->config->get($config_key) : array();

		if(!isset($this->request->post['config'])){

			$this->load->model('setting/setting');
			if (isset($this->request->post[$config_key])) {
				$setting = $this->request->post;
			} elseif ($this->model_setting_setting->getSetting($id, $store_id)) { 
				$setting = $this->model_setting_setting->getSetting($id, $store_id);
			}
			if(isset($setting[$config_key])){
				foreach($setting[$config_key] as $key => $value){
					$result[$key] = $value;
				}
			}
			
		}
		return $result;
	}

	public function getStores(){
		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();
		$result = array();
		if($stores){
			$result[] = array(
				'store_id' => 0, 
				'name' => $this->config->get('config_name')
				);
			foreach ($stores as $store) {
				$result[] = array(
					'store_id' => $store['store_id'],
					'name' => $store['name']	
					);
			}	
		}
		return $result;
	}
}