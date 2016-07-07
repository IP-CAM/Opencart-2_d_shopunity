<?php
/*
 *	location: admin/model
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

        return $json;	}


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
        $scripts = glob(DIR_SYSTEM . 'mbooth/xml/*');
        foreach($scripts as $script){
            $xml = simplexml_load_string(file_get_contents($script), "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $codenames[] = json_decode($json,TRUE)['id'];
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
        
        // echo '<pre>';
        // print_r( $result);
        //Collect data from mbooth files.
        //
        //
        // $result = array();
        // if ($path) {
        //     $scripts = glob(DIR_SYSTEM . 'mbooth/xml/*');
        //     if (!empty($scripts)) {
        //         $result = array_merge($result, $scripts);
        //     }
        // } else {
        //     $result = scandir(DIR_SYSTEM . 'mbooth/xml/');
        // }
        return $extensions;
    }

    public function getUnregisteredExtensions(){
        $codenames = array();
        $scripts = glob(DIR_SYSTEM . 'mbooth/xml/*');
        foreach($scripts as $script){
            $xml = simplexml_load_string(file_get_contents($script), "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $codenames[] = json_decode($json,TRUE)['id'];
            $unregistered_extensions[json_decode($json,TRUE)['id']] = json_decode($json,TRUE);
        }

        
        $filter_data = array(
            'codename' => implode(',', $codenames)
        );

        $extensions = $this->getExtensions($filter_data);

        foreach( $extensions as $extension ){
            unset($unregistered_extensions[$extension['codename']]);
        }
        $result = array();
        foreach($unregistered_extensions as $extension ){
            $result[] = $this->_mbooth_extension($extension);
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

	public function downloadExtension($download_link){

        $filename = DIR_SYSTEM . 'mbooth/download/extension.zip';
        $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';

        $ch = curl_init();
        $fp = fopen($filename, "w");
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_URL, $download_link);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        $page = curl_exec($ch);
        if (!$page) {
            exit;
        }
        curl_close($ch);

        return $filename;
    }

     public function extractExtension($filename = false, $location = false) {
        if (!$filename) {
            $filename = DIR_SYSTEM . 'mbooth/download/extension.zip';
        }
        if (!$location) {
            $location = dirname($filename);
        }

        $result = array();
        $zip = new ZipArchive;
        if (!$zip) {
            $result['error'][] = 'ZipArchive not working.';
        }

        $res = $zip->open($filename, ZipArchive::CHECKCONS);
        if ($res !== TRUE) {
            switch($res) {
                case ZipArchive::ER_NOZIP:
                    $result['error'][] = 'not a zip archive';
                case ZipArchive::ER_INCONS :
                    $result['error'][] = 'consistency check failed';
                case ZipArchive::ER_CRC :
                    $result['error'][] = 'checksum failed';
                default:
                    $result['error'][] = 'error ' . $res;
            }
        }else{
            if ($zip->open($filename) != "true") {
                $result['error'][] = $filename;
            }
            $zip->extractTo($location);
            $zip->close();
        }

        unlink($filename);

        return $result;
    }

    public function getMboothFileByCodename($codename){
        $file = DIR_SYSTEM.'mbooth/xml/mbooth_'.$codename.'.xml';
        if(file_exists($file)){
            return $file;
        }
        $file = DIR_SYSTEM.'mbooth/xml/'.$codename.'.xml';
        if(file_exists($file)){
            return $file;
        }
        return false;
    }

    public function getMboothByCodename($codename){
        $mbooth_file = $this->getMboothFileByCodename($codename);
        if($mbooth_file){

            $xml = new SimpleXMLElement(file_get_contents($mbooth_file));

            if (isset($xml->id)) {
                $result['file_name'] = basename($mbooth_file, '');
                $result['id'] = isset($xml->id) ? (string) $xml->id : '';
                $result['name'] = isset($xml->name) ? (string) $xml->name : '';
                $result['description'] = isset($xml->description) ? (string) $xml->description : '';
                $result['type'] = isset($xml->type) ? (string) $xml->type : '';
                $result['version'] = isset($xml->version) ? (string) $xml->version : '';
                $result['mbooth_version'] = isset($xml->mbooth_version) ? (string) $xml->mbooth_version : '';
                $result['opencart_version'] = isset($xml->opencart_version) ? (string) $xml->opencart_version : '';
                $result['author'] = isset($xml->author) ? (string) $xml->author : '';
                $files = $xml->files;
                $dirs = $xml->dirs;
                $required = $xml->required;
                $updates = $xml->update;

                foreach ($files->file as $file) {
                    $result['files'][] = (string) $file;
                }

                if (!empty($dirs)) {

                    $dir_files = array();

                    foreach ($dirs->dir as $dir) {
                        $this->getFiles($this->dir_root . $dir, $dir_files);
                    }

                    foreach ($dir_files as $file) {
                        $file = str_replace($this->dir_root, "", $file);
                        $result['files'][] = (string) $file;
                    }
                }

                return $result;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function zipExtension($codename){
        $mbooth = $this->getMboothByCodename($codename);
        if($mbooth){
            $temp = tempnam(ini_get('upload_tmp_dir'), 'zip');
            $zip = new ZipArchive();
            $zip->open($temp, ZipArchive::OVERWRITE);

            foreach ($mbooth['files'] as $file) {

                if (file_exists($this->dir_root . $file)) {

                    if (is_file($this->dir_root . $file)) {
                        $zip->addFile($this->dir_root . $file, 'upload/' . $file);

                        $result['success'][] = $file;
                    } else {
                        $result['error'][] = $file;
                    }
                } else {
                    $result['error'][] = $file;
                }
            }

            //add install.xml file for opencart automatic installer.
            $file = 'install.xml';
            $create_install_xml = false;
            if(file_exists($this->dir_root . 'install_'.$codename. '.xml')){
                $zip->addFile($this->dir_root . 'install_'.$codename. '.xml', $file);
            }elseif (file_exists($this->dir_root . $file)) {
                $zip->addFile($this->dir_root . $file, $file);
            }else{
                if ($this->createInstallXml($mbooth)) {
                    $zip->addFile($this->dir_root . $file, $file);
                    $result['success'][] = $file;
                    $create_install_xml = true;
                } else {
                    $result['error'][] = "Could not create Install.xml";
                }
            }
         
            $zip->close();

            if ($create_install_xml) {
                 if(!$this->deleteInstallXml()){
                     $result['error'][] = "Could not delete Install.xml";
                 }
            }

            if (empty($result['error'])) {
                header('Pragma: public');
                header('Expires: 0');
                header('Content-Description: File Transfer');
                header('Content-Type: mbooth/xml');
                header('Content-Disposition: attachment; filename=' . $codename . '_' . date('Y-m-d') . '.ocmod' . '.zip');
                header('Content-Transfer-Encoding: binary');
                readfile($temp);
                unlink($temp);
            }

            return $result;
        }else{
            return false;
        }
    }

    public function deleteExtension($codename){
        $mbooth = $this->getMboothByCodename($codename);
        if($mbooth){
            $result = array('success' => array(), 'error' => array());
            foreach ($mbooth['files'] as $file) {
                if (is_file(DIR_ROOT . $file)) {

                    if (@unlink(DIR_ROOT . $file)) {
                        $result['success'][] = $file;
                    } else {
                        $result['error'][] = $file;
                    }

                    $dir = dirname($this->base_dir . $file);
                    while (strlen($dir) > strlen($this->base_dir)) {
                        if (is_dir($dir)) {
                            if ($this->isDirEmpty($dir)) {
                                if (@rmdir($dir)) {
                                    $result['success'][] = dirname($dir);
                                    $dir = dirname($dir);
                                } else {
                                    $result['error'][] = dirname($dir);
                                }
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                } else {
                    $result['error'][] = $file;
                }
            }
        }else{
            $result = false;
        }
        return $result;
    }

    public function createInstallXml($mbooth){

        $file = fopen($this->dir_root . "install.xml", "wb");
        $txt = "<modification>
    <name>" . (!empty($mbooth['name'])) ? $mbooth['name'] : '' . "</name>
    <code>" . (!empty($mbooth['id'])) ? $mbooth['id'] : '' . "</code>
    <version>" . (!empty($mbooth['version'])) ? $mbooth['version'] : '' . "</version>
    <author>" . (!empty($mbooth['author'])) ? $mbooth['author'] : ''. "</author>
</modification>";
        fwrite($file, $txt);
        fclose($file);
        return 'install.xml';
    }

    public function deleteInstallXml() {
        return unlink($this->dir_root . 'install.xml');
    }

    public function getFiles($dir, &$arr_files) {

        if (is_dir($dir)) {
            $handle = opendir($dir);
            while ($file = readdir($handle)) {
                if ($file == '.' or $file == '..')
                    continue;
                if (is_file($file))
                    $arr_files[] = "$dir/$file";
                else
                    $this->getFiles("$dir/$file", $arr_files);
            }
            closedir($handle);
        }else {
            $arr_files[] = $dir;
        }
    }

    public function moveFiles($from, $to, $result) {

        if(file_exists($from)){
            $files = scandir($from);

            foreach ($files as $file) {

                if ($file == '.' || $file == '..' || $file == '.DS_Store')
                    continue;

                if (is_dir($from . $file)) {
                    if (!file_exists($to . $file . '/')) {
                        mkdir($to . $file . '/', 0777, true);
                    }
                    $result = $this->moveFiles($from . $file . '/', $to . $file . '/', $result);
                } elseif (rename($from . $file, $to . $file)) {
                    $result['success'][] = str_replace($this->dir_root, '', $to . $file);
                } else {
                    $result['error'][] = str_replace($this->dir_root, '', $to . $file);
                }
            }

            $this->deleteFiles($from);
        }else{
            $result['error'][] = $from;
        }

        return $result;
    }

    public function deleteFiles($path){
    	if (is_dir($path)) {
            $objects = scandir($path);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($path . "/" . $object) == "dir")
                        $this->delete_dir($path . "/" . $object);
                    else
                        unlink($path . "/" . $object);
                }
            }
            reset($objects);
            rmdir($path);
        }
    }

    public function isDirEmpty($dir) {
        if (!is_readable($dir))
            return true;

        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                return false;
            }
        }
        return true;
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
            $result['installed'] = false;
            $result['registered'] = true;
            
            if(file_exists(DIR_SYSTEM . 'mbooth/xml/mbooth_'.$data['codename'].'.xml')){
                $result['installed'] = true;
            }

            
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

            $result = $data;
            $result['codename'] = $data['id'];
            $result['name'] = trim($data['name']);
            $result['url'] = '';
            $data['prices'] = '';
            $result['image'] = $this->model_tool_image->resize('catalog/d_shopunity/no_image.jpg', 320, 200);
            $result['processed_images'] = array(
                0 => array(
                    'width' => 160,
                    'hight' => 100,
                    'url' => $this->model_tool_image->resize('catalog/d_shopunity/no_image.jpg', 320, 200)
                    ),
                1 => array(
                    'width' => 320,
                    'hight' => 200,
                    'url' => $this->model_tool_image->resize('catalog/d_shopunity/no_image.jpg', 320, 200)
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