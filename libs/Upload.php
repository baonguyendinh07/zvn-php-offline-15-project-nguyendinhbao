<?php
class Upload{
    public static function removeFile($folder, $fileName){
		$path = FILES_PATH . $folder . DS . $fileName;
		unlink($path);
	}

	public static function uploadFile($fileObj, $folderUpload, $options = null){
		if($options == null){
			if($fileObj['tmp_name'] != null){
				$uploadDir		= FILES_PATH . $folderUpload . DS;
				$fileName		= Helper::randomString(8) . '.' . pathinfo($fileObj['name'], PATHINFO_EXTENSION);
				if(move_uploaded_file($fileObj['tmp_name'], $uploadDir . $fileName)) return $fileName;
			}	
		}
	}
}