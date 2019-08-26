<?php
class PresentsController extends MainController{
	
	
	
	function routifyPresent()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
	
		if($section == 'list')
			$action='list1';
	
		if($action)
			$CORE->action = $action;
	}
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		//vd(CategorySimple::$fields);
		
		//vd(Present::$fields);
		Core::renderView('presents/indexView.php', $model);
	}
	
	
	function itemsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		Core::renderView('presents/itemsListIndex.php', $MODEL);
	}
	
	
	
	function itemsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$elPP = Present::ADMIN_ELEMENTS_PER_PAGE;
			$page = intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
			$MODEL['elPP'] = $elPP;
			$MODEL['p'] = $page;
			
			
			if($_REQUEST['status'])
				$MODEL['status'] = Status::num($_REQUEST['status']);
			
			$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'id';
			$MODEL['desc'] = $_REQUEST['desc'];
			$MODEL['isActive'] = $_REQUEST['isActive'] ? true : false;
			//vd($MODEL['status']);
			$params = array(
					'status'=>$MODEL['status'],
					'orderBy'=>$MODEL['orderBy'].($MODEL['desc'] ? ' DESC ' : ''),
					'id'=>$MODEL['chosenId'],
					'from'=>$MODEL['p'],
					'count'=>$MODEL['elPP'],
					'statusesNotIn'=>array(Status::code(Status::DELETED), ),
			);
			//vd($params);
			$MODEL['items'] = Present::getList($params);
			foreach($MODEL['items'] as $c)
				$c->initRelatedProducts();
			//vd($MODEL['items']);
			$params['limit'] = '';
			$MODEL['totalCount'] = Present::getCount($params);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('presents/itemsListAjax.php', $MODEL);
	}
	
	
	function itemSwitchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = Present::get($_REQUEST['id']);
			if($item)
			{
				$statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
	
				$item->status = $statusToBe;
				$item->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найден товар '.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	
	
	function itemEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			$MODEL['item'] = Present::get($_REQUEST['id']);
			if($MODEL['item'])
				$MODEL['item']->initRelatedProducts();
			
			//vd($MODEL['item']);	
			
			$MODEL['catalog'] = CategorySimple::getList(array('status'=>Status::code(Status::ACTIVE)));
			foreach($MODEL['catalog'] as $cat)
				$cat->products = ProductSimple::getList(array('status'=>Status::code(Status::ACTIVE), 'catId'=>$cat->id)); 
			//vd($MODEL['allCats']);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('presents/itemEdit.php', $MODEL);
	}
	
	
	
	function itemEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			if(!$item = Present::get($_REQUEST['id']))
				$item = new Present();
			
			# 	первый уровень проверки - пришло ли в REQUEST всё, что требуется полям класса, и верное ли оно
			# 	дальше будет ещё validate() редактируемого объекта
			$errors = Error::merge($errors, $item->setValuesFromRequestByFields());
			
			
			# 	validate() объекта
			if(!$errors)
				$errors = Error::merge($errors, $item->validate());
			
				
			if(!$errors)
			{
				if($item->id)
					$item->update();
				else
				{
					$item->status = Status::code(Status::ACTIVE);
					$item->id = $item->insert();
				}
			}
			//vd($_REQUEST);
			
			# 	сохраняем продукты
			ProductRelation::deleteRelations(ProductRelationType::code(ProductRelationType::PRESENT_TRIGGER), $item->id);
			ProductRelation::deleteRelations(ProductRelationType::code(ProductRelationType::PRESENT), $item->id);
			
			//vd($_REQUEST);
			
			foreach($_REQUEST['products'] as $relTypeCode=>$productIds)
			{
				$relType = ProductRelationType::code($relTypeCode);
				foreach($productIds as $id=>$on)
				{
					$rel = new ProductRelation();
					$rel->productId = $id;
					$rel->relationType = $relType;
					$rel->objectId = $item->id;
					$rel->param1 = $_REQUEST['quans'][$relTypeCode][$id];
					
					$rel->insert();
				}
			}
			
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
	
		$json['errors'] = $errors;
		$json['item'] = $item;

		echo '<script>window.top.itemEditSubmitComplete('.json_encode($json).')</script>';
	}
	
	
	
	
	
	
	
	function itemDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = Present::get($_REQUEST['id']);
			if($item)
			{
				$item->status = Status::code(Status::DELETED);
				$item->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найден товар '.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
}




?>