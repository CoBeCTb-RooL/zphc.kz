<?php
class Bonus
{
	const TBL = 'users_bonuses';
	
	
	
	public    $id
			, $dateCreated
			, $transactionType
			, $value
			, $valueInCurrency
			, $currency
			, $currencyCoef
			, $userId
			, $param1
			, $param2
			
		;
	
	
	
	function __construct($trType, $value, $userId, $param1, $param2, $currency)
	{
		global $_GLOBALS;
		
		$this->currency = $currency ? $currency : $_GLOBALS['currency'];
		$this->currencyCoef = $this->currency->coef;
		$this->transactionType = $trType;
		$this->value = $value;
		$this->valueInCurrency = Currency::calculatePrice($value, $currency);
		$this->userId = $userId;
		$this->param1 = $param1;
		$this->param2 = $param2;
	}	
		
		
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->id = $arr['id'];
			$u->transactionType = BonusTransactionModel::code($arr['transactionType']);
			$u->dateCreated = $arr['dateCreated'];
			$u->value = $arr['value'];
			$u->valueInCurrency = $arr['valueInCurrency'];
			
			$u->currency = Currency::code($arr['currency']);
			$u->currencyCoef = $arr['currencyCoef'];
			$u->currency->coef = $u->currencyCoef;  
			
			$u->userId = $arr['userId'];
			
			$u->param1 = $arr['param1'];
			$u->param2 = $arr['param2'];
			
			return $u;
		}
	}
	
	
	
	
	function get($id)
	{
		if($id = intval($id))
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE id=".$id." ";
			$qr=DB::query($sql);
			echo mysql_error();
			if($attrs = mysql_fetch_array($qr, MYSQL_ASSOC))
				$item = self::init($attrs);
			
			return $item;
		}
	}
	
	
	
	
	function getList($params)
	{
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		
		if($params['orderBy'])
			$sql.=" ORDER BY ".(mysql_real_escape_string($params['orderBy']) ? mysql_real_escape_string($params['orderBy']) : ' idx ')." ".($params['desc'] ? ' DESC ' : '')." ";
			
		if( ($params['from'] = intval($params['from']))>=0 && ($params['count'] = intval($params['count']))>0)
			$sql.=" LIMIT ".$params['from']*$params['count'].", ".$params['count']." ";
			
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[$next['id']] = self::init($next);
				
		return $ret;
	}
	
	
	
	function getCount($params)
	{
		$sql="SELECT COUNT(*) FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		return $next[0];
	}
	
	
	function getListInnerSql($params)
	{
		//vd($params);
		$sql = "";
		
		if($params['userId'])
			$sql .= " AND userId='".intval($params['userId'])."' ";
		
		if($params['param1'])
			$sql .= " AND param1='".intval($params['param1'])."' ";
		
		if($params['transactionType'])
			$sql .= " AND transactionType='".strPrepare($params['transactionType']->code)."' ";
		
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated=NOW(),
		".$this->innerAlterSql()."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
		
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		dateCreated=NOW(),
		".$this->innerAlterSql()."
		WHERE id=".$this->id."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="
		  `transactionType`='".strPrepare($this->transactionType->code)."'		
		, `value` = '".floatval($this->value)."'
		, valueInCurrency = '".floatval($this->valueInCurrency)."'
		, currency = '".strPrepare($this->currency->code)."'
		, currencyCoef = '".floatval($this->currencyCoef)."'
		, userId = '".intval($this->userId)."'
		
		, param1 = '".strPrepare($this->param1)."'
		, param2 = '".strPrepare($this->param2)."'
		";
		
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		/*if(!$this->customerName)
			$errors[] = new Error('Введите <b>имя</b>', 'name');*/
		
		
		return $errors;
	}
	
	
	

	
	
	
	
} 













?>