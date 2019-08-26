<?php
DeliveryType::initArr();

class DeliveryType{
	
	public    $code
			, $name
			, $icon
		;
		
	const PICKUP 			= 'pickup';
	const COURIER 			= 'courier';
	const MAIL 				= 'mail';
	
	
	static $items;

	
	function  __construct($code, $name, $icon, $info)
	{
		$this->code=$code;
		$this->name=$name;
		$this->icon = $icon;
		$this->info = $info;
	}	
	
	
	public  function initArr()
	{
		$arr[self::PICKUP] 	= new self(self::PICKUP, 	'Самовывоз из г. Алматы', '', 'Необходимо согласовать время');
		$arr[self::COURIER] 	= new self(self::COURIER, 	'Доставка курьером по г. Алматы', '/img/paymentTypes/cash_rur.gif', 'с 10:00 до 18:00 на след. день после заказа');
		$arr[self::MAIL] 		= new self(self::MAIL, 	'Доставка по почте', '/img/paymentTypes/yandexmoney.gif', 'От 2 до 7 дней, EMS почта');
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
	
}

