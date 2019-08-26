<?php
class CatalogSimpleController extends MainController{
	
	
	
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
		
		//echo "!";
		
		//vd(CategorySimple::$fields);
		//vd(ProductSimple::$fields);
		Core::renderView('catalogSimple/indexView.php', $model);
	}
	
	
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
				$cat->itemsCount = ProductSimple::getCount($params);
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
			//vd($MODEL);
	
		Core::renderView('catalogSimple/catsList.php', $MODEL);
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
	
		Core::renderView('catalogSimple/catEdit.php', $MODEL);
	}
	
	
	function catEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			Slonne::fixFILES();
			
			if(!$item = CategorySimple::get($_REQUEST['id']))
				$item = new CategorySimple();
			
			# 	первый уровень проверки - пришло ли в REQUEST всё, что требуется полям класса, и верное ли оно
			# 	дальше будет ещё validate() редактируемого объекта
			$errors = Error::merge($errors, $item->setValuesFromRequestByFields());
			
			$item->catId = intval($_REQUEST['catId']);
			
			
			# 	validate() объекта
			if(!$errors)
				$errors = Error::merge($errors, $item->validate());
			
				
			# 	теперь сохраняем картинки и файлы
			if(!$errors)
			{
				foreach(CategorySimple::$fields as $f)
				{
					if(! ($f->type == FieldType::IMG || $f->type == FieldType::FILE) )
						continue;
					
					foreach($_FILES[$f->htmlName] as $file) 	# 	задел на будущее для толпы картинок
					{		
						$tmp = $f->saveMedia($destDir = ProductSimple::getMediaDir());

						if(gettype($tmp) == 'string')
						{
							# 	удаляем старый файл
							$prevFile = $item->{$f->htmlName};
							if($prevFile)
								unlink(ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$prevFile);
							
							$item->{$f->htmlName} = ProductSimple::MEDIA_SUBDIR.'/'.$tmp;
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
	
	
	
	
	
	
	function productsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		Core::renderView('catalogSimple/productsListIndex.php', $MODEL);
	}
	
	
	
	function productsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			//vd($_REQUEST);
			$MODEL['cat'] = CategorySimple::get($_REQUEST['catId']);
			$MODEL['cats'] = CategorySimple::getFullCatsTree($status=Status::code(Status::ACTIVE));
			
			$elPP = ProductSimple::ADMIN_ELEMENTS_PER_PAGE;
			$page = intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
			$limit=" LIMIT ".$page*$elPP.", ".$elPP."";
			$MODEL['elPP'] = $elPP;
			$MODEL['p'] = $page;
			
			//vd($_REQUEST);
			//vd($_REQUEST['catId']);
			
			$MODEL['chosenId'] = $_REQUEST['chosenId'];
			
			//vd($_REQUEST);
			
			if($_REQUEST['status'])
				$MODEL['status'] = Status::num($_REQUEST['status']);
			
			$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'idx';
			$MODEL['desc'] = $_REQUEST['desc'];
			//vd($MODEL['status']);
			$params = array(
					'catId'=>$MODEL['cat']->id,
					'status'=>$MODEL['status'],
					'orderBy'=>$MODEL['orderBy'].($MODEL['desc'] ? ' DESC ' : ''),
					'id'=>$MODEL['chosenId'],
				//'limit'=>$limit,
					'from'=>$MODEL['p'],
					'count'=>$MODEL['elPP'],
					'statusesNotIn'=>array(Status::code(Status::DELETED), ), 
			);
			//vd($params);
			$MODEL['items'] = ProductSimple::getList($params);
			$params['limit'] = '';
			$MODEL['totalCount'] = ProductSimple::getCount($params);
	
			foreach($MODEL['items'] as $item)
			{
				// 	наполняем айдишники
				$catIdsToTake[] = $item->catId;
			}
			
			// 	инитим категории
			$cats = CategorySimple::getByIdsList(array_unique($catIdsToTake));
			foreach($MODEL['items'] as $item)
				$item->cat = $cats[$item->catId];

			$MODEL['noTop'] = $_REQUEST['noTop'];
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
			//vd($MODEL);
	
		Core::renderView('catalogSimple/productsListAjax.php', $MODEL);
	}
	
	
	function productSwitchStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = ProductSimple::get($_REQUEST['id']);
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
	
	
	
	
	
	
	function productEdit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			$MODEL['item'] = ProductSimple::get($_REQUEST['id']);
			$MODEL['currentCatId'] = $_REQUEST['currentCat'];
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('catalogSimple/productEdit.php', $MODEL);
	}
	
	
	
	function productEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			Slonne::fixFILES();
			
			if(!$item = ProductSimple::get($_REQUEST['id']))
				$item = new ProductSimple();
			
			# 	первый уровень проверки - пришло ли в REQUEST всё, что требуется полям класса, и верное ли оно
			# 	дальше будет ещё validate() редактируемого объекта
			$errors = Error::merge($errors, $item->setValuesFromRequestByFields());
			
			$item->catId = intval($_REQUEST['catId']);
			
			
			# 	validate() объекта
			if(!$errors)
				$errors = Error::merge($errors, $item->validate());
			
				
			# 	теперь сохраняем картинки и файлы
			if(!$errors)
			{
				foreach(ProductSimple::$fields as $f)
				{
					if(! ($f->type == FieldType::IMG || $f->type == FieldType::FILE) )
						continue;
					
					foreach($_FILES[$f->htmlName] as $file) 	# 	задел на будущее для толпы картинок
					{		
						$tmp = $f->saveMedia($destDir = ProductSimple::getMediaDir());

						if(gettype($tmp) == 'string')
						{
							# 	удаляем старый файл
							$prevFile = $item->{$f->htmlName};
							if($prevFile)
								unlink(ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$prevFile);
							
							$item->{$f->htmlName} = ProductSimple::MEDIA_SUBDIR.'/'.$tmp;
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
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
	
		$json['errors'] = $errors;
		$json['item'] = $item;

		echo '<script>window.top.CatalogSimple.productEditSubmitComplete('.json_encode($json).')</script>';
	}
	
	
	
	
	
	
	
	function productDelete()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = ProductSimple::get($_REQUEST['id']);
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
	
	
	
	
	
	
	
	
	function productsQuans()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			
			if($_REQUEST['go_btn'])
			{
				//vd($_REQUEST);
				foreach($_REQUEST['price'] as $productId=>$price)
				{
					$price = Funx::cleanSumStr($price);
					
					$p = ProductSimple::get($productId);
					$quan = $_REQUEST['quan'][$p->id];
					
					if($price>0 )
						$p->price = $price;
					
					//if(intval($quan))
						$p->stock = intval($quan);
					
					$p->update();					
				}
				
				echo '<script>notice("Сохранено!")</script>';
			}
			
			
			$MODEL['catalog'] = CategorySimple::getList(array('status'=>Status::code(Status::ACTIVE)));
			foreach($MODEL['catalog'] as $cat)
				$cat->products = ProductSimple::getList(array('status'=>Status::code(Status::ACTIVE), 'catId'=>$cat->id)); 
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('catalogSimple/productsQuans.php', $MODEL);
	}
	
	
	
	
	
	
	
	
	function productsListSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			//vd($_REQUEST);
			foreach($_REQUEST['idx'] as $id=>$val)
			{
				if($item = ProductSimple::get($id))
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
	
		echo '<script>window.top.CatalogSimple.productsListSubmitComplete('.json_encode($json).')</script>';
	}
	
	

	
	
	
	function optPrices()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
				
			if($_REQUEST['go_btn'])
			{
				//vd($_REQUEST);
				
				# 	сохраняем idxOpt
				foreach($_REQUEST['idxOpt'] as $id=>$idx)
				{
					$p = ProductSimple::get($id);
					$p->idxOpt = intval($idx);
					$p->update();
				}
				
				# 	сохраняем прайсы
				foreach($_REQUEST['price'] as $productId=>$pricesArr)
				{
					//vd($productId);
					OptPrice::deleteByProductId($productId);
					
					# 	сохраняем цены 
					foreach($pricesArr as $sum=>$price)
					{
						$price = Funx::cleanSumStr($price);
						if($price)
						{
							$op = new OptPrice($productId, $sum, $price);
							$op->insert();
						}
					}
				}
			}
			
				
			$MODEL['catalog'] = CategorySimple::getList(array('status'=>Status::code(Status::ACTIVE)));
			foreach($MODEL['catalog'] as $cat)
			{
				$cat->products = ProductSimple::getList(array('status'=>Status::code(Status::ACTIVE), 'catId'=>$cat->id, 'orderBy'=>'idxOpt, name'));
				foreach($cat->products as $p)
					$p->initOptPrices();
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('catalogSimple/optPrices.php', $MODEL);
	}
	
	
	
}




?>