<?php 
class Settings{
	
	const TBL = 'slonne__settings';
	
	var $attrs;
		
	
	
	function get()
	{
		$sql="SELECT * FROM `".self::TBL."` LIMIT 1";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		if(mysql_num_rows($qr))
		{
			$next = mysql_fetch_array($qr, MYSQL_ASSOC);
			//vd($next);
			return $next;
		}
	}
	
	
	
	function save($arr)
	{
		if($arr)
		{
			$sql = "
			UPDATE `".self::TBL."` 
			SET 
			
			  `title_postfix` = '".strPrepare($arr['title_postfix'])."'
			, `title_separator` ='".mysql_real_escape_string($arr['title_separator'])."'
			, `description` ='".strPrepare($arr['description'])."'
			, `keywords` ='".strPrepare($arr['keywords'])."'
					
			, `".strPrepare(Currency::KZT)."` ='".round(floatval($arr[Currency::KZT]), 2)."'
			, `".strPrepare(Currency::RUR)."` ='".round(floatval($arr[Currency::RUR]), 2)."'
					
			, `exchange_increment_".Currency::KZT."` ='".round(floatval($arr['exchange_increment_'.Currency::KZT]), 2)."'
			, `exchange_increment_".Currency::RUR."` ='".round(floatval($arr['exchange_increment_'.Currency::RUR]), 2)."'
			
			, `yandex_money` ='".strPrepare($arr['yandex_money'])."'
			, `qiwi` ='".strPrepare($arr['qiwi'])."'
			, `web_money` ='".strPrepare($arr['web_money'])."'
			, `visa` ='".strPrepare($arr['visa'])."'
			
			, `delivery_cost` ='".strPrepare($arr['delivery_cost'])."'
			, `order_sum_for_free_delivery` ='".strPrepare($arr['order_sum_for_free_delivery'])."'
			, `refererPrize` ='".strPrepare($arr['refererPrize'])."'	
					
			, `twitter` ='".strPrepare($arr['twitter'])."'	
			, `facebook` ='".strPrepare($arr['facebook'])."'	
			, `vk` ='".strPrepare($arr['vk'])."'	
			, `instagram` ='".strPrepare($arr['instagram'])."'
			, `youtube` ='".strPrepare($arr['youtube'])."'
					
			, `contactPhone` ='".strPrepare($arr['contactPhone'])."'
			, `contactEmail` ='".strPrepare($arr['contactEmail'])."'
			, `contactWhatsApp` ='".strPrepare($arr['contactWhatsApp'])."'
					
					
			";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	function saveCurrency($arr)
	{
		$sql = "
			UPDATE `".self::TBL."`
			SET
		
			  `".strPrepare(Currency::KZT)."` ='".floatval($arr[Currency::KZT])."'
			, `".strPrepare(Currency::RUR)."` ='".floatval($arr[Currency::RUR])."'
		
		
			";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
}
?>