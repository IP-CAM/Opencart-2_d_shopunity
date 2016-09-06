<?php
/*
 *  location: admin/model
 */

class ModelDShopunityDeveloper extends Model {



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