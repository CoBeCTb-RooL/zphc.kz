<?php
class OptPrice2{
	
	const TBL = 'catalog_simple__optPrices2';
	
	static $optPrices = array(
			250 	=>	true,
			500 	=>	true,
			1000 	=>	true,
			2000 	=>	true,
			3500 	=>	false,
			5000 	=>	true,
			7500 	=>	false,
			10000 	=>	true,
			20000 	=>	false,
			50000 	=>	false,
	);
	
	public $productId;
	public $status;
	
	
	
	function __construct()
	{

	}
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
				
			$u->productId = $arr['productId'];
			$u->status = Status::num($arr['status']);
			
			foreach(self::$optPrices as $sum=>$active)
			{
				$u->{'price_'.$sum} = $arr['price_'.$sum];
			}
				
			return $u;
		}
	}
	
	
	function getByProductId($id, $status)
	{
		if($id = intval($id))
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE productId=".$id." ".($status ? " AND status=".intval($status->num)." " : "")."";
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
				$item = self::init($attrs);
			
			return $item;
		}
	}
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		productId = ".intval($this->productId).",
		status = ".intval($this->status->num)."
		
		";
		
		foreach(self::$optPrices as $sum=>$visible)
		{
			$sq.=", price_".intval($sum)."=".floatval($this->{'price_'.$sum})."";
		}
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
	function deleteByProductId($productId)
	{
		$sql = "DELETE FROM  `".self::TBL."` WHERE productId=".intval($productId)."";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
	}
	
}
?>