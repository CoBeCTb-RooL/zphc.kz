<?php
class ActionsController extends MainController{
	
	
	
	function routifyAction()
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
		
		//vd(Action::$fields);
		Core::renderView('actions/indexView.php', $model);
	}
	
	
	function itemsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		Core::renderView('actions/itemsListIndex.php', $MODEL);
	}
	
	
	
	function itemsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$elPP = Action::ADMIN_ELEMENTS_PER_PAGE;
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
					'isActive'=>$MODEL['isActive'],
			);
			//vd($params);
			$MODEL['items'] = Action::getList($params);
			foreach($MODEL['items'] as $c)
				$c->initRelatedProducts();
			//vd($MODEL['items']);
			$params['limit'] = '';
			$MODEL['totalCount'] = Action::getCount($params);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('actions/itemsListAjax.php', $MODEL);
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
			$item = Action::get($_REQUEST['id']);
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
			$MODEL['item'] = Action::get($_REQUEST['id']);
			if($MODEL['item'])
				$MODEL['item']->relatedProducts = ProductRelation::getList(ProductRelationType::code(ProductRelationType::ACTION), $MODEL['item']->id);
			//vd($MODEL['item']->relatedProducts);
			$MODEL['currentCatId'] = $_REQUEST['currentCat'];
			
			$MODEL['catalog'] = CategorySimple::getList(array('status'=>Status::code(Status::ACTIVE)));
			foreach($MODEL['catalog'] as $cat)
				$cat->products = ProductSimple::getList(array('status'=>Status::code(Status::ACTIVE), 'catId'=>$cat->id)); 
			//vd($MODEL['allCats']);
			
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('actions/itemEdit.php', $MODEL);
	}
	
	
	
	function itemEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			Slonne::fixFILES();
			
			if(!$item = Action::get($_REQUEST['id']))
				$item = new Action();
			
			# 	первый уровень проверки - пришло ли в REQUEST всё, что требуется полям класса, и верное ли оно
			# 	дальше будет ещё validate() редактируемого объекта
			$errors = Error::merge($errors, $item->setValuesFromRequestByFields());
			
			
			# 	validate() объекта
			if(!$errors)
				$errors = Error::merge($errors, $item->validate());
			
				
			# 	теперь сохраняем картинки и файлы
			if(!$errors)
			{
				foreach(Action::$fields as $f)
				{
					if(! ($f->type == FieldType::IMG || $f->type == FieldType::FILE) )
						continue;
					
					foreach($_FILES[$f->htmlName] as $file) 	# 	задел на будущее для толпы картинок
					{		
						$tmp = $f->saveMedia($destDir = Action::getMediaDir(), $preserveFilename = false);

						if(gettype($tmp) == 'string')
						{
							# 	удаляем старый файл
							$prevFile = $item->{$f->htmlName};
							if($prevFile)
								unlink(ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$prevFile);
							
							$item->{$f->htmlName} = Action::MEDIA_SUBDIR.'/'.$tmp;
						}
						else 
							$errors[] = $tmp;
					}
				}
			}
			
			//vd(htmlspecialchars($_REQUEST['videoHTML']));
			
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
			ProductRelation::deleteRelations(ProductRelationType::code(ProductRelationType::ACTION), $item->id);
			foreach(array_keys($_REQUEST['products']) as $v)
			{
				//vd($v);
				$rel = new ProductRelation();
				$rel->productId = $v;
				$rel->relationType = ProductRelationType::code(ProductRelationType::ACTION);
				$rel->objectId = $item->id;
				$rel->param1 = $_REQUEST['quans'][$v];
				
				$rel->insert();
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
			$item = Action::get($_REQUEST['id']);
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