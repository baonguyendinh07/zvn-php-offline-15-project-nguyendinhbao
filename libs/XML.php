<?php
class XML{
	
	public static function getContentXML($fileName, $option = null){
		if($option == null){
			$file = PUBLIC_PATH . 'files' . DS . 'xml' . DS . $fileName;
			$xml = simplexml_load_file($file);
			return  $xml;
		}
	}
	
	public static function createXML($arrData, $fileName, $option = null){
		if($option == null){
			$strXML  = '<?xml version="1.0" encoding="UTF-8"?>';
			$strXML .= '<book_store>';
			foreach($arrData as $key => $value){
				$strXML .= '<category>
								<id>'.$value['id'].'</id>
								<name>'.$value['name'].'</name>
								<picture>'.$value['picture'].'</picture>
							</category>';
			}
			$strXML .= '</book_store>';
			
			$file = PUBLIC_PATH . 'files' . DS . 'xml' . DS . $fileName;
			file_put_contents($file, $strXML);
		}
		
	}
}