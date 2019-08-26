<?php
CategorySimple::initFields();
class CategorySimple
{
	const TBL = 'catalog_simple__categories';
	
	public    $id
			, $pid
			, $status
			, $name
			, $photo
			, $dateCreated
			, $idx
		;
	
	static $fields;
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
			
			$u->id = $arr['id'];
			$u->status = Status::num($arr['status']);
			$u->pid = $arr['pid'];
			$u->name = $arr['name'];
			$u->photo = $arr['photo'];
			$u->dateCreated = $arr['dateCreated'];
			$u->idx = $arr['idx'];
			
			return $u;
		}
	}
	
	
	
	function initFields()
	{
		//self::$fields = '123d';
		
		$f = new FieldType(FieldType::VARCHAR, 'Наименование', 'name', $isRequired=true, $isShownInList=true, $isHighlighted=true, $param1 = null);
		$arr[] = $f;
		
		$f = new FieldType(FieldType::IMG, 'Фото', 'photo', $isRequired=true, $isShownInList=true, $isHighlighted=false, $param1 = null);
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
		
		$sql.=" ORDER BY ".(mysql_real_escape_string($params['orderBy']) ? mysql_real_escape_string($params['orderBy']) : ' idx ASC ') ." ";

		if( ($params['from'] = intval($params['from']))>=0 && ($params['count'] = intval($params['count']))>0)
			$sql.=" LIMIT ".$params['from'].", ".$params['count']." ";
			
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
			$ret[] = self::init($next);
				
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
		if($params['pid'])
			$sql.=" AND pid=".intval($params['pid'])." ";
		
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
			
		return $sql;
	}
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		dateCreated = NOW(),
		".$this->innerAlterSql()."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
	}
	
	
	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` SET
		".$this->innerAlterSql()."
		WHERE id=".$this->id."
		";
		DB::query($sql);
		echo mysql_error();
		//vd($sql);
	}
	
	
	
	
	function innerAlterSql()
	{
		$str="
		  status = '".intval($this->status->num)."'		
		, pid = '".strPrepare($this->pid)."'
		, name = '".strPrepare($this->name)."'
		, photo = '".strPrepare($this->photo)."'
		, idx = '".intval($this->idx)."'	
		";
		
		return $str;
	}
	
	
	
	
	
	function validate()
	{
		$errors = null;
		
		if(!$this->name)
			$errors[] = new Error('Введите название', 'name');
		
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
		$sql = "SELECT MAX(idx) as res  FROM `".mysql_real_escape_string(self::TBL)."` WHERE 1 ".($catId ? " AND pid=".intval($catId)." " : "")."";
		$qr = DB::query($sql);
		echo mysql_error();
	
		$next = mysql_fetch_array($qr, MYSQL_ASSOC);
		$res = $next['res'];
	
		$res = $res % 10 ? $res + (10-$res%10) : $res+10;
	
		return $res;
	}
	
	
	
	
	
	
	function getElderCats()
	{
		$pid = $this->pid;
		while($pid)
		{
			$tmp = self::get($pid);
			$pid = $tmp->pid;
			$ret[] = $tmp;
		}
	
		$this->elderCats = array_reverse($ret);
	}
	
	
	
	function drawTreeSelect($pid/*чьих детей отображать*/, $self_id, $idToBeSelected, $level=0 )
	{
		global $_CONFIG;
	
		$pid=intval($pid);
		$level=intval($level);
	
		$cat = self::get($pid);
		if($cat->id == $self_id && $self_id)
			return $ret;
	
		if($cat->id )
		{
			$ret.='
			<option '.($idToBeSelected==$cat->id?' selected="selected"  ':'').' value="'.$cat->id.'">';
			for($i=1; $i<$level; $i++)
			{
				$ret.='------';
			}
			$ret.='| ('.$cat->id.') '.$cat->name;
			$ret.='
			</option>';
		}

		#	достаём детей
		$params = array(
				'pid'=>$pid,
				'statusesNotIn'=>array(Status::code(Status::DELETED)),
				'limit'=>'',
				'order'=>'',
				'additionalClauses'=>'and 1',
				'status'=>null,
		);
		$children = self::getList($params);
		foreach($children as $key=>$child)
		{
			$ret.=self::drawTreeSelect($child->id, $self_id,  $idToBeSelected,  ($level+1));
		}

		return $ret;
	}
	
	
	
	
	
	function setIdx($id, $val)
	{
		if($id=intval($id))
		{
			$sql = "UPDATE `".self::TBL."` SET idx='".intval($val)."' WHERE id=".$id;
			vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
	# 	пока что только для 2 уровней
	function getFullCatsTree($status)
	{
		//vd($status);
		$ret = self::getList(array('status'=>$status));
		/*foreach($ret as $key=>$cat)
		{
			$cat->subCats = self::getList($cat->id, $status);
		}*/
	
		return $ret;
	}
	
	
	
	
	
	function getByIdsList($ids, $status)
	{
		if($ids)
		{
			foreach($ids as $key=>$val)
				$ids[$key] = intval($val);
					
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1  AND id IN (".join(', ', $ids).") ";
			if($status)
				$sql.=" AND status='".intval($status->num)."' ";
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[$next['id']] = self::init($next);
		}
		return $ret;
	}
	
	
	
	
	

	function url()
	{
		global $CORE, $_CONFIG;
	
		$route = Route::getByName(Route::KARTOCHKA_OBYAVLENIYA);
		$ret = '/catalog/'.($this->urlPiece());
		return $ret;
	}
	
	
	
	function urlPiece()
	{
		return ''.$this->id.'_'.str2url($this->name);
	}
	
	
	
	
	/*function getMediaDir()
	{
		return ROOT.'/'.UPLOAD_IMAGES_REL_DIR.self::MEDIA_SUBDIR.'/';
	}*/
	
	
	
	
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
	
	
} 













?>