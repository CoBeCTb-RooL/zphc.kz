<?php
Course::initFields();
class Course
{
	const TBL = 'courses';
	
	const ADMIN_ELEMENTS_PER_PAGE = 10;
	
	const MEDIA_SUBDIR = 'course';
	
	public    $id
			, $status
			, $name
			, $photo
			, $anons
			, $courseDuration
			, $pktDuration
			, $goal
			, $consist
			, $descr
			, $courseTable
			, $discount
			, $excel
			, $idx
			, $dateCreated
			
			, $isInSlides
			
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
			$u->courseTable = $arr['courseTable'];
			$u->courseDuration = $arr['courseDuration'];
			$u->pktDuration = $arr['pktDuration'];
			$u->goal = $arr['goal'];
			$u->consist = $arr['consist'];
			$u->discount = $arr['discount'];
			$u->excel = $arr['excel'];
			$u->dateCreated = $arr['dateCreated'];
			$u->idx = $arr['idx'];
			$u->isInSlides = $arr['isInSlides'];
			$u->rate = $arr['rate'];
			$u->votesCount = $arr['votesCount'];
			
			
			return $u;
		}
	}
	
	
	function initFields()
	{
		//self::$fields = '123d';
		$f = new FieldType(FieldType::IMG, 'Фото', 'photo', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::VARCHAR, 'Наименование', 'name', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;
	
		/*$f = new FieldType(FieldType::TEXT, 'Анонс', 'anons', $isRequired=true, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;*/
		
		$f = new FieldType(FieldType::VARCHAR, 'Цель', 'goal', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::VARCHAR, 'Состав', 'consist', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::INT, 'Длительность курса', 'courseDuration', $isRequired=true, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::INT, 'Длительность ПКТ', 'pktDuration', $isRequired=true, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		
		
		$f = new FieldType(FieldType::WYSIWYG, 'Описание', 'descr', $isRequired=true, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::WYSIWYG, 'Таблица курса', 'courseTable', $isRequired=false, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::INT, 'Скидка (%)', 'discount', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;

		
		$f = new FieldType(FieldType::FILE, 'Эксель', 'excel', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::CHECKBOX, 'В слайдере на главной?', 'isInSlides', $isRequired=false, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		/*
		////////////////
		$f = new FieldType(FieldType::FILE, 'ФАЙЛ', 'file', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;*/
	
	
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
		//vd($params);
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
		//vd($params['ids']);
		//vd(array_key_exists('ids', $params));
		$sql = "";
		if($params['catId'])
			$sql.=" AND catId=".intval($params['catId'])." ";
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
		
		if(array_key_exists('isInSlides', $params))
			$sql .= " AND isInSlides='".intval($params['isInSlides'])."' ";
		
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated = NOW(),
		idx = 9999,
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
		idx = ".intval($this->idx).",
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
		, excel = '".strPrepare($this->excel)."'
		, descr = '".strPrepare($this->descr)."'
		, courseTable = '".strPrepare($this->courseTable)."'
		, courseDuration = '".strPrepare($this->courseDuration)."'
		, pktDuration = '".strPrepare($this->pktDuration)."'
		, goal = '".strPrepare($this->goal)."'
		, consist = '".strPrepare($this->consist)."'
		
		, discount = '".intval($this->discount)."'
		, isInSlides = '".intval($this->isInSlides)."'
		, rate = '".floatval($this->rate)."'
		, votesCount = '".intval($this->votesCount)."'
		
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
	
	
	
	
	
	
	/*function saveMediaFromFILES()
	{
		//vd(self::$fields);
		$errors = null;
		
		$fileDir = ROOT.'/'.UPLOAD_IMAGES_REL_DIR.self::MEDIA_SUBDIR.'/';
		
		foreach(self::$fields as $f)
		{
			if(! ($f->type == FieldType::IMG || $f->type == FieldType::FILE) )
				continue;
			
			
			foreach($_FILES[$f->htmlName] as $file)
			{
				$uploadedFile = new UploadedFile($file);
				$uploadedFile->newFileName = Funx::generateName();
				
				if($f->type == FieldType::FILE)
					$uploadedFile->newFileName = Funx::correctFileName($uploadedFile->name);
				
				$uploadedFile->destDir = $fileDir.Funx::getSubdirsByFile($uploadedFile->newFileName);
				
				$error = $uploadedFile->save();
				if(!$error)
				{
					$uploadedFile->resizeImageToMaxWidthAndHeight(MAX_PIC_WIDTH, MAX_PIC_HEIGHT);
					$this->{$f->htmlName} = $uploadedFile->newFileName.'.'.$uploadedFile->ext;
				}
				else
					$errors[] = $error;
			}
		}
		
		return $errors;
	}*/
	
	
				
				
	function getMediaDir()
	{
		return ROOT.'/'.UPLOAD_IMAGES_REL_DIR.self::MEDIA_SUBDIR.'/';
	}
			
	
	
	
	
	
	
	function url()
	{
		global $CORE, $_CONFIG;
		$ret = '/courses/'.($this->urlPiece());
		return $ret;
	}
	
	function urlFull()
	{
		global $CORE, $_CONFIG;
		$ret = 'http://'.$_SERVER['HTTP_HOST'].'/courses/'.($this->urlPiece());
		return $ret;
	}
	
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
	
	
	
	
	
	
	function recountRating()
	{
		$totalRate = Review::getTotalRate(Object::code(Object::COURSE), $this->id);
		$this->rate = $totalRate['rate'] / $totalRate['count'];
		$this->votesCount = $totalRate['count'];
		
		$this->update();
	}
	
	
	
	
	
	
	
	function initRelatedProducts($products)
	{
		$this->relatedProducts = ProductRelation::getList(ProductRelationType::code(ProductRelationType::COURSE), $this->id);
		foreach($this->relatedProducts as $p)
		{
			if($products[$p->productId])
				$p->product = $products[$p->productId]; 
			else
				$p->product = ProductSimple::get($p->productId);
		}
		
		//vd($MODEL['item']);
		$this->calculateTotalSums();
	}
	
	
	
	
	
	
	
	
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
	
	
	
	
	
	
	function addToCart($courseQuan)
	{
		$courseQuan = $courseQuan ? $courseQuan : 1;
		
		
		foreach($this->relatedProducts as $p)
		{
			$quan = (intval($p->param1) ? intval($p->param1) : 1);
			
			$_SESSION['cart']['products'][$p->product->id] += $quan*$courseQuan;
			
		}
	
	}
	
	
	
	
	function getProductsState()
	{
		return ProductsState::getProductsState($this->relatedProducts);
	}
	
	

	
	
	
} 













?>