<?php
/*
 *  location: admin/model
 */

class ModelDShopunityExtension extends Model {

    private $store_id = '';
    private $api = '';
    private $dir_root = '';

    public function __construct($registry){
        parent::__construct($registry);
        $this->api = new Shopunity($registry);
        $this->store_id = $this->api->getStoreId();
        $this->dir_root = substr_replace(DIR_SYSTEM, '/', -8);
      
    }

    public function getExtensions($filter_data = array()){
        $json = $this->api->get('extensions', $filter_data);

        if($json){
            foreach($json as $key => $value){
                $json[$key] = $this->_extension($value);
            }  
        }

        return $json;  
    }


    public function getStoreExtensions($store_id = false){
        if(!$store_id){
            $store_id = $this->store_id;
        }
        $json = $this->api->get('stores/'.$store_id.'/extensions');

        if($json){
            foreach($json as $key => $value){
                $json[$key] = $this->_extension($value);
            }
        }
        
        return $json;
    }

    public function getLocalExtensions(){

        //Return mbooth files.
        $codenames = array();
        $this->load->model('module/d_mbooth');

        $installed_extensions = $this->model_module_d_mbooth->getExtensions();
        foreach($installed_extensions as $extension){
            $codenames[] = $extension['codename'];
        }
        
        $filter_data = array(
            'codename' => implode(',', $codenames)
        );

        $extensions = $this->getExtensions($filter_data);
        if($extensions){
            foreach($extensions as $id => $extension){
                if($extension['store_extension']){
                    unset($extensions[$id]);
                }
            }
        }

        return $extensions;
    }

    public function getUnregisteredExtensions(){
        $codenames = array();
        $unregistered_extensions = array();
        $this->load->model('module/d_mbooth');

        $installed_extensions = $this->model_module_d_mbooth->getExtensions();
        foreach($installed_extensions as $extension){
            $codenames[] = $extension['codename'];
            $unregistered_extensions[$extension['codename']] = $extension;
        }

        
        $filter_data = array(
            'codename' => implode(',', $codenames)
        );

        $extensions = $this->getExtensions($filter_data);
        $result = array();
        
        if($extensions){
            foreach( $extensions as $extension ){
                unset($unregistered_extensions[$extension['codename']]);
            }

            
            foreach($unregistered_extensions as $extension ){
                $result[] = $this->_mbooth_extension($extension);
            }
        }
        
        return $result;
    }

    public function getExtension($extension_id){

        $json = $this->api->get('extensions/'.$extension_id);

        return $this->_extension($json);
    }

    public function purchaseExtension($extension_id, $extension_recurring_price_id){
        $data = array(
            'extension_id' => $extension_id,
            'extension_recurring_price_id' => $extension_recurring_price_id
        );
        $result = $this->api->post('stores/'.$this->store_id.'/extensions', $data);

        return $result;
    }

    public function suspendExtension($store_extension_id){
        $result = $this->api->delete('stores/'.$this->store_id.'/extensions/'.$store_extension_id);

        return $result;
    }

    public function getExtensionDownload($extension_id){
        $data = array(
            'store_version' => VERSION,
            'store_id' => $this->store_id);
        $result = $this->api->get('extensions/'.$extension_id.'/download', $data);
        return $result;
    }

    public function getExtensionDownloadByCodename($codename){
        $data = array(
            'store_version' => VERSION,
            'store_id' => $this->store_id);
        $result = $this->api->get('extensions/'.$codename.'/download', $data);
        return $result;
    }

    public function isInstalled($codename){
        if(file_exists(DIR_SYSTEM . 'mbooth/extension/'.$codename.'.json')){
            return true;
        }
        return false;
    }

    public function _extension($data){
        $result = array();

        if(!empty($data)){
            $result = $data;
            $result['url'] = $this->url->link('d_shopunity/extension/item', 'token='.$this->session->data['token'].'&extension_id='.$data['extension_id'],'SSL');
            if($data['prices']){
                $result['price'] = array();
                foreach( $data['prices'] as $price){
                    if($price['recurring_duration'] >= 365){
                        $result['price'] = $price;
                        break;
                    }
                }
            }
            $result['registered'] = true;
            $result['installed'] = $this->isInstalled($data['codename']);
           
            $result['purchase'] = $this->_ajax($this->url->link('d_shopunity/extension/purchase', 'token=' . $this->session->data['token'] . '&extension_id=' . $data['extension_id'] , 'SSL'));
            $result['install'] = $this->_ajax($this->url->link('d_shopunity/extension/install', 'token=' . $this->session->data['token']  . '&extension_id=' . $data['extension_id'] , 'SSL'));
            $result['update'] = $this->_ajax($this->url->link('d_shopunity/extension/install', 'token=' . $this->session->data['token']  . '&extension_id=' . $data['extension_id'] , 'SSL'));
            $result['download'] = $this->_ajax($this->url->link('d_shopunity/extension/download', 'token='.$this->session->data['token'] . '&codename='.$data['codename'] ));
            $result['uninstall'] = $this->_ajax($this->url->link('d_shopunity/extension/uninstall', 'token=' . $this->session->data['token']  . '&codename='.$data['codename'] , 'SSL'));
            if(!empty($data['store_extension'])){
                $result['suspend'] = $this->_ajax($this->url->link('d_shopunity/extension/suspend', 'token=' . $this->session->data['token']  . '&store_extension_id='.$data['store_extension']['store_extension_id'] , 'SSL'));
            }else{
                $result['suspend'] = '';
            }
            

        }

        return $result;

    }

    private function _ajax($url){
        return html_entity_decode($url);
    }

    private function _mbooth_extension($data){

        $result = array();

        if(!empty($data)){
            $this->load->model('tool/image');
            $image_thumb = (!empty($data['images']['thumb'])) ? $data['images']['thumb'] : $this->model_tool_image->resize('catalog/d_shopunity/no_image.jpg', 320, 200);
            $image_main = (!empty($data['images']['main'])) ? $data['images']['main'] : $this->model_tool_image->resize('catalog/d_shopunity/no_image.jpg', 640, 400);
            
            $result = $data;
            $result['name'] = trim($data['name']);
            $result['url'] = '';
            $data['prices'] = '';
            $result['image'] = $image_main;
            $result['processed_images'] = array(
                0 => array(
                    'width' => 320,
                    'hight' => 200,
                    'url' => $image_thumb
                    ),
                1 => array(
                    'width' => 640,
                    'hight' => 400,
                    'url' => $image_thumb
                    )
                );
            $result['installed'] = true;
            $result['registered'] = false;
            $result['store_extension'] = false;

            $result['installable'] = true;
            $result['updatable'] = false;
            $result['downloadable'] = true;
            $result['purchasable'] = false;
            $result['suspendable'] = false;
            
            $result['purchase'] = '';
            $result['install'] = '';
            $result['update'] = '';
            $result['download'] = $this->_ajax($this->url->link('d_shopunity/extension/download', 'token='.$this->session->data['token'].'&codename='.$result['codename'] ));
            $result['uninstall'] = $this->_ajax($this->url->link('d_shopunity/extension/uninstall', 'token='.$this->session->data['token'].'&codename='.$result['codename'] ));
            $result['suspend'] = '';
        }

        return $result;
    }


}