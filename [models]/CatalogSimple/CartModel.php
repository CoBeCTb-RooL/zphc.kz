<?php
class Cart{
	
	public 	  $ids
			
			, $courses
			, $actions
			, $individualProducts
			, $presents
			
			, $currency
			
			, $sum
			, $sumInCurrency
			
			, $totalQuan
			
			, $paymentType
			, $deliveryType
			
			, $deliveryCost
			, $deliveryCostInCurrency
			
			, $payedByBonus
			, $payedByBonusInCurrency
			
			, $orderItems
			
			, $allCartProductsInfo
			, $allCartCoursesInfo
			, $allCartActionsInfo
			
			
			
	;
	
	private $_ids;
	
	
	function init()
	{
		global $_GLOBALS, $USER;
		
		
		$this->currency = $_GLOBALS['currency'];
		
		$this->ids = $_SESSION['cart']['products'];
		//vd($this->ids);
		
		# 	инициализируем все товары корзины
		$this->allCartProductsInfo = ProductSimple::getList(array(
													'status' => Status::code(Status::ACTIVE),
													'ids' => array_keys($this->ids),
												));
		
		# 	проверка товаров на наличие, и УРЕЗАНИЕ если надо
		foreach($this->allCartProductsInfo as $p)
		{
			if(!$p->stock || !$this->ids[$p->id])
				$p->removeFromCart();
			if($p->stock < $this->ids[$p->id])
				$p->setToCart($p->stock);
		}
		
		
		$this->ids = $_SESSION['cart']['products'];
		$this->_ids = $this->ids;
		
		
		# 	ищем КУРСЫ
		$courses = self::getCoursesListFromCartArr($this->_ids);
		//vd($courses);
		foreach($courses as $course)
		{
			$course->initRelatedProducts($this->allCartProductsInfo);
			$this->allCartCoursesInfo[$course->id] = $course;
			while($isCourseInCart = Cart::isRelatedProductsInCartArray($course->relatedProducts, $this->_ids))
			{
				# 	считаем общую сумму
				$this->sum['sum'] += $course->sum['sum'];
				$this->sumInCurrency['sum'] += $course->sumInCurrency['sum'];
				
				$this->sum['discountSum'] += $course->sum['discountSum'];
				$this->sumInCurrency['discountSum'] += $course->sumInCurrency['discountSum'];
				
				$this->courses[$course->id] ++;
				self::excludeProductsFromCartArray($course->relatedProducts, $this->_ids);
				
				# 	формируем OrderItems[]
				foreach($course->relatedProducts as $p)
				{
					$p->product->initDiscountPrice($course->discount);
					$this->orderItems[] = new OrderItem($p->product, ProductRelationType::code(ProductRelationType::COURSE), $p->param1, $course->discount, $course->id);
				}
			}
		}
		
		//vd($this->orderItems);
		
		
		# 	ищем АКЦИИ
		$actions = self::getActionsListFromCartArr($this->_ids);
		//vd($actions);
		foreach($actions as $action)
		{
			$action->initRelatedProducts($this->allCartProductsInfo);
			$this->allCartActionsInfo[$action->id] = $action;
			if($isActionInCart = Cart::isRelatedProductsInCartArray($action->relatedProducts, $this->_ids))
			{
				# 	считаем количество штук товаров по акции
				$tmp = array();
				foreach($action->relatedProducts as $p)
				{
					$quan = $this->_ids[$p->product->id];
					//vd($quan);
					
					$tmp[$p->product->id] = $quan;
					
					
					# 	считаем общую сумму
					$p->product->initDiscountPrice($action->discount);
					
					$this->sum['sum'] += $p->product->price * $quan;
					$this->sumInCurrency['sum'] += $p->product->priceInCurrency * $quan;
						
					$this->sum['discountSum'] += ($p->product->price * $quan - $p->product->discountPrice * $quan);
					$this->sumInCurrency['discountSum'] += ($p->product->priceInCurrency * $quan - $p->product->discountPriceInCurrency * $quan);
					
					# 	формируем OrderItems[]
					$this->orderItems[] = new OrderItem($p->product, ProductRelationType::code(ProductRelationType::ACTION), $quan, $action->discount, $action->id);
				}
				
				$this->actions[$action->id] = $tmp;
				
				self::excludeProductsFromCartArray($action->relatedProducts, $this->_ids, $getAllQuan=true);
			}
		}
		
		
		
		# 	кладём индивидуальные продукты
		$this->individualProducts = $this->_ids;
		
		
		# 	считаем общую сумму индивидуальных товаров
		foreach($this->_ids as $productId=>$quan)
		{
			# 	инициализируем скидку
			$this->allCartProductsInfo[$productId]->initDiscountObject();
			$product = $this->allCartProductsInfo[$productId];
			
			if($product->discountObject)
				$product->initDiscountPrice($product->discountObject->discount);
			
			//vd($product->price);
			$price = $product->discountPrice ? $product->discountPrice : $product->price;
			$priceInCurrency = $product->discountPriceInCurrency ? $product->discountPriceInCurrency : $product->priceInCurrency;
			
			
			$this->sum['sum'] += $price * $quan;
			$this->sumInCurrency['sum'] += $priceInCurrency * $quan;
		
			# 	формируем OrderItems[]
			$discount = null;
			$productRelationType = null;
			$param1 = null;
			if($product->discountObject)
			{
				$discount = $product->discountObject->discount;
				$productRelationType = ProductRelationType::DISCOUNT;
				$param1 = $product->discountObject->id;
			}
			$this->orderItems[] = new OrderItem(
											$product, 
											$productRelationType,
											$quan,
											$discount, 
											$param1
										);
		}
		
		/*
		vd($this->sum);
		vd($this->sumInCurrency);*/
		
		
		$this->sum['totalSum'] = $this->sum['sum'] - $this->sum['discountSum'];
		$this->sumInCurrency['totalSum'] = $this->sumInCurrency['sum'] - $this->sumInCurrency['discountSum'];
		//vd($this);
		
		
		$this->paymentType = self::getPaymentType() ? self::getPaymentType() : PaymentType::code(PaymentType::CASH);
		
		$this->deliveryType= self::getDeliveryType() ? self::getDeliveryType() : DeliveryType::code(DeliveryType::PICKUP);
		
		
		
		$this->deliveryCost = self::getDeliveryCost($this->deliveryType, $this->sum['totalSum']);
		$this->deliveryCostInCurrency = Currency::calculatePrice($this->deliveryCost);
		
		
		
		//$_SESSION['cart']['payedByBonus'] = 10;
		# 	проверка суммы бонусов в сессии
		if($USER)
		{
			$this->payedByBonus = self::getPayedByBonus();
			$this->payedByBonus = $USER->getAvailableBonusSum($this->payedByBonus);
			Cart::setPayedByBonus($this->payedByBonus);
			
			$this->payedByBonus = self::getPayedByBonus();
			$this->payedByBonusInCurrency = Currency::calculatePrice($this->payedByBonus);
		}
		
		
		
		# 	общее число товаров
		foreach($this->ids as $val)
			$this->totalQuan += $val;
		
			
			
			
		# 	подсчитываем ПОДАРКИ
		$this->_ids = $this->ids;
		$presents = self::getPresentsListFromCartArr($this->_ids);
		
		foreach($presents as $present)
		{
			$present->initRelatedProducts($this->allCartProductsInfo);
			//vd($present);
			$this->allCartPresentsInfo[$present->id] = $present;
			while($isPresentProductsInCart = Cart::isRelatedProductsInCartArray($present->triggerProducts, $this->_ids))
			{
				# 	TODO: заполнить массив подарков
				foreach($present->presentProducts as $p)
					$this->presents[$p->product->id] += $p->param1 ? $p->param1 : 1;
				
				self::excludeProductsFromCartArray($present->triggerProducts, $this->_ids);
		
				# 	формируем OrderItems[]
				foreach($present->presentProducts as $p)
				{
					$this->orderItems[] = new OrderItem($p->product, ProductRelationType::code(ProductRelationType::PRESENT), $p->param1, $discount=100, $present->id);
				}
			}
		}
			
		//vd($this->orderItems);
		/*	
		
		echo'
		<table border="1">'; 
		foreach($this->orderItems as $oi)
		{
			echo '
			<tr>
				<td>'.$oi->productName.'</td>
				<td>'.$oi->purchaseType->code.'</td>
				<td>x'.$oi->quan.'</td>
				<td>id='.$oi->param1.'</td>
			</tr>';	
		}
		echo '
		</table>';
		vd($this->orderItems);*/
	}
	
	
	
	
	function isItemAlreadyInCart($id)
	{
		return $this->ids[$id] ? true : false; 
	}
	
	
	
	
	
	
	
	
	function isRelatedProductsInCartArray($relatedProducts, $cartArr)
	{
		$ret = true;
		//vd($relatedProducts);
		//vd($cartArr);
		foreach($relatedProducts as $p)
		{
			$requiredQuan = $p->param1 ? $p->param1 : 1;
			if(! $cartArr[$p->product->id] || $cartArr[$p->product->id] < $requiredQuan)
				$ret = false;
		}
		
		return $ret;
	}
	
	
	
	
	
