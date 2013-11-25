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


abstract class yComponent{

	protected
		$id;

	abstract public function generate();

	public function getId(){
		return $this->id;
	}
}
?>