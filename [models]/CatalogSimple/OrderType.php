<?php
OrderType::initArr();

class OrderType{
	
	var   $code
		, $name
		;
		
	const COMMON 	= 'common';
	const OPT	    = 'opt';

	static $items;

	
	function  __construct($code, $name, $background)
	{
		$this->code=$code;
		$this->name=$name;
		$this->background=$background;
	}	
	
	
	public  function initArr()
	{
		$arr[self::COMMON] 	= new self(self::COMMON, 'Обычный' );
		$arr[self::OPT] 	= new self(self::OPT, 'Опт');

		self::$items = $arr;
	}
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
}