	function getCoursesListFromCartArr($cartArr)
	{
		$courseRelations = ProductRelation::getListByProductIds(array_keys($cartArr), ProductRelationType::code(ProductRelationType::COURSE));
		foreach($courseRelations as $rel)
			$coursesIds[] = $rel->objectId;
		//vd($courseRelations);
		//vd($coursesIds);
		$coursesIds = array_unique($coursesIds);
		//vd($coursesIds);
		$courses = Course::getList(array(
				'statuses'=>array(Status::code(Status::ACTIVE)),
				'ids'=>$coursesIds,
		));
		
		return $courses;
	}
	
	
	
	
	
	function getActionsListFromCartArr($cartArr)
	{
		$actionRelations = ProductRelation::getListByProductIds(array_keys($cartArr), ProductRelationType::code(ProductRelationType::ACTION));
		foreach($actionRelations as $rel)
			$ids[] = $rel->objectId;
		//vd($courseRelations);
		//vd($ids);
		$ids = array_unique($ids);
		//vd($ids);
		$actions = Action::getList(array(
				'statuses'=>array(Status::code(Status::ACTIVE)),
				'ids'=>$ids,
				'isActive'=>true,
		));

		return $actions;
	}
	
	
	
	function getPresentsListFromCartArr($cartArr)
	{
		$relations = ProductRelation::getListByProductIds(array_keys($cartArr), ProductRelationType::code(ProductRelationType::PRESENT_TRIGGER));
		foreach($relations as $rel)
			$presentsBunchIds[] = $rel->objectId;

		$presentsBunchIds = array_unique($presentsBunchIds);
		//vd($presentsBunchIds);
		$presentsBunches = Present::getList(array(
				'statuses'=>array(Status::code(Status::ACTIVE)),
				'ids'=>$presentsBunchIds,
		));

		return $presentsBunches;
	}
	
	
	
	
	function excludeProductsFromCartArray($relatedProducts, &$cartArr, $getAllQuan = false)
	{
		//echo " excludeProductsFromCartArray ";
		//vd($cartArr);
		foreach($relatedProducts as $p)
		{
			$quan = $p->param1 ? $p->param1 : 1;
			if($getAllQuan)
				$quan = $cartArr[$p->product->id];
			
			$cartArr[$p->product->id] -= $quan;
			
			if($cartArr[$p->product->id] <=0)
				unset($cartArr[$p->product->id]);
		}
	}
	
	
	
	
	function getPaymentType($type)
	{
		return PaymentType::code($_SESSION['cart']['paymentTypeCode']);
	}
	function setPaymentType($type)
	{
		$_SESSION['cart']['paymentTypeCode'] = $type->code; 
	}
	
	
	
	function getDeliveryType($type)
	{
		return DeliveryType::code($_SESSION['cart']['deliveryTypeCode']);
	}
	function setDeliveryType($type)
	{
		$_SESSION['cart']['deliveryTypeCode'] = $type->code;
	}
	
	
	
	function getDeliveryCost($type, $totalSum)
	{
		global $_CONFIG;
		
		if($type->code !=DeliveryType::PICKUP)
		{	
			//vd($totalSum);
			$price = $totalSum < $_CONFIG['SETTINGS']['order_sum_for_free_delivery'] ? $_CONFIG['SETTINGS']['delivery_cost'] : 0;
		}
				
		return $price;
	}
	
	
	
	
	
	function getPayedByBonus()
	{
		return $_SESSION['cart']['payedByBonus'];
	}
	function setPayedByBonus($sumInBucks)
	{
		$_SESSION['cart']['payedByBonus'] = $sumInBucks;
	}
	
	
	
	
	function clean()
	{
		unset($_SESSION['cart']);
	}
	
	
	
}