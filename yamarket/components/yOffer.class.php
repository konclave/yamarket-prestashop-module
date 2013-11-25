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


class yOffer extends yComponent{
	protected
		$generalProperties = array(
			'url'=>'', 'buyurl'=>'', 'price'=>'', 'wprice'=>'',
			'currencyId'=>'', 'xCategory'=>'', 'categoryId'=>array(),
			'picture'=>'', 'delivery'=>'','name'=>'', 'deliveryIncluded'=>'',
			'orderingTime'=>'', 'aliases'=>'', 'additional'=>array(),
			'description'=>'', 'sales_notes'=>'', 'promo'=>'',
			'manufacturer_warranty'=>'', 'county_of_origin'=>'',
			'downloadable'=>''
		),
		$generalAttributes = array(
			'id'=>'', 'type'=>'vendor.model', 'available'=>'true'
		),
		$privateProperties = array(
			'typePrefix'=>'', 'vendor'=>'', 'vendorCode'=>'',
			'model'=>'', 'provider'=>'', 'tarifplan'=>''
		);



	public function __construct($id, $name, $price){
		$this->id = $id;
		$this->name = $name;
		$this->price = $price;
	}


	public function __set($name, $value){
		if(isset($this->generalProperties[$name])){
			$this->generalProperties[$name] = $value;
		}else if(isset($this->generalAttributes[$name])){
			$this->generalAttributes[$name] = $value;
		}else if(isset($this->privateProperties[$name])){
			$this->privateProperties[$name] = $value;
		}else{
			return false;
		}
	}


	public function __get($name){
		if(isset($this->generalProperties[$name])){
			return $this->generalProperties[$name];
		}else if(isset($this->generalAttributes[$name])){
			return $this->generalAttributes[$name];
		}else if(isset($this->privateProperties[$name])){
			return $this->privateProperties[$name];
		}else{
			return false;
		}
	}



	public function generate(){
		$tmp = '<offer';
		//generate attributes
		foreach($this->generalAttributes as $key=>$value){
			if(strlen(trim($value))>0){
				$tmp.=" $key='".YMarket::specialChars(trim($value))."'";
			}
		}
		$tmp.=">\r\n";

		//generate general properties
		$tmp.=$this->getProp($this->generalProperties);

		$tmp.=$this->getProp($this->privateProperties);



		$tmp.='</offer>';

		return $tmp;
	}


	protected function getProp($data){
		$tmp='';
		if(is_array($data)){
			foreach($data as $key=>$value){
				if(is_array($value)){
					$tmp.=$this->getProp($value);
				}else if(strlen(trim($value))>0){
					$tmp.="<$key>".YMarket::specialChars(trim($value))."</$key>\r\n";
				}
			}
		}

		return $tmp;

	}
}
?>