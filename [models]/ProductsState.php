<?php
ProductsState::initArr();

class ProductsState{
	
	static $items;
	
	public $code;
	public $name;
		
	const OK 					= 'ok';
	const NO_STOCK 				= 'no_stock';
	const INSUFFICIENT_STOCK 	= 'insufficient_stock';
	
	
	
	public  function initArr()
	{
		$arr[self::OK]					= new self( self::OK,		'Ок');
		$arr[self::NO_STOCK]			= new self( self::NO_STOCK, 'Некоторых товаров нет в наличии');
		$arr[self::INSUFFICIENT_STOCK]	= new self( self::INSUFFICIENT_STOCK, 'Некоторых товаров нет в нужном количестве');
		
		
		self::$items = $arr;
	}
	
	
	
	function  __construct($code, $name, $namePlural, $icon)
	{
		$this->code=$code;
		$this->name=$name;
	}
	
	
	
	
	function code($code)
	{
		return self::$items[$code];
	}
	
	
	
	
	function getProductsState($relatedProducts)
	{
		global $CART; 
		
		$state = ProductsState::code(ProductsState::OK);
		foreach($relatedProducts as $p)
		{
			$requiredQuan = $p->param1 ? intval($p->param1) : 1;
			
			//vd($p->product->stock);
			if($p->relationType->code == ProductRelationType::COURSE)
			{	
				$p->product->stock -= $requiredQuan * $CART->courses[$p->objectId];
			}
			//vd($p->product->stock);
			//vd($requiredQuan);
			//vd($p->product->stock);
			if(!$p->product->stock )
				$state = ProductsState::code(ProductsState::NO_STOCK);
			elseif($p->product->stock < $requiredQuan)
				$state = ProductsState::code(ProductsState::INSUFFICIENT_STOCK);
		}
		//vd($relatedProducts);
		return $state;
	}
	
	
}

