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


class yCurrency extends yComponent{

	private
		$rate,
		$plus;

	private static $possibleRates = array(
		'NBU', 'CBRF', 'CB'
	);


	public function __construct($id, $rate='NBU', $plus=0){
		$this->setId($id);

		$this->setRate($rate);

		$this->setPlus($plus);

	}


	public function setId($id){
		$this->id = strval($id);
	}

	public function setRate($rate){
	if((is_float($rate)&&floatval($rate)>0)||
		in_array($rate, self::$possibleRates)){
			$this->rate = $rate;
		}else{
			throw new Exception('Rate must be integer or type('.
			implode(',', self::$possibleRates).')');
		}
	}


	public function setPlus($plus){
		$this->plus = floatval($plus);
	}

	public function getRate(){
		return $this->rate;
	}

	public function getPlus(){
		return $this->plus;
	}


	public function generate(){
		if(!isset($this->id)){
			throw new Exception('Cannot find currency id');
		}

		if(!isset($this->rate)){
			throw new Exception('Cannot find currency rate');
		}

		$tmp = "<currency id='{$this->id}' rate='{$this->rate}' ".
		(isset($this->plus)&&floatval($this->plus)>0?"plus='{$this->plus}'":'').
		" />\r\n";

		return $tmp;
	}

}
?>