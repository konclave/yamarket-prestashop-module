<?php
/**
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public
 * License (version 2 or (at your option) any later version) as published by the Free Software Foundation.
 *
 * @author xPoint <xPoint@meta.ua>
 * @version 0.1
 * @package
 * @link http://ymarket.sourceforge.net/
 */

include_once 'yComponent.class.php';
include_once 'components/yOffer.class.php';
include_once 'components/yCategory.class.php';
include_once 'components/yCurrency.class.php';


class YMarket {
	protected
		$shop = array(),
		$currencies = array(),
//		$categories = array(),
//		$offers = array(),
//fix
		$categories_data = '',
		$offers_data = '',
//fix
		$doctype = "<?xml version='1.0' encoding='windows-1251'?>
<!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>\r\n",
		$rootElement = '',
		$shopElement = '',
		$name='',
		$company='',
		$url = '',
		$local_delivery_cost = false,
		$date = null;

	private static $specialChars = array('&nbsp;'=>' ','&'=>'&amp;','"'=>'&quot;','>'=>'&gt;',
		'<'=>'&lt;','`'=>'&apos;');



		public function __construct($name='', $company='', $url='', $ldc = false){
			$this->name = $name;
			$this->company = $company;
			$this->url = $url;
			$this->local_delivery_cost=$ldc;
			$this->date = time();
			$this->rootElement = "<yml_catalog date='%04d-%02d-%02d %02d:%02d'>\r\n%s\r\n</yml_catalog>";
			$this->shopElement =
			"<shop>\r\n<name>%s</name>\r\n<company>%s</company>\r\n<url>%s</url>\r\n%s\r\n</shop>";



		}



		public function generate($output = false, $gz = false){

			$currencies = "<currencies>\r\n";
			foreach($this->currencies as $currency){
				$currencies.=$currency->generate();
			}
			$currencies .= "</currencies>\r\n";


			$categories = "<categories>\r\n";
//			foreach($this->categories as $category){
//				$categories.=$category->generate();
//			}
//fix
      $categories.=$this->categories_data;
//fix
			$categories.="</categories>\r\n";

			$offers = "<offers>\r\n";
//			foreach($this->offers as $offer){
//				$offers.=$offer->generate();
//			}
//fix
      $offers.=$this->offers_data;
//fix
			$offers.="</offers>\r\n";
      if($this->local_delivery_cost)
        $ldc='<local_delivery_cost>'.$this->local_delivery_cost.'</local_delivery_cost>';
      else $ldc='';
			$this->shopElement = sprintf($this->shopElement,
			self::specialChars($this->name), self::specialChars($this->company),
			self::specialChars($this->url),
			"$currencies \r\n$categories \r\n$ldc \r\n $offers\r\n");

			$this->rootElement = sprintf($this->rootElement,
			date('Y', $this->date), date("m", $this->date),
			date('d', $this->date), date('H', $this->date),
			date('i', $this->date), $this->shopElement);

			$data = $this->doctype.$this->rootElement;

			if($gz==true&&function_exists('gzencode')){
				$data = gzencode($data, 9);
			}

			if($output == true){
				if($gz==true&&function_exists('gzencode'))
				$header = array("Content-type:application/x-gzip","Content-Disposition: attachment; filename=yml.xml.gz");
				else
				$header = 'Content-type:application/xml';
				if(is_array($header))
				{
					foreach($header as $value){
						header($value);
					}
				}else{
				header($header);
				}
				echo $data;
			}else{
			return $data;
			}

		}

		public function issetComponent(yComponent $comp){
			switch(get_class($comp)){
				case 'yCurrency':
					return isset($this->currencies[$comp->getId()]);
				case 'yCategory':
					return isset($this->categories[$comp->getId()]);
				case 'yOffer':
					return isset($this->offers[$comp->getId()]);
				default:
					return false;
			}
		}


		public function add(yComponent $comp, $replace = false){
			if(!$this->issetComponent($comp) || $replace === true){
			switch(get_class($comp)){
				case 'yCurrency':
					$this->currencies[$comp->getId()] = $comp;
					break;
				case 'yCategory':
//					$this->categories[$comp->getId()] = $comp;
//fix
					$this->categories_data.=$comp->generate();
					break;
				case 'yOffer':
				case 'yBookOffer':
				case 'yAudioBookOffer':
				case 'yMusicOffer':
				case 'yVideoOffer':
				case 'yTourOffer':
				case 'yTicketOffer':
					//$this->offers[$comp->getId()] = $comp;
//fix
					$this->offers_data.=$comp->generate();
					break;
				default:
					break;
			}

			return true;

			}else{
				return false;
			}
		}


		public function delete(yComponent $comp){
			if($this->issetComponent($comp)){
				switch(get_class($comp)){
					case 'yCurrency':
						unset($this->currencies[$comp->getId()]);
						break;
					case 'yCategory':
						unset($this->categories[$comp->getId()]);
						break;
					case 'yOffer':
						unset($this->offers[$comp->getId()]);
						break;
					default:
						break;
				}

				return true;
			}else{
				return false;
			}
		}


		public function replace(yComponent $comp){
			if($this->issetComponent($comp)){
				return $this->add($comp, true);
			}else{
				return false;
			}
		}



		public static function specialChars($str){
      $str=preg_replace('!<[^>]*?>!', ' ', $str);
			foreach(self::$specialChars as $k=>$v){
				$symbols [] =$k;
				$chars[] = $v;
			}
			return str_replace($symbols, $chars, iconv("UTF-8", "CP1251",$str));
		}
}
?>