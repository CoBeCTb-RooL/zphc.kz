<?php
PaymentType::initArr();

class PaymentType{
	
	public    $code
			, $name
			, $icon
		;
		
	const CASH 				= 'cash';
	const YANDEX_MONEY 		= 'yandex_money';
	const QIWI 				= 'qiwi';
	const WEB_MONEY 		= 'web_money';
	const VISA 				= 'visa';
	
	
	static $items;

	
	function  __construct($code, $name, $icon)
	{
		$this->code=$code;
		$this->name=$name;
		$this->icon = $icon;
	}	
	
	
	public  function initArr()
	{
		$arr[self::CASH] 			= new self(self::CASH, 	'Наличными курьеру', '/img/paymentTypes/cash_rur.gif');
		$arr[self::YANDEX_MONEY] 	= new self(self::YANDEX_MONEY, 	'Яндекс.Деньги', '/img/paymentTypes/yandexmoney.gif');
		$arr[self::QIWI] 			= new self(self::QIWI, 	'Qiwi', '/img/paymentTypes/qiwi.gif');
		$arr[self::WEB_MONEY] 		= new self(self::WEB_MONEY, 	'Web-money', '/img/paymentTypes/webmoney.gif');
		$arr[self::VISA] 			= new self(self::VISA, 	'VISA / Master Card', '/img/paymentTypes/visa.gif');
		
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
	
}

