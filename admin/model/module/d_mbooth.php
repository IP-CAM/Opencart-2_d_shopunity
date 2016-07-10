<?php

class ModelModuleDmbooth extends Model {
    private $dir_root = '';

    public function __construct($registry){
        parent::__construct($registry);
        $this->dir_root = substr_replace(DIR_SYSTEM, '/', -8);
      
    }

	public function getExtensions(){
		$result = array();

		$files = glob(DIR_SYSTEM . 'mbooth/extension/*.json');

		foreach($files as $file){
			$result[] = $this->_extension(json_decode(file_get_contents($file), true));
		}

        return $result;

	}

	public function getExtension($codename){
		$result = array();

		$file = DIR_SYSTEM . 'mbooth/extension/'.$codename.'.json';

		if(file_exists($file)){
			return $this->_extension(json_decode(file_get_contents($file), true));
		}else{
			return false;
		}
	}

	public function downloadExtensionArchive($download_link){

        $filename = DIR_SYSTEM . 'mbooth/download/extension.zip';
        $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';

        $ch = curl_init();
        $fp = fopen($filename, "w");
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_URL, htmlspecialchars_decode($download_link));
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

     public function extractExtensionArchive($filename = false, $location = false) {
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

	public function downloadExtension($codename){

        $mbooth = $this->getExtension($codename);
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
            if(isset($mbooth['install'])){

            	if(isset($mbooth['install']['php'])){
            		if(file_exists($this->dir_root . $mbooth['install']['php'])){
            			$zip->addFile($this->dir_root . $mbooth['install']['php'], 'install.php');
            		}
            	}

            	if(isset($mbooth['install']['sql'])){
            		if(file_exists($this->dir_root . $mbooth['install']['sql'])){
            			$zip->addFile($this->dir_root . $mbooth['install']['sql'], 'install.sql');
            		}
            	}

            	if(isset($mbooth['install']['xml'])){
            		if(file_exists($this->dir_root . $mbooth['install']['xml'])){
            			$zip->addFile($this->dir_root . $mbooth['install']['xml'], 'install.xml');
            		}
            	}
            }

            if(isset($mbooth['readme'])){
            	if(file_exists($this->dir_root . $mbooth['readme'])){
        			$zip->addFile($this->dir_root . $mbooth['readme'], 'readme.md');
        		}
            }
         
            $zip->close();

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

		$mbooth = $this->getExtension($codename);
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

	public function backupExtension($codename){


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
		}

		return $result;

	}


}