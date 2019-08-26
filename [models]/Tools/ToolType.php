<?php
ToolType::initArr();

class ToolType{
	
	static $items;
	
	public $code;
	public $name;
		
	const ADV_COUNT_RECACHE = 'adv_count_recache';
	const SET_EXPIRED 		= 'set_expired';
	const GET_CURRENCY 		= 'get_currency';
	
	
	
	public  function initArr()
	{
		$arr[self::ADV_COUNT_RECACHE] 	= new self( self::ADV_COUNT_RECACHE, 	'Пересчёт количества объявлений');
		$arr[self::SET_EXPIRED] 		= new self( self::SET_EXPIRED, 			'Блок объяв. с истёкшим сроком');
		$arr[self::GET_CURRENCY] 		= new self( self::GET_CURRENCY, 		'Получение курсов валют');
		
		
		self::$items = $arr;
	}
	
	
	
	function  __construct($code, $name)
	{
		$this->code=$code;
		$this->name=$name;
	}
	
	
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
}

