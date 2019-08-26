<?php
Present::initFields();
class Present
{
	const TBL = 'presents';
	
	const ADMIN_ELEMENTS_PER_PAGE = 10;
	
	
	public    $id
			, $status
			, $name
			, $dateCreated
			, $text
			
			, $triggerProducts
			, $presentProducts
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
			$u->text = $arr['text'];
			$u->dateCreated = $arr['dateCreated'];
			
			return $u;
		}
	}
	
	
	function initFields()
	{
		$f = new FieldType(FieldType::VARCHAR, 'Наименование', 'name', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::WYSIWYG, 'Описание', 'text', $isRequired=false, $isShownInList=true, $isHighlighted=false, $param1 = null);
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
		, `text` = '".strPrepare($this->text)."'
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
		# 	необходимые для подарка товары
		$this->triggerProducts = ProductRelation::getList(ProductRelationType::code(ProductRelationType::PRESENT_TRIGGER), $this->id);
		foreach($this->triggerProducts as $p)
			$p->product = ProductSimple::get($p->productId);
		
		# 	подарочные товары
		$this->presentProducts = ProductRelation::getList(ProductRelationType::code(ProductRelationType::PRESENT), $this->id);
		foreach($this->presentProducts as $p)
			$p->product = ProductSimple::get($p->productId);
		
		//vd($MODEL['item']);
	}
	
	
	
	
	
	
	
	function getListByProductId($prodId, $status, $relationType)
	{
		$sql="SELECT a.* FROM `".strPrepare(self::TBL)."` as a INNER JOIN `".ProductRelation::TBL."` as r ON  a.id=r.objectId  WHERE 1 AND r.productId=".intval($prodId)."";
		if($status)
			$sql.=" AND status='".intval($status->num)."'";
		
		$relationTypes = array(ProductRelationType::PRESENT, ProductRelationType::PRESENT_TRIGGER);
		if($relationType)
			$relationTypes = array($relationType->code);
		$sql.=" AND relationType IN ('".join("', '", $relationTypes)."')";
		
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[$next['id']] = self::init($next);

		return $ret;
	}
	
	
	
	
	
	function getProductsState()
	{
		$triggerProductsState = ProductsState::getProductsState($this->triggerProducts);
		$presentProductsState = ProductsState::getProductsState($this->presentProducts);
		
		$ret = ProductsState::code(ProductsState::OK);
		if($triggerProductsState->code != ProductsState::OK)
			$ret = $triggerProductsState;
		elseif($presentProductsState->code != ProductsState::OK)
			$ret = $presentProductsState;
		
		return $ret;
	}
	
	
	
	
	
	
	function addToCart($courseQuan)
	{
		$courseQuan = $courseQuan ? $courseQuan : 1;
	
		foreach($this->triggerProducts as $p)
		{
			$quan = (intval($p->param1) ? intval($p->param1) : 1);
			$_SESSION['cart']['products'][$p->product->id] += $quan*$courseQuan;
		}
	
	}
	
	
	
	
	function calculateTotalSums()
	{
		foreach($this->triggerProducts as $p)
		{
			$price = $p->product->price;
			//vd($price);
			if($p->product->discountObject)
				$price = ProductSimple::calculateDiscountPrice($p->product->price, $p->product->discountObject->discount);
			//vd($price);
			$quan = $p->param1 ? $p->param1 : 1;
				
			$this->sum += $price * $quan;
			/*$this->sum['sum'] += $p->product->price * $quan;
			$this->sumInCurrency['sum'] += $p->product->priceInCurrency * $quan;
				
				
			$this->sum['discountSum'] += ProductSimple::calculateDiscountPrice($p->product->price, 100-$this->discount) * $quan;
			$this->sumInCurrency['discountSum'] += ProductSimple::calculateDiscountPrice($p->product->priceInCurrency, 100-$this->discount) * $quan;*/
		}
	
		 
		/*$this->sum['totalSum'] = $this->sum['sum'] - $this->sum['discountSum'];
		$this->sumInCurrency['totalSum'] = $this->sumInCurrency['sum'] - $this->sumInCurrency['discountSum'];*/
	
	}
	
	
	
	
	
	
} 













?>