<?php
Currency::initArr();

class Currency{
	
	public    $code
			, $sign
			, $coef
		;
			
	const USD 	= 'USD';
	const KZT 	= 'KZT';
	const RUR 	= 'RUR';
	
	
	const CURRENCY_SITE = 'http://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22USDKZT,USDRUB%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=';
	const CURRENCY_SITE_2 = 'http://openexchangerates.org/api/latest.json?app_id=5761b35c375f4294a48d8251ddb4a1e5';

	
	static $items;

	
	function  __construct($code, $sign)
	{
		$this->code = $code;
		$this->sign = $sign;
	}	
	
	
	public  function initArr()
	{
		/*$arr[self::USD] 	= new self(self::USD,  '$');
		$arr[self::KZT] 	= new self(self::KZT,  'тг');
		$arr[self::RUR] 	= new self(self::RUR,  'руб');*/
		
		/*$arr[self::USD] 	= new self(self::USD,  '$');
		$arr[self::KZT] 	= new self(self::KZT,  '&#8376;');
		$arr[self::RUR] 	= new self(self::RUR,  '&#8381;');
		*/
		
		$arr[self::USD] 	= new self(self::USD,  '$');
//        $arr[self::KZT] 	= new self(self::KZT,  '<span class="arial-narrow-tenge" style="font-weight: normal; ">т</span>');
//        $arr[self::RUR] 	= new self(self::RUR,  '<span class="rouble">o</span>');
        $arr[self::KZT] 	= new self(self::KZT,  '&#8376;');
        $arr[self::RUR] 	= new self(self::RUR,  '&#8381;');
		
		self::$items = $arr;
	}
	
	
	function code($code)
	{
		foreach(self::$items as $key=>$val)
			if($val->code == $code)
				return $val;
	}
	
	
	
	
	function getPrice($bucksPrice, $cur)
	{
		//vd($cur);
		$ret = round($bucksPrice * $cur->coef, 1);
		
		$ret = number_format($ret, 0, '', ' ');
	
		return '!!!!'.$ret.'!!!!';
	}
	
	
	
	function calculatePrice($bucksPrice, $cur)
	{
		global $_GLOBALS;
		$cur = $cur ? $cur : $_GLOBALS['currency'];
		//vd($cur);
		$ret = self::calculateByCoef($bucksPrice, $cur->coef);
	
		return $ret;
	}
	
	function calculateByCoef($bucksPrice, $coef)
	{
		return $bucksPrice * $coef;
	}
	
	
	
	function formatPrice($price, $cur, $isWithoutCurrencySign)
	{
		//vd($cur);
		global $_GLOBALS;
		//echo "!";
		$cur = $cur ? $cur : $_GLOBALS['currency'];
		$priceStr = number_format($price, 2, ',', ' ');
		//vd($cur);
		$tmp = explode(',', $priceStr);
		//vd($priceStr);
		# 	урезание ненужных нолей
		$afterComma = ($tmp[1]);
		if(!$afterComma{1})
			$afterComma = substr($afterComma, 0, 1);
		if(!$afterComma)
			$afterComma = '';
		
		$ret = $tmp[0].($afterComma ? ','.$afterComma : '').''.(!$isWithoutCurrencySign ? ' '.$cur->sign : '');
		
		
		return $ret;
	}
	
	
	function getPriceStr($price, $cur)
	{
		
		return self::formatPrice(self::calculatePrice($price, $cur), $cur);
	}
	
	
	
	
	
	function drawAllCurrenciesPrice($price)
	{
		global $_GLOBALS;
		
		ob_start();
		?>
		<span class="currencies <?=$_GLOBALS['currency']->code?>">
		<?
		foreach(Currency::$items as $cur)
		{
			$p = Currency::getPriceStr($price, $cur);
			?>
			<span class="price-currency price-currency-<?=$cur->code?> <?=$cur->code==$_GLOBALS['currency']->code ? ' active ' : '' ?>"><?=$p?></span>
		<?php 	
		}?>
		</span>
		<?php 
		$ret = ob_get_clean();
		
		return $ret;
	}
	
	
	
	
	
}

