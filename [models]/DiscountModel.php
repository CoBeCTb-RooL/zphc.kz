<?php
Discount::initFields();
class Discount
{
	const TBL = 'discounts';
	
	const ADMIN_ELEMENTS_PER_PAGE = 10;
	
	
	public    $id
			, $status
			, $name
			, $discount
			, $dateCreated
		;
	
		
	static $fields;
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->id = $arr['id'];
			$u->status = Status::num($arr['status']);
			$u->name = $arr['name'];
			$u->discount = $arr['discount'];
			$u->dateCreated = $arr['dateCreated'];
			
			return $u;
		}
	}
	
	
	function initFields()
	{
		$f = new FieldType(FieldType::VARCHAR, 'Наименование', 'name', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::INT, 'Скидка (%)', 'discount', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;

		self::$fields = $arr;
	}
	
	
	function get($id, $status)
	{
		if($id = intval($id))
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE id=".$id." ".($status ? " AND status=".intval($status->num)." " : "")."";
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
			$sql.=" LIMIT ".$params['from'].", ".$params['count']." ";
			
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
		/*if($params['catId'])
			$sql.=" AND catId=".intval($params['catId'])." ";*/
		if($params['status'])
			$sql.=" AND (status=".intval($params['status']->num).") ";
			
		if(count($params['statuses']))
		{
			$sql .= " AND status IN(-1";
			foreach($params['statuses'] as $s)
				$sql .= ", '".$s->num."'";
				$sql.=") ";
		}
		if(count($params['statusesNotIn']))
		{
			$sql .= " AND status NOT IN(-1";
			foreach($params['statusesNotIn'] as $s)
				$sql .= ", '".$s->num."'";
				$sql.=") ";
		}
		
		if($params['id'])
			$sql .= " AND id=".intval($params['id'])." ";
		if(array_key_exists('ids', $params) )
		{
			$sql .= " AND id IN(-1";
			foreach($params['ids'] as $s)
				$sql .= ", '".intval($s)."'";
			$sql.=") ";
		}
		if(array_key_exists('idsNotIn', $params) )
		{
			$sql .= " AND id NOT IN(-1";
			foreach($params['idsNotIn'] as $s)
				$sql .= ", '".intval($s)."'";
				$sql.=") ";
		}
		
		//vd($sql);
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated = NOW(),
		".$this->innerAlterSql()."
		";
		vd($sql);
		DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
		//vd($sql);
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
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
		  status='".intval($this->status->num)."'		
		, name = '".strPrepare($this->name)."'
		, discount = '".intval($this->discount)."'
		";
		
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		if(!$this->name)
			$errors[] = new Error('Не указано название', 'name');
		/*if(!$this->descr)
			$errors[] = new Error('Не введено описание товара', 'descr');*/
		
		
		return $errors;
	}
	
	
	
	
	function setStatus($status)
	{
		if($status)
		{
			$sql = "UPDATE `".self::TBL."` SET status='".intval($status->num)."' WHERE id=".$this->id;
			//vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	
	function setValuesFromRequestByFields()
	{
		$errors = null;
		
		foreach(self::$fields as $f)
		{
			if(property_exists(__CLASS__, $f->htmlName))
			{
				if($res = $f->validateValueFromRequest())
					$errors[] = $res;
				else 
				{
					if(!($f->type == FieldType::IMG || $f->type == FieldType::FILE))
						$this->{$f->htmlName} = $f->getValueFromRequest();
				}
			}
			else 
				$errors[] = new Error('Ошибка! У класса отсутствует свойство "'.$f->htmlName.'". Обратитесь к разработчику.');
		}
			
		//vd($this);
		return $errors;
	}
	
	
	
	
	
	
	
	function initRelatedProducts()
	{
		$this->relatedProducts = ProductRelation::getList(ProductRelationType::code(ProductRelationType::DISCOUNT), $this->id);
		foreach($this->relatedProducts as $p)
			$p->product = ProductSimple::get($p->productId);
		
		//vd($MODEL['item']);
	}
	
	
	
	
	
	
	
	
	
} 













?>