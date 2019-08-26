<?php
BonusTransactionModel::initArr();

class BonusTransactionModel{
	
	public    $code
			, $name
			, $color
		;
		
	const ADD_FOR_ORDER		= 'add_for_order';
	const PAY_FOR_ORDER 	= 'pay_for_order';
	
	
	static $items;

	
	function  __construct($code, $name, $icon, $info)
	{
		$this->code=$code;
		$this->name=$name;
		$this->color = $color;
	}	
	
	
	public  function initArr()
	{
		$arr[self::ADD_FOR_ORDER] 	= new self(self::ADD_FOR_ORDER,  'Начисление за заказ', '');
		$arr[self::PAY_FOR_ORDER] 	= new self(self::PAY_FOR_ORDER,  'Оплачено за заказ', '');
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
	
}

