<?php
Object::initArr();

class Object{
	
	var   $code
		, $name
		;
		
	const UNSPECIFIED 			= '';
	const CATEGORY 				= 'category';
	const ADV 					= 'adv';
	const COMMENT 				= 'comment';
	const USER 					= 'user';
	const PRODUCT_VOLUME_UNIT 	= 'product_volume_unit';
	const CITY 					= 'city';
	const TOOL 					= 'tool';
	const TASK_GROUP 			= 'task_group';
	const TASK 					= 'task';
	const ADMIN 				= 'admin';
	const ADMIN_GROUP 			= 'admin_group';
	const PRODUCT 				= 'product';
	const COURSE 				= 'course';
	const ACTION 				= 'action';
	
	static $items;
	
		
	
	
	public  function initArr()
	{
		$arr[self::CATEGORY]			= new self( self::CATEGORY, 'Категория');
		$arr[self::ADV]					= new self( self::ADV, 'Объявление');
		$arr[self::COMMENT]				= new self( self::COMMENT, 'Комментарий');
		$arr[self::USER]				= new self( self::USER, 'Пользователь');
		$arr[self::PRODUCT_VOLUME_UNIT]	= new self( self::PRODUCT_VOLUME_UNIT, 'Единица объёма товара');
		$arr[self::CITY]				= new self( self::CITY, 'Город');
		$arr[self::TOOL]				= new self( self::TOOL, 'Тул');
		$arr[self::TASK_GROUP]			= new self( self::TASK_GROUP, 'Группа задач');
		$arr[self::TASK]				= new self( self::TASK, 'Задача');
		$arr[self::ADMIN]				= new self( self::ADMIN, 'Администратор');
		$arr[self::ADMIN_GROUP]			= new self( self::ADMIN_GROUP, 'Группа администраторов');
		$arr[self::PRODUCT]				= new self( self::PRODUCT, 'Товар');
		$arr[self::COURSE]				= new self( self::COURSE, 'Курс');
		$arr[self::ACTION]				= new self( self::ACTION, 'Акция');
		
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

