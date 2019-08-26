<?php
OrderStatus::initArr();

class OrderStatus{
	
	var   $code
		, $name
		, $icon
		, $background
		
		;
		
	const NEW_ORDER 	= 'new_order';
	const ASSEMBLING	= 'assembling';
	const DELIVERING 	= 'delivering';
	const CANCELLED 	= 'cancelled';
	const CLOSED 		= 'closed';
	const DELETED 		= 'deleted';
	
	
	static $items;

	
	function  __construct($code, $name, $icon, $background)
	{
		$this->code=$code;
		$this->name=$name;
		$this->icon=$icon;
		$this->background=$background;
	}	
	
	
	public  function initArr()
	{
		$arr[self::NEW_ORDER] 	= new self(self::NEW_ORDER, 	'Новый', 				'', '#D2FFBD');
		$arr[self::ASSEMBLING] 	= new self(self::ASSEMBLING, 	'В процессе сборки', 	'', '#fff');
		$arr[self::DELIVERING] 	= new self(self::DELIVERING, 	'В доставке', 			'', '#fff');
		$arr[self::CANCELLED] 	= new self(self::CANCELLED, 	'Отменён', 				'', '#fff');
		$arr[self::CLOSED] 		= new self(self::CLOSED, 		'Успешно закрыт', 		'', '#FBFFB3');
		$arr[self::DELETED] 	= new self(self::DELETED, 		'Удалён',		 		'', '#fff');
		
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
}

