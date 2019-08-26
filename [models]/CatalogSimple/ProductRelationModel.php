<?php
class ProductRelation{
	const TBL = 'catalog_simple__products_relations';
	
	public    $productId
			, $relationType
			, $objectId
			, $param1
	;
	
	
	
	
	function init($arr)
	{
		if($arr)
		{
			$u = new self;
				
			$u->productId = $arr['productId'];
			$u->relationType = ProductRelationType::code($arr['relationType']);
			$u->objectId = $arr['objectId'];
			$u->param1 = $arr['param1'];
				
			return $u;
		}
	}
	
	
	
	
	
	
	function getList($relType, $objectId)
	{
		if($relType && ($objectId = intval($objectId)))
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($relType, $objectId);
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[] = self::init($next);
		}
		return $ret;
	}
	
	
	
	function getCount($relType, $objectId)
	{
		$sql="SELECT COUNT(*) FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($relType, $objectId);
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		$next = mysql_fetch_array($qr);
		return $next[0];
	}
	
	
	
	function getListByProductId($productId, $relType)
	{
        #   достаём айдишники только активных скидок
        $activeDiscountIds = [];
        $a = Discount::getList(['status'=>Status::code(Status::ACTIVE), ]);
        foreach($a as $key=>$val)
            $activeDiscountIds[] = $val->id;

		if($relType && ($productId = intval($productId)))
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1
			AND objectId IN(-1, ".( count($activeDiscountIds) ? join(", ", $activeDiscountIds):'-2' ).") 
			".self::getListInnerSql($relType, '', $productId);
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[] = self::init($next);
			//vd($ret);
		}
		return $ret;
	}
	
	
	
	function getListByProductIds($productIds, $relType)
	{
		//vd($productIds);
		if($relType && $productIds)
		{
			$sql="SELECT * FROM `".strPrepare(self::TBL)."` WHERE 1 ".self::getListInnerSql($relType, '', '', $productIds);
			//vd($sql);
			$qr=DB::query($sql);
			echo mysql_error();
			while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				$ret[] = self::init($next);
		}
		return $ret;
	}
	
	
	
	function getListInnerSql($relType, $objectId, $productId, $productIds)
	{
		//vd($relType);
		//vd($params);
		//vd($productIds);
		$sql = "";
		if($objectId)
			$sql.=" AND objectId=".intval($objectId)." ";
		if($relType)
			$sql.=" AND (relationType='".($relType->code)."') ";
		if($productId)
			$sql.=" AND productId=".intval($productId)." ";
		
		if(count($productIds))
		{
			$sql .= " AND productId IN(-1";
			foreach($productIds as $id)
			    if($id)
				    $sql .= ", '".intval($id)."'";
			$sql.=") ";
		}
				
		return $sql;
	}
	
	
	
	
	
	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` SET
		".$this->innerAlterSql()."
		";
		//vd($sql);
		DB::query($sql);
		echo mysql_error();
		return mysql_insert_id();
		//vd($sql);
	}
	
	
	function innerAlterSql()
	{
		$str="
		  productId='".intval($this->productId)."'
		, objectId= '".intval($this->objectId)."'
		, relationType = '".strPrepare($this->relationType->code)."'
		, param1 = '".strPrepare($this->param1)."'
		
		";
	
	
		return $str;
	}
	
	
	
	
	
	function deleteRelations($relType, $objectId)
	{
		
		if($relType && ($objectId = intval($objectId)))
		{
			$sql = "DELETE FROM `".strPrepare(self::TBL)."` WHERE relationType='".strPrepare($relType->code)."' AND objectId='".$objectId."'";
			vd($sql);
			DB::query($sql);
			echo mysql_error();
		}
	}
	
	
	
	
}