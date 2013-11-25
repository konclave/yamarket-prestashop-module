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
class yCategory extends yComponent{

	private
		$parentId,
		$name;


	public function __construct($id, $name, $parentId = null){
		$this->setId($id);
		$this->setName($name);
		$this->setParent($parentId);
	}


	public function setId($id){
		$id = intval($id);
		if($id>0){
			$this->id = $id;
			return true;
		}else{
			return false;
		}
	}

	public function setName($name){
		if(strlen(trim($name))>0){
			$this->name = YMarket::specialChars($name);
			return true;
		}else{
			return false;
		}
	}

	public function setParent($parentId){
		$this->parentId = $parentId;
	}


	public function getParent(){
		return $this->parentId;
	}

	public function getName(){
		return $this->name;
	}


	public function generate(){
		if(!isset($this->id)){
			throw new Exception('Cannot find category id');
		}

		$tmp = "<category id='{$this->id}' ";
		if($this->parentId!==null&&intval($this->parentId)>0){
			$tmp.="parentId='{$this->parentId}' ";
		}
		$tmp.=">{$this->name}</category>";

		return $tmp;
	}
}
?>