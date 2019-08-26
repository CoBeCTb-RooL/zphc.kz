<?php
ProductRelationType::initArr();

class ProductRelationType{
	
	public    $code
			, $name
		;
		
	const COURSE 		= 'course';
	const ACTION 		= 'action';
	const DISCOUNT 		= 'discount';
	const PRESENT_TRIGGER 		= 'present_trigger';
	const PRESENT 		= 'present';
	
	
	static $items;

	
	function  __construct($code, $name)
	{
		$this->code=$code;
		$this->name=$name;
	}	
	
	
	public  function initArr()
	{
		$arr[self::COURSE] 			= new self(self::COURSE, 	'Курс');
		$arr[self::ACTION] 			= new self(self::ACTION, 	'Акция');
		$arr[self::DISCOUNT] 		= new self(self::DISCOUNT, 	'Скидка');
		
		$arr[self::PRESENT_TRIGGER] 		= new self(self::PRESENT_TRIGGER, 	'Нужный для подарка товар');
		$arr[self::PRESENT] 				= new self(self::PRESENT, 			'Подарок');
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
	
}

