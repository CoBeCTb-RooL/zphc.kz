<?php
class CourseController extends MainController{
	
	
	
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
		//vd(Course::$fields);
		Core::renderView('courses/indexView.php', $model);
	}
	
	/*
	function catsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$MODEL['cat'] = CategorySimple::get($_REQUEST['catId']);
	
			if($MODEL['cat'])
			{
				$MODEL['cat']->getElderCats();
			}
	
			$params = array(
						'pid'=>intval($_REQUEST['catId']),
						'statusesNotIn'=>array(Status::code(Status::DELETED), ),
					);
			$MODEL['list'] = CategorySimple::getList($params);
			foreach($MODEL['list'] as $cat)
			{
				$params = array(
						'pid'=>intval($cat->id),
						'status'=>Status::code(Status::ACTIVE),
				);
				$cat->subCatsCount = CategorySimple::getCount($params);
				
				$params['catId'] = $cat->id;
				$cat->itemsCount = Course::getCount($params);
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
			//vd($MODEL);
	
		Core::renderView('courses/catsList.php', $MODEL);
	}
	
	
	
	
	function catEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			$MODEL['cat'] = CategorySimple::get($_REQUEST['catId']);
					
			$MODEL['currentCatId'] = $_REQUEST['currentCat'];
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('courses/catEdit.php', $MODEL);
	}
	
	
	function catEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			if(!$item = CategorySimple::get($_REQUEST['id']))
				$item = new CategorySimple();
				
			$item->pid = intval($_REQUEST['pid']);
			$item->name = strPrepare($_REQUEST['name']);
			
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
					$item->idx = CategorySimple::getNextIdx();
					$item->id = $item->insert();
				}
			}
			//vd($_REQUEST);
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
	
		$json['errors'] = $errors;
		$json['item'] = $item;

		echo '<script>window.top.catEditSubmitComplete('.json_encode($json).')</script>';
	}
	
	
	
	function catSwitchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$cat = CategorySimple::get($_REQUEST['catId']);
			if($cat)
			{
				$statusToBe = $cat->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
				//vd($statusToBe);
	
				$cat->status = $statusToBe;
				$cat->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найдена категория '.$_REQUEST['catId'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	
	function catsListSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			//usleep(800000);
			foreach($_REQUEST['idx'] as $catId=>$val)
			{
				if($val = intval($val))
					CategorySimple::setIdx($catId, $val);
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors']=$errors;

		echo '<script>window.top.catsListSubmitComplete('.json_encode($json).')</script>';
	}
	
	
	function catDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = CategorySimple::get($_REQUEST['id']);
			if($item)
			{
				$item->status = Status::code(Status::DELETED);
				$item->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найдена категория '.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	*/
	
	
	
	
	
	function itemsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		Core::renderView('courses/itemsListIndex.php', $MODEL);
	}
	
	
	
	function itemsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$elPP = Course::ADMIN_ELEMENTS_PER_PAGE;
			$page = intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
			$MODEL['elPP'] = $elPP;
			$MODEL['p'] = $page;
			
			
			if($_REQUEST['status'])
				$MODEL['status'] = Status::num($_REQUEST['status']);
			
			$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'idx';
			$MODEL['desc'] = $_REQUEST['desc'];
			//vd($MODEL['status']);
			$params = array(
					'status'=>$MODEL['status'],
					'orderBy'=>$MODEL['orderBy'].($MODEL['desc'] ? ' DESC ' : ''),
					'id'=>$MODEL['chosenId'],
					'from'=>$MODEL['p']*$MODEL['elPP'],
					'count'=>$MODEL['elPP'],
					'statusesNotIn'=>array(Status::code(Status::DELETED), ), 
			);
			//vd($params);
			$MODEL['items'] = Course::getList($params);
			foreach($MODEL['items'] as $c)
				$c->initRelatedProducts();
			$params['limit'] = '';
			$MODEL['totalCount'] = Course::getCount($params);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('courses/itemsListAjax.php', $MODEL);
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
			$item = Course::get($_REQUEST['id']);
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
			$MODEL['item'] = Course::get($_REQUEST['id']);
			$MODEL['item']->relatedProducts = ProductRelation::getList(ProductRelationType::code(ProductRelationType::COURSE), $MODEL['item']->id);
			//vd($MODEL['item']->relatedProducts);
			$MODEL['currentCatId'] = $_REQUEST['currentCat'];
			
			$MODEL['catalog'] = CategorySimple::getList(array('status'=>Status::code(Status::ACTIVE)));
			foreach($MODEL['catalog'] as $cat)
				$cat->products = ProductSimple::getList(array('status'=>Status::code(Status::ACTIVE), 'catId'=>$cat->id)); 
			//vd($MODEL['allCats']);
			
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('courses/itemEdit.php', $MODEL);
	}
	
	
	
	function itemEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			Slonne::fixFILES();
			
			if(!$item = Course::get($_REQUEST['id']))
				$item = new Course();
			
			# 	первый уровень проверки - пришло ли в REQUEST всё, что требуется полям класса, и верное ли оно
			# 	дальше будет ещё validate() редактируемого объекта
			$errors = Error::merge($errors, $item->setValuesFromRequestByFields());
			
			
			# 	validate() объекта
			if(!$errors)
				$errors = Error::merge($errors, $item->validate());
			
				
			# 	теперь сохраняем картинки и файлы
			if(!$errors)
			{
				foreach(Course::$fields as $f)
				{
					if(! ($f->type == FieldType::IMG || $f->type == FieldType::FILE) )
						continue;
					
					foreach($_FILES[$f->htmlName] as $file) 	# 	задел на будущее для толпы картинок
					{		
						$tmp = $f->saveMedia($destDir = Course::getMediaDir(), $preserveFilename = true);

						if(gettype($tmp) == 'string')
						{
							# 	удаляем старый файл
							$prevFile = $item->{$f->htmlName};
							if($prevFile)
								unlink(ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$prevFile);
							
							$item->{$f->htmlName} = Course::MEDIA_SUBDIR.'/'.$tmp;
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
			ProductRelation::deleteRelations(ProductRelationType::code(ProductRelationType::COURSE), $item->id);
			foreach(array_keys($_REQUEST['products']) as $v)
			{
				//vd($v);
				$rel = new ProductRelation();
				$rel->productId = $v;
				$rel->relationType = ProductRelationType::code(ProductRelationType::COURSE);
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
			$item = Course::get($_REQUEST['id']);
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
	
	
	
	
	
	function itemsListSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			//vd($_REQUEST);
			foreach($_REQUEST['idx'] as $id=>$val)
			{
				if($item = Course::get($id))
				{
					$item->idx = intval($val);
					$item->update();
				}
			}
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
	
		$json['errors'] = $errors;
		//$json['item'] = $item;

		echo '<script>window.top.itemsListSubmitComplete('.json_encode($json).')</script>';
	}
	
	
	
}




?>