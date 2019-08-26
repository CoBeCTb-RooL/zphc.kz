<?php
class OrdersController extends MainController{
	
	
	
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
		
		//vd(Order::$fields);
		Core::renderView('orders/indexView.php', $model);
	}
	
	
	function itemsList()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
	
		Core::renderView('orders/itemsListIndex.php', $MODEL);
	}
	
	
	
	function itemsListAjax()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
		
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$elPP = Order::ADMIN_ELEMENTS_PER_PAGE;
			$page = intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
			$MODEL['elPP'] = $elPP;
			$MODEL['p'] = $page;
			
			
			if($_REQUEST['orderStatus'])
				$MODEL['orderStatus'] = OrderStatus::code($_REQUEST['orderStatus']);
			
				
			$MODEL['chosenUserId'] = $_REQUEST['chosenUserId'];
			$MODEL['chosenId'] = $_REQUEST['chosenId'];
			$MODEL['dateFrom'] = $_REQUEST['dateFrom'];
			$MODEL['dateTo'] = $_REQUEST['dateTo'];
			
			$MODEL['deliveryType'] = DeliveryType::code($_REQUEST['deliveryType']);
			$MODEL['paymentType'] = PaymentType::code($_REQUEST['paymentType']);
			
			$MODEL['customerPhone'] = User::cleanPhone($_REQUEST['customerPhone']);
			$MODEL['customerEmail'] = $_REQUEST['customerEmail'];
			$MODEL['currency'] = Currency::code($_REQUEST['currency']);
			
			$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'id desc';
			$MODEL['desc'] = $_REQUEST['desc'];

			$params = array(
					'orderStatus'=>$MODEL['orderStatus'],
					'orderBy'=>$MODEL['orderBy'].($MODEL['desc'] ? ' DESC ' : ''),
					'id'=>$MODEL['chosenId'],
					'dateFrom'=>$MODEL['dateFrom'],
					'dateTo'=>$MODEL['dateTo'],
					'from'=>$MODEL['p'],
					'count'=>$MODEL['elPP'],
					'orderStatusesNotIn'=>array(OrderStatus::code(OrderStatus::DELETED), ),
					'userId'=>$MODEL['chosenUserId'],
					'deliveryType'=>$MODEL['deliveryType'],
					'paymentType'=>$MODEL['paymentType'],
					'customerPhone'=>$MODEL['customerPhone'],
					'customerEmail'=>$MODEL['customerEmail'],
					'currency'=>$MODEL['currency'], 
			);
			//vd($params);
			$MODEL['items'] = Order::getList($params);
			/*foreach($MODEL['items'] as $c)
				$c->initRelatedProducts();*/
			//vd($MODEL['items']);
			$params['limit'] = '';
			$MODEL['totalCount'] = Order::getCount($params);
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('orders/itemsListAjax.php', $MODEL);
	}
	
	
	function setStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		$errors = null;
		//vd($_REQUEST);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$item = Order::get($_REQUEST['id']);
			if($item)
			{
				$orderStatus = OrderStatus::code($_REQUEST['status']);
				if($orderStatus)
				{
					$item->orderStatus = $orderStatus;
					$item->update();
					
					
					if($orderStatus->code == OrderStatus::CLOSED)
					{
						
						# 	минусуем количество
						$item->initOrderItems();
						$item->initOrderItemProducts();
						foreach($item->orderItems as $oi)
						{
							$oi->product->stock -= $oi->quan;
							$oi->product->stock = $oi->product->stock  > 0 ? $oi->product->stock  : 0;
							$oi->product->update();
						}
						
						
						# 	поощряем реферера
						if($item->refererId)
						{
							if($referer = $item->initReferer())
							{
								//vd($referer->bonus);
								$prize = $_CONFIG['SETTINGS']['refererPrize'];
								if($item->userId != $item->refererId && $prize > 0)
								{
									$params = array(
											'userId'=>$referer->id,
											'transactionType'=>BonusTransactionModel::code(BonusTransactionModel::ADD_FOR_ORDER),
											'param1'=>$item->id,
									);
									$alreadyExistingBonus = Bonus::getList($params);
									
									if(!$alreadyExistingBonus)
									{
										# 	начисляем бонус
										$referer->bonus += $prize;
										$referer->bonus = $referer->bonus >= 0 ? $referer->bonus : 0;
										$referer->update();
										
										# 	сохраняем транзакцию
										$bonus = new Bonus(
												BonusTransactionModel::code(BonusTransactionModel::ADD_FOR_ORDER), 
												$prize, 
												$referer->id, 
												$item->id, 
												$param2=null, 
												$currency=$item->currency
										);
										
										$bonus->insert();
									}
									
								}
							}
						}
						
						
						# 	ЮЗЕРУ заблокированные бонусы  отнять, и отнять от бонусов
						if($item->userId && $item->payedByBonus)
						{
							$user = User::get($item->userId, Status::code(Status::ACTIVE));
							if($user)
							{
								//vd($item->payedByBonus);
								//vd($user);
								$user->bonus = round($user->bonus - $item->payedByBonus, 2);
								$user->bonusBlocked = round($user->bonusBlocked - $item->payedByBonus, 2);
								$user->bonus = $user->bonus > 0 ? $user->bonus : 0;
								$user->bonusBlocked = $user->bonusBlocked >0 ? $user->bonusBlocked : 0;
								$user->update();
						
								# 	сохраняем транзакцию
								$bonus = new Bonus(
										BonusTransactionModel::code(BonusTransactionModel::PAY_FOR_ORDER),
										-$item->payedByBonus,
										$user->id,
										$item->id,
										$param2=null,
										$currency=$item->currency
										);
						
								$bonus->insert();
						
							}
						}
						
					}
					
					
					if($orderStatus->code == OrderStatus::CANCELLED)
					{
						# 	возвращаем заблокированные бонусы хозяйну
						if($item->payedByBonus && $item->userId)
						{
							$user = User::get($item->userId, Status::code(Status::ACTIVE));
							if($user)
							{
								$user->bonusBlocked = round($user->bonusBlocked - $item->payedByBonus, 2);
								$user->bonusBlocked = $user->bonusBlocked >=0 ? $user->bonusBlocked : 0; 
								$user->update();
							}
						}
					}
					
					
				}
				else 
					$errors[] = new Error('Ошибка статуса - '.$_REQUEST['status'].'');
			}
			else
				$errors[] = new Error('Ошибка! Не найден товар '.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['orderStatus'] = $orderStatus;

		echo json_encode($json);
	}
	
	
	
	
	function item()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		//$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			$MODEL['item'] = Order::get($CORE->params[1]);
			if($MODEL['item'])
			{	
				$MODEL['item']->initOrderItems();
				//vd(count($MODEL['item']->orderItems));
				//vd($MODEL['item']->orderItems);
				$MODEL['item']->initOrderItemProducts();
				$MODEL['item']->initReferer();
				
				$MODEL['item']->sortOrderItems();
				$MODEL['item']->initOrderCourses();
				$MODEL['item']->initOrderActions();
			}
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
	
		Core::renderView('orders/itemView.php', $MODEL);
	}
	
	
	
	
	
	function setManagerComment()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$order = Order::get($_REQUEST['id']);
			$order->managerComment = htmlspecialchars($_REQUEST['comment']);
			$order->update();
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
		
		$json['errors'] = $errors;
		//$json['orderStatus'] = $orderStatus;
		
		echo json_encode($json);
	}
	
	
	
	function itemEditSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR))
		{
			Slonne::fixFILES();
			
			if(!$item = Order::get($_REQUEST['id']))
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
				foreach(Order::$fields as $f)
				{
					if(! ($f->type == FieldType::IMG || $f->type == FieldType::FILE) )
						continue;
					
					foreach($_FILES[$f->htmlName] as $file) 	# 	задел на будущее для толпы картинок
					{		
						$tmp = $f->saveMedia($destDir = Order::getMediaDir(), $preserveFilename = true);

						if(gettype($tmp) == 'string')
						{
							# 	удаляем старый файл
							$prevFile = $item->{$f->htmlName};
							if($prevFile)
								unlink(ROOT.'/'.UPLOAD_IMAGES_REL_DIR.$prevFile);
							
							$item->{$f->htmlName} = Order::MEDIA_SUBDIR.'/'.$tmp;
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
					$item->orderStatus = OrderStatus::code(OrderStatus::ACTIVE);
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
			$item = Order::get($_REQUEST['id']);
			if($item)
			{
				
				$item->orderStatus = OrderStatus::code(OrderStatus::DELETED);
				$item->update();
			}
			else
				$errors[] = new Error('Ошибка! Не найден товар '.$_REQUEST['id'].'');
		}
		else
			$errors[] = new Error(Error::NO_ACCESS_ERROR);
	
		$json['errors'] = $errors;
		$json['orderStatus'] = $orderStatusToBe;

		echo json_encode($json);
	}
	
	
	
	
	
	
	
	
	
	function charts()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		$CORE->setLayout(null);
	
		if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
		{
			$elPP = Order::ADMIN_ELEMENTS_PER_PAGE;
			$page = intval($_REQUEST['p']) ? intval($_REQUEST['p'])-1 : 0;
			$MODEL['elPP'] = $elPP;
			$MODEL['p'] = $page;
				
				
			if($_REQUEST['orderStatus'])
				$MODEL['orderStatus'] = OrderStatus::code($_REQUEST['orderStatus']);
					
	
				$MODEL['chosenUserId'] = $_REQUEST['chosenUserId'];
				$MODEL['chosenId'] = $_REQUEST['chosenId'];
				$MODEL['dateFrom'] = $_REQUEST['dateFrom'];
				$MODEL['dateTo'] = $_REQUEST['dateTo'];
					
				$MODEL['deliveryType'] = DeliveryType::code($_REQUEST['deliveryType']);
				$MODEL['paymentType'] = PaymentType::code($_REQUEST['paymentType']);
					
				$MODEL['customerPhone'] = User::cleanPhone($_REQUEST['customerPhone']);
				$MODEL['customerEmail'] = $_REQUEST['customerEmail'];
				$MODEL['currency'] = Currency::code($_REQUEST['currency']);
					
				$MODEL['orderBy'] = $_REQUEST['orderBy'] ? $_REQUEST['orderBy'] : 'id desc';
				$MODEL['desc'] = $_REQUEST['desc'];
	
				$params = array(
						'orderStatus'=>$MODEL['orderStatus'],
						'orderBy'=>$MODEL['orderBy'].($MODEL['desc'] ? ' DESC ' : ''),
						'id'=>$MODEL['chosenId'],
						'dateFrom'=>$MODEL['dateFrom'],
						'dateTo'=>$MODEL['dateTo'],
						/*'from'=>$MODEL['p'],
						'count'=>$MODEL['elPP'],*/
						'orderStatusesNotIn'=>array(OrderStatus::code(OrderStatus::DELETED), ),
						'userId'=>$MODEL['chosenUserId'],
						'deliveryType'=>$MODEL['deliveryType'],
						'paymentType'=>$MODEL['paymentType'],
						'customerPhone'=>$MODEL['customerPhone'],
						'customerEmail'=>$MODEL['customerEmail'],
						'currency'=>$MODEL['currency'],
				);
				//vd($params);
				$MODEL['items'] = Order::getList($params);
				/*foreach($MODEL['items'] as $c)
					$c->initRelatedProducts();*/
				
		}
		else
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
	
		Core::renderView('orders/charts.php', $MODEL);
	}
	
	
	
	
	
}




?>