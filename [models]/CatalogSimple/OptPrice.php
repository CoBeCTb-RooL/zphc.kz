<?php
class OptPrice{
	
	const TBL = 'catalog_simple__optprices';
	
	
	public $productId;
	public $sum;
	public $price;



	
	function __construct($productId, $sum, $price)
	{
		$this->productId = $productId;
		$this->sum = $sum;
		$this->price = $price;
	}
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
				
			$u->productId = $arr['productId'];
			$u->sum = intval($arr['sum']);
			$u->price = floatval($arr['name']);
				
				
			return $u;
		}
	}



	function getList()
    {
        $sql = "SELECT * FROM `".self::TBL."` WHERE 1";
        $qr = DB::query($sql);
        echo mysql_error();

        while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
        {
            //vd($next);
            $arr[$next['productId']][] = $next ;
//            $arr[$next['sum']] = $next['price'];
        }

        return $arr;
    }



	
	function getArrByProductId($productId, $prices=null)
	{
	    if($prices)
	        $tmp = $prices[$productId];
 	    else
        {
            $sql = "SELECT * FROM `".self::TBL."` WHERE productId=".intval($productId);
            $qr = DB::query($sql);
            echo mysql_error();

            while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
                $tmp[] = $next;
        }


		foreach ($tmp as $next)
            $arr[$next['sum']] = $next['price'];
	
		return $arr;
	}
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		productId = ".intval($this->productId).",
		sum=".intval($this->sum).",
		price=".floatval($this->price)."
		";
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
	
	
	
	function shortenDozeStr($str)
	{
		$ret = $str;
		
		//return $ret; 
		if($str)
		{
			$ret.='';
			/*$ret = str_replace('миллиграммов', 'мг', $ret);
			$ret = str_replace('миллиграмма', 'мг', $ret);
			$ret = str_replace('миллиграмму', 'мг', $ret);
			$ret = str_replace('миллиграмм', 'мг', $ret);
			
			$ret = str_replace('таблеток', 'таб', $ret);
			$ret = str_replace('ампул', 'амп', $ret);
			
			$ret = str_replace('флаконов', 'фл', $ret);
			$ret = str_replace('флакон', 'фл', $ret);
			$ret = str_replace('единиц', 'ед.', $ret);
			
			$ret = str_replace('по', '&times;', $ret);*/
			
			$ret = str_replace('миллиграммов', 'mg', $ret);
			$ret = str_replace('миллиграмма', 'mg', $ret);
			$ret = str_replace('миллиграмму', 'mg', $ret);
			$ret = str_replace('миллиграмм', 'mg', $ret);
				
			$ret = str_replace('таблеток', 'tab', $ret);
			$ret = str_replace('ампул', 'amps', $ret);
				
			$ret = str_replace('флаконов', 'vials', $ret);
			$ret = str_replace('флакон', 'vials', $ret);
			$ret = str_replace('единиц', 'IU', $ret);
				
			$ret = str_replace('по', '&times;', $ret);
		}
		
		return $ret;
	}



	function steps()
    {
        $ret = [];
        foreach (ProductSimple::$optPrices as $step=>$bool)
            if($bool)
                $ret[] = $step;
        return $ret;
    }
	
}
?>