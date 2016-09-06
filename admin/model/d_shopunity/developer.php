<?php
/*
 *  location: admin/model
 */

class ModelDShopunityDeveloper extends Model {

	private $store_id = '';
    private $api = '';
    private $dir_root = '';

    public function __construct($registry){
        parent::__construct($registry);
        $this->api = new Shopunity($registry);
        $this->store_id = $this->api->getStoreId();
        $this->dir_root = substr_replace(DIR_SYSTEM, '/', -8);
      
    }

    public function getExtensions($developer_id){

        $data = array(
            'shared' => true
        );

        $json = $this->api->get('developers/'.$developer_id.'/extensions', $data);

        return $this->_extension($json);
    }


    public function _extension($data){
		$this->load->model('d_shopunity/extension');
        return $this->model_d_shopunity_extension->_extension($data);
    }


}