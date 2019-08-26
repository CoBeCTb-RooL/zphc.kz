<?php
Action::initFields();
class Action
{
	const TBL = 'actions';
	
	const ADMIN_ELEMENTS_PER_PAGE = 10;
	
	const MEDIA_SUBDIR = 'actions';
	
	public    $id
			, $status
			, $name
			, $photo
			, $anons
			, $descr
			, $dateFrom
			, $dateTill
			, $discount
			, $idx
			, $dateCreated
			
			, $isInSlides
			, $isInMainCounter
			
			, $rate
			, $votesCount
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
			$u->photo = $arr['photo'];
			$u->anons = $arr['anons'];
			$u->descr = $arr['descr'];
			$u->dateFrom = $arr['dateFrom'];
			$u->dateTill = str_replace('00:00:00', '23:5:59', $arr['dateTill']);
			$u->discount = $arr['discount'];
			$u->dateCreated = $arr['dateCreated'];
			$u->idx = $arr['idx'];
			
			$u->isInSlides = $arr['isInSlides'];
			$u->isInMainCounter = $arr['isInMainCounter'];
			
			$u->rate = $arr['rate'];
			$u->votesCount = $arr['votesCount'];
			
			
			return $u;
		}
	}
	
	
	function initFields()
	{
		$f = new FieldType(FieldType::IMG, 'Фото', 'photo', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::VARCHAR, 'Наименование', 'name', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;
	
		$f = new FieldType(FieldType::TEXT, 'Анонс', 'anons', $isRequired=true, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::DATETIME, 'Дата начала', 'dateFrom', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::DATETIME, 'Дата окончания', 'dateTill', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::WYSIWYG, 'Описание', 'descr', $isRequired=true, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::INT, 'Скидка (%)', 'discount', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;

		$f = new FieldType(FieldType::CHECKBOX, 'В слайдере на главной?', 'isInSlides', $isRequired=false, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::CHECKBOX, 'Главный счётчик на главной?', 'isInMainCounter', $isRequired=false, $isShownInList=true, $isHighlighted=false, $param1 = null);
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
			
		if($params['dateFrom'])
			$sql .= " AND dateFrom >= '".strPrepare($params['dateFrom'])."' ";
		if($params['dateTill'])
			$sql .= " AND dateTill <= '".strPrepare($params['dateTill'])."' ";
		
		if($params['isActive'])
		{
			$sql .= " AND dateFrom <='" . date('Y-m-d H:i:s') . "' AND dateTill>='" . date('Y-m-d H:i:s') . "' ";
			//$sql .= " AND '" . date('Y-m-d') . "' BETWEEN DATE(dateFrom) AND DATE(dateTill) ";
		}
			//vd(array_key_exists('isInSlides', $params));
		if(array_key_exists('isInSlides', $params))
			$sql .= " AND isInSlides='".intval($params['isInSlides'])."' ";
		if(array_key_exists('isInMainCounter', $params))
			$sql .= " AND isInMainCounter='".intval($params['isInMainCounter'])."' ";
		
		
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
		, photo = '".strPrepare($this->photo)."'
		, anons = '".strPrepare($this->anons)."'
		, descr = '".strPrepare($this->descr)."'
		, dateFrom = '".strPrepare($this->dateFrom)."'
		, dateTill = '".strPrepare($this->dateTill)."'
		, idx = ".intval($this->idx)."
		, discount = '".intval($this->discount)."'
		, rate = '".floatval($this->rate)."'
		, votesCount = '".intval($this->votesCount)."'
		, isInSlides = '".intval($this->isInSlides)."'
		, isInMainCounter = '".intval($this->isInMainCounter)."'
		";
		
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		if(!$this->name)
			$errors[] = new Error('Не указано название', 'name');
		if(!$this->descr)
			$errors[] = new Error('Не введено описание товара', 'descr');
		
		
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
	
	
	function getNextIdx($catId)
	{
		$sql = "SELECT MAX(idx) as res  FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1 ".($catId ? " AND catId=".intval($catId)." " : "")."";
		$qr = DB::query($sql);
		echo mysql_error();
	
		$next = mysql_fetch_array($qr, MYSQL_ASSOC);
		$res = $next['res'];
	
		$res = $res % 10 ? $res + (10-$res%10) : $res+10;
	
		return $res;
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
	
	
	
	
	
				
				
	function getMediaDir()
	{
		return ROOT.'/'.UPLOAD_IMAGES_REL_DIR.self::MEDIA_SUBDIR.'/';
	}
			
	
	
	
	
	
	
	function url()
	{
		global $CORE, $_CONFIG;
	
		$ret = '/actions/'.($this->urlPiece());
		return $ret;
	}
	function urlFull()
	{
		global $CORE, $_CONFIG;
		$ret = 'http://'.$_SERVER['HTTP_HOST'].'/actions/'.($this->urlPiece());
		return $ret;
	}
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
	
	
	
	
	
	
	function recountRating()
	{
		$totalRate = Review::getTotalRate(Object::code(Object::ACTION), $this->id);
		$this->rate = $totalRate['rate'] / $totalRate['count'];
		$this->votesCount = $totalRate['count'];
		
		$this->update();
	}
	
	
	
	
	
	
	
	function initRelatedProducts()
	{
		$this->relatedProducts = ProductRelation::getList(ProductRelationType::code(ProductRelationType::ACTION), $this->id);
		foreach($this->relatedProducts as $p)
			$p->product = ProductSimple::get($p->productId);
		
		//vd($MODEL['item']);
		$this->calculateTotalSums();
	}
	
	
	
	
	
	
	
	
	/*function calculateTotalSums()
	{
		foreach($this->relatedProducts as $p)
			$sum += $p->product->price * ($p->param1 ? $p->param1 : 1);
		
		$this->sum['sum'] = $sum;
		
		$discountSum = round($sum / 100 * $this->discount, 0);
		$this->sum['discountSum'] = $discountSum; 		
		$this->sum['totalSum'] = $this->sum['sum'] - $this->sum['discountSum'];  
		
	}*/
	function calculateTotalSums()
	{	
		foreach($this->relatedProducts as $p)
		{
			$quan = $p->param1 ? $p->param1 : 1;
			
			$this->sum['sum'] += $p->product->price * $quan;
			$this->sumInCurrency['sum'] += $p->product->priceInCurrency * $quan;
			
			
			$this->sum['discountSum'] += ProductSimple::calculateDiscountPrice($p->product->price, 100-$this->discount) * $quan;
			$this->sumInCurrency['discountSum'] += ProductSimple::calculateDiscountPrice($p->product->priceInCurrency, 100-$this->discount) * $quan;
		}
		
		 		
		$this->sum['totalSum'] = $this->sum['sum'] - $this->sum['discountSum'];  
		$this->sumInCurrency['totalSum'] = $this->sumInCurrency['sum'] - $this->sumInCurrency['discountSum'];
		
	}
	

	
	
	
	function getProductsState()
	{
		return ProductsState::getProductsState($this->relatedProducts);
	}
	
	
	
	
	
	
	function addToCart()
	{
		//vd($this->relatedProducts);
		foreach($this->relatedProducts as $p)
			$_SESSION['cart']['products'][$p->product->id] += (intval($p->param1) ? intval($p->param1) : 1);
		
	}
	
	
	
	
	
} 













?>