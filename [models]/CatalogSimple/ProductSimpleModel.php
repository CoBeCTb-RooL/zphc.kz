<?php
ProductSimple::initFields();
class ProductSimple
{
	const TBL = 'catalog_simple__items';
	
	const ADMIN_ELEMENTS_PER_PAGE = 10;
	
	const MEDIA_SUBDIR = 'catalogSimple';
	
	static $optPrices = array(
			250 	=>	true, 
			500 	=>	true,
			1000 	=>	true,
			2000 	=>	true,
			3500 	=>	true,
			5000 	=>	true,
			7500 	=>	true,
			10000 	=>	true,
			20000 	=>	false,
			50000 	=>	false,
	);
	
	public    $id
			, $catId
			, $status
			, $name
			, $photo
			, $anons
			, $descr
			, $inPackage
			, $videoHTML
			, $isNew
			
			, $price
			, $priceInCurrency
			, $priceStr
			
			, $discountPrice
			, $discountPriceInCurrency
			, $discountPriceStr
			
			, $stock
			, $idx
			, $idxOpt
			, $dateCreated
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
			$u->catId = $arr['catId'];
			$u->name = $arr['name'];
			$u->photo = $arr['photo'];
			$u->anons = $arr['anons'];
			$u->descr = $arr['descr'];
			$u->inPackage = $arr['inPackage'];
			$u->videoHTML = $arr['videoHTML'];
			$u->isNew = $arr['isNew'];
			$u->price = $arr['price'];
			$u->stock = $arr['stock'];
			$u->dateCreated = $arr['dateCreated'];
			$u->idx = $arr['idx'];
			$u->idxOpt = $arr['idxOpt'];
			$u->rate = $arr['rate'];
			$u->votesCount = $arr['votesCount'];
			
			$u->priceInCurrency = Currency::calculatePrice($u->price);
			$u->priceStr = Currency::formatPrice($u->priceInCurrency);
			
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
	
		$f = new FieldType(FieldType::TEXT, 'Анонс', 'anons', $isRequired=true, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::WYSIWYG, 'Описание', 'descr', $isRequired=true, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::VARCHAR, 'В упаковке', 'inPackage', $isRequired=false, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::VIDEO_HTML, 'Код видео', 'videoHTML', $isRequired=false, $isShownInList=false, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::CHECKBOX, 'Новинка?', 'isNew', $isRequired=false, $isShownInList=true, $isHighlighted=false, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::FLOAT, 'Цена ($)', 'price', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::INT, 'Наличие', 'stock', $isRequired=false, $isShownInList=true, $isHighlighted=false, $param1 = null);
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
		
		if(count($params['ids']))
		{
			$sql .= " AND id IN(-1";
			foreach($params['ids'] as $s)
				$sql .= ", ".intval($s)."";
			$sql.=") ";
		}
		
		if(array_key_exists('isNew', $params))
			$sql .= " AND isNew='".intval($params['isNew'])."' ";
		
		if($params['inStock'])
			$sql.=" AND stock>0 ";
		
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated = NOW(),
		idx = ".intval($this->idx ? $this->idx : 9999).",
		idxOpt = ".intval($this->idxOpt ? $this->idxOpt : 9999).",
		".$this->innerAlterSql()."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
		//vd($sql);
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		idx='".intval($this->idx)."', 
		idxOpt = ".intval($this->idxOpt ? $this->idxOpt : 9999).",
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
		, catId = '".intval($this->catId)."'
		, name = '".strPrepare($this->name)."'
		, photo = '".strPrepare($this->photo)."'
		, anons = '".strPrepare($this->anons)."'
		, descr = '".strPrepare($this->descr)."'
		, inPackage = '".strPrepare($this->inPackage)."'
		, videoHTML = '".strPrepare($this->videoHTML)."'
		, isNew = ".($this->isNew ? 'true' : 'false')."
		, price = '".floatval($this->price)."'
		, stock = '".intval($this->stock)."'
		, rate = '".floatval($this->rate)."'
		, votesCount = '".intval($this->votesCount)."'
		
		";
		
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		if(!$this->catId)
			$errors[] = new Error('Не указана категория товара', 'catId');
		
		
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
	
		$route = Route::getByName(Route::KARTOCHKA_OBYAVLENIYA);
		$ret = '/catalog/item/'.($this->urlPiece());
		return $ret;
	}
	function urlFull()
	{
		global $CORE, $_CONFIG;
		//vd($_SERVER);
		//$ret = 'http://'.DOMAIN.'/catalog/item/'.($this->urlPiece());
		$ret = 'http://'.$_SERVER['HTTP_HOST'].'/catalog/item/'.($this->urlPiece());
		return $ret;
	}
	
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
	
	
	
	
	
	
	function recountRating()
	{
		$totalRate = Review::getTotalRate(Object::code(Object::PRODUCT), $this->id);
		$this->rate = $totalRate['rate'] / $totalRate['count'];
		$this->votesCount = $totalRate['count'];
		
		$this->update();
	}
	
	
	
	

	function addToCart($quan)
	{
		/*vd($_SESSION['cart']['products'][$this->id]);
		vd($quan);*/
		
		$_SESSION['cart']['products'][$this->id] += intval($quan) ? intval($quan) : 1;
		/*vd($_SESSION['cart']['products'][$this->id]);
		vd($_SESSION['cart']['products'][$this->id] <=0);
		return;*/
		if($_SESSION['cart']['products'][$this->id] <=0)
			unset($_SESSION['cart']['products'][$this->id]);
	}
	
	
	
	function setToCart($quan)
	{
		$_SESSION['cart']['products'][$this->id] = intval($quan) ? intval($quan) : 1;
	}
	
	
	
	
	function removeFromCart($quan)
	{
		unset($_SESSION['cart']['products'][$this->id]);
	}
	
	
	
	function getActionsIds()
	{
		return ProductRelation::getListByProductId($this->id, ProductRelationType::code(ProductRelationType::ACTION));	
	}
	
	function getCoursesIds()
	{
		return ProductRelation::getListByProductId($this->id, ProductRelationType::code(ProductRelationType::COURSE));
	} 
	
	function getDiscountsIds()
	{
		return ProductRelation::getListByProductId($this->id, ProductRelationType::code(ProductRelationType::DISCOUNT));
	}
	
	
	
	
	function initDiscountPrice($discount)
	{
		/*echo '<hr />';
		vd($discount);
		echo '<hr />';*/
		if($discount)
		{
			//vd($this->price);
			$this->discountPrice = self::calculateDiscountPrice($this->price, $discount);
			//vd($this->discountPrice);
			$this->discountPriceInCurrency = Currency::calculatePrice($this->discountPrice);
			$this->discountPriceStr = Currency::formatPrice($this->discountPriceInCurrency);
		}
	}
	
	
	
	function calculateDiscountPrice($price, $discount)
	{
		//return round($price / 100 * (100 - $discount), 2);
		return $price / 100 * (100 - $discount);
	}
	
	
	
	
	
	
	
	function initDiscountObject()
	{
		if($tmp = $this->getDiscountsIds())
		{
			$this->discountObject = Discount::get($tmp[0]->objectId, Status::code(Status::ACTIVE));
		}
		
	}
	
	
	
	
	function initOptPrices($prices=null)
	{
		$this->optPricesArr = OptPrice::getArrByProductId($this->id, $prices);
	}




	function json($scenario)
    {
        $ret = (array)$this;

        if($scenario == 'micro')
        {
            $ret = [];
            $ret['id'] = $this->id;
            $ret['name'] = $this->name;
            $ret['inPackage'] = $this->inPackage;
            $ret['price'] = $this->price;
            $ret['priceStr'] = $this->priceStr;
            $ret['priceInCurrency'] = $this->priceInCurrency;
            $ret['stock'] = $this->stock;
        }

        return $ret;
    }
	
	
	
} 













?>