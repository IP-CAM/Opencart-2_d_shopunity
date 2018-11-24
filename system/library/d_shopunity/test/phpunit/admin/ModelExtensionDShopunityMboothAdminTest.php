<?php

class ModelExtensionDShopunityMboothAdminTest extends OpenCartTest
{
    public function test_if_extension_returns_valid_json()
    {   
        //$this->login(getenv('OC_ADMIN_USER'),getenv('OC_ADMIN_PASS'));

        $this->load->model('extension/d_shopunity/mbooth');
        $output = $this->model_extension_d_shopunity_mbooth->_extension(json_decode(file_get_contents(DIR_SYSTEM . 'library/d_shopunity/extension/d_shopunity.json'), true));
        $this->assertTrue(
            isset($output['codename']) 
            && isset($output['version'])
            && isset($output['name'])
            && isset($output['description'])
            && isset($output['opencart_version'])
            && isset($output['type'])
            && isset($output['install'])
            && isset($output['uninstall'])
            && isset($output['files'])
            && isset($output['changelog'])
        );
        $this->assertEquals('extension/d_shopunity', $output['index'] );
        
    }
}