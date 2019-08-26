<?php 
class Review{
	
	const TBL = 'reviews';
	
	
	public $id;
	public $dateCreated;
	public $name;
	public $email;
	public $phone;
	public $text;
	public $answer;
	public $rate;
	public $status;
	public $userId;
	public $objectType;
	public $objectId;
	
	
	const EL_PP = 2; 
	
		
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->dateCreated = $arr['dateCreated'];
		$m->status = Status::num($arr['status']);
		$m->name = $arr['name'];
		$m->email = $arr['email'];
		$m->phone = $arr['phone'];
		$m->text = $arr['text'];
		$m->answer = $arr['answer'];
		$m->rate = $arr['rate'];
		$m->userId = $arr['userId'];
		$m->objectType = Object::code($arr['objectType']);
		$m->objectId= intval($arr['objectId']);
		
		
		return $m;
	}
	
	
	function getList($params)
	{
		//vd($params);
		$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($params);
		
		if($params['orderBy'])
			$sql.=" ORDER BY ".mysql_real_escape_string($params['orderBy'])." ".($params['desc']?" DESC ":"")."";
			
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
		if($params['status'])
			$sql.=" AND status=".intval($params['status']->num)." ";
		if($params['objectType'])
			$sql.=" AND objectType='".strPrepare($params['objectType']->code)."' ";
		if($params['objectId'])
			$sql.=" AND objectId=".intval($params['objectId'])." ";
		if($params['userId'])
			$sql.=" AND userId='".strPrepare($params['userId'])."' ";
		
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
		//vd($sql);
			
		return $sql;
	}
	
	
	
	
	function get($id)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id;
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	

	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET dateCreated=NOW(), 
		".$this->alterSql()."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	

	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` 
		SET 
		".$this->alterSql()."
		WHERE id=".intval($this->id)."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		$str.="
		  `status` = '".intval($this->status->num)."'
		, `name`='".strPrepare($this->name)."'
		, `email`='".strPrepare($this->email)."'
		, `text`='".strPrepare($this->text)."'
		, `answer`='".strPrepare($this->answer)."'
		, `rate`='".intval($this->rate)."'
		, `userId`=".(intval($this->userId) ? intval($this->userId) : 'NULL')."
		, `objectType`='".strPrepare($this->objectType->code)."'
		, `objectId`='".intval($this->objectId)."'
		";
		
		return $str;
	}
	
	
	
	
	
	function delete($id)
	{
		if($id = intval($id))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE id=".$id;
			DB::query($sql);
			echo mysql_error(); 
		}
	}
	
	
	
	
	
	function validate()
	{
		if(!trim($this->text))
			$problems[] = new Error('Введите Ваше сообщение!', 'text');
		if(!trim($this->name))
			$problems[] = new Error('Введите Ваше имя!', 'name');
		if(!$this->objectType->code)
			$problems[] = new Error('Ошибка типа! Обратитесь к разработчикам..');
		
		
		return $problems;
	}
	
	
	
	
	
	function getTotalRate($objectType, $objectId)
	{
		if($objectType && ($objectId = intval($objectId)))
		{
			$sql = "SELECT COUNT(*) as count, SUM(rate) as rate FROM `".self::TBL."` 
					WHERE objectType='".strPrepare($objectType->code)."' 
					AND objectId='".intval($objectId)."' 
					AND rate>0
					AND status='".intval(Status::code(Status::ACTIVE)->num)."'";
			//vd($sql);
			$qr = DB::query($sql);
			echo mysql_error();
			$next = mysql_fetch_array($qr, MYSQL_ASSOC);
			
			return ($next);
		}
		
		
		
	}
	
	
	
	
}
?>