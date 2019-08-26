<?php
class ToolsController extends MainController{
	
	/*function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
		
		if($action)
			$CORE->action = $action;
	}*/
		
		
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
		
		Core::renderView('tools/index.php', $MODEL);
	}
	
	
	
	# 	пересчёт кол-ва объявлений
	function advsCountRecache()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
		
		
		# 	сразу обозначаем какой тул
		$toolType = ToolType::code(ToolType::ADV_COUNT_RECACHE);
		
		# 	пытаемся выявить сразу тип запуска
		$execType=ToolExecType::code($_GET['execType']);
		
		# 	если нажата кнопка ГОУ - мы полюбому записываем стату, 
		# 	чтобы видеть, когда реально совершались попытки запуска, и контролить их фэйлы
		if($_REQUEST['go_btn'])
		{
			# 	начало создания отчёта
			$stat = new ToolStat();
			$stat->begin($toolType, $execType);
		}
		
		
		if($_GET[Core::CRON_KEY_1] == Core::CRON_VALUE_1)
		{
			if($execType)
			{
				echo '<h1>'.$toolType->name.'</h1>';
				echo 'Пересчёт количества объявлений по категориям / городам и тд.  <input type="button" onclick="if(confirm(\'Запустить?\')){location.href=location.href+\'&go_btn=1\';} " value="Запустить" />';
				
				if($_REQUEST['go_btn'])
				{
					ob_flush();
					flush();
					
					# 	ТУЛ
					$t = new Timer('блабла');
					AdvQuan::recacheAll();
					$t->stop();
					echo '<p>Завершено за '.$t->time.' сек.';
					# 	/ТУЛ
					
					# 	закрываем статистику выполнения
					$stat->success($text);
					
				} 
			}
			else
			{
				if($_REQUEST['go_btn'])
					$stat->fail('EXEC_TYPE_ERROR ['.$_REQUEST['execType'].']');
				echo 'EXEC_TYPE_ERROR. =(';
			}
		}
		else
		{
			if($_REQUEST['go_btn'])
				$stat->fail('CRON_SALT_ERROR');
			echo 'CRON_SALT_ERROR. =(';
		}
	}
	
	
	
	
	
	
	function setExpired()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
	
		
		# 	сразу обозначаем какой тул
		$toolType = ToolType::code(ToolType::SET_EXPIRED);
		
		# 	пытаемся выявить сразу тип запуска
		$execType=ToolExecType::code($_GET['execType']);
		
		# 	если нажата кнопка ГОУ - мы полюбому записываем стату,
		# 	чтобы видеть, когда реально совершались попытки запуска, и контролить их фэйлы
		if($_REQUEST['go_btn'])
		{
			# 	начало создания отчёта
			$stat = new ToolStat();
			$stat->begin($toolType, $execType);
		}
		
		
		if($_GET[Core::CRON_KEY_1] == Core::CRON_VALUE_1)
		{
			$execType=ToolExecType::code($_GET['execType']);
			if($execType)
			{
				$toolType = ToolType::code(ToolType::SET_EXPIRED);
				echo '<h1>'.$toolType->name.'</h1>';
				echo 'Выставление статуса ПРОСРОЧЕН  <input type="button" onclick="if(confirm(\'Запустить?\')){location.href=location.href+\'&go_btn=1\';} " value="Запустить" /><p>';
				
				$t = new Timer('');
				$list = AdvItem::getExpiredList();
				
				if($list)
				{
					$str.='Объявлений к блокировке: '.count($list).'';
					echo $str;
					if($_REQUEST['go_btn'])
					{
						foreach($list as $key=>$item)
						{
							echo '<br>'.($key+1).')  id: '.$item->id.'';
							ob_flush();
							flush();
							//usleep(200000);
							
							if($_REQUEST['go_btn'])
							{
								$item->setStatus(Status::code(Status::EXPIRED));
								echo ' - ok';
								ob_flush();
								flush();
								//usleep(200000);
							}
						}
					}
				}
				else
				{
					echo 'Объявлений нет.';
				}
				$t->stop();
				
				
				if($_REQUEST['go_btn'])
				{
					# 	закрываем статистику выполнения
					$stat->param1 = count($list);
					$stat->text = count($list) ? 'Проработано объявлений: '.count($list).'' : 'Объявлений для блокировки нет.';
					$stat->success();
					
					echo '<p>Завершено за '.$t->time.' сек.';
				}
			}
			else
			{
				if($_REQUEST['go_btn'])
					$stat->fail('EXEC_TYPE_ERROR ['.$_REQUEST['execType'].']');
				echo 'EXEC_TYPE_ERROR. =(';
			}
		}
		else
		{
			if($_REQUEST['go_btn'])
				$stat->fail('CRON_SALT_ERROR');
			echo 'CRON_SALT_ERROR. =(';
		}
	}
	
	
	
	
	
	
	
	
	
	# 	пересчёт кол-ва объявлений
	function getCurrency()
	{

	    return self::getCurrency2();

		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		$CORE->setLayout(null);
	
	
		# 	сразу обозначаем какой тул
		$toolType = ToolType::code(ToolType::GET_CURRENCY);
	
		# 	пытаемся выявить сразу тип запуска
		$execType=ToolExecType::code($_GET['execType']);
	
		# 	если нажата кнопка ГОУ - мы полюбому записываем стату,
		# 	чтобы видеть, когда реально совершались попытки запуска, и контролить их фэйлы
		if($_REQUEST['go_btn'])
		{
			# 	начало создания отчёта
			$stat = new ToolStat();
			$stat->begin($toolType, $execType);
		}
	
	
		if($_GET[Core::CRON_KEY_1] == Core::CRON_VALUE_1)
		{
			if($execType)
			{
				echo '<h1>'.$toolType->name.'</h1>';
				echo 'Получаем валюту с Yahoo 
						<br>url: <a href="'.Currency::CURRENCY_SITE_2.'" target="_blank">'.Currency::CURRENCY_SITE_2.'</a> <br><input type="button" onclick="if(confirm(\'Запустить?\')){location.href=location.href+\'&go_btn=1\';} " value="Запустить" />';
	
				if($_REQUEST['go_btn'])
				{
					ob_flush();
					flush();
						
					# 	ТУЛ
					$t = new Timer('блабла');
					
					/*// Get cURL resource
					$curl = curl_init();
					// Set some options - we are passing in a useragent too here
					curl_setopt_array($curl, array(
					    CURLOPT_RETURNTRANSFER => 1,
					    CURLOPT_URL =>  ('http://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22USDKZT,USDRUB%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='),
					    CURLOPT_USERAGENT => 'Chrome'
					));
					// Send the request & save response to $resp
					$res = curl_exec($curl);
					vd($res);
					// Close request to clear up some resources
					curl_close($curl);*/
									
					$res = file_get_contents(Currency::CURRENCY_SITE_2);
					//vd($res);
					
					
					if($res)
					{

						$res = json_decode($res);
						
						//vd($res->query->results->rate[0]->Ask);
						$tg = floatval($res->query->results->rate[0]->Ask);
						$rub = floatval($res->query->results->rate[1]->Ask);
						
						
						$tg += $_CONFIG['SETTINGS']['exchange_increment_'.Currency::KZT];
						$rub += $_CONFIG['SETTINGS']['exchange_increment_'.Currency::RUR];
						vd($_CONFIG['SETTINGS']);
						
						if($tg && $rub)
						{
							Settings::saveCurrency(array(
												Currency::KZT=>$tg, 
												Currency::RUR=>$rub,
											));
							$text = 'Успешно сохранено!<br>
									 <div>1 USD = '.$tg.' '.Currency::KZT.'</div>
									 <div>1 USD = '.$rub.' '.Currency::RUR.'</div>';
							$stat->success($text);
							echo '<p>'.$text;
						}
						else
						{
							$stat->fail('Сайт вернул некорректные значения.');
						}
					}
					else 
					{
					    echo 'Сайт вернул FALSE';
						$stat->fail('Сайт вернул FALSE');
					}

                    $t->stop();
					echo '<p>Завершено за '.$t->time.' сек.';
					# 	/ТУЛ
						
					# 	закрываем статистику выполнения
					
						
				}
			}
			else
			{
				if($_REQUEST['go_btn'])
					$stat->fail('EXEC_TYPE_ERROR ['.$_REQUEST['execType'].']');
					echo 'EXEC_TYPE_ERROR. =(';
			}
		}
		else
		{
			if($_REQUEST['go_btn'])
				$stat->fail('CRON_SALT_ERROR');
				echo 'CRON_SALT_ERROR. =(';
		}
	}






    # 	пересчёт кол-ва объявлений
    function getCurrency2()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::FRONTEND);
        $CORE->setLayout(null);


        # 	сразу обозначаем какой тул
        $toolType = ToolType::code(ToolType::GET_CURRENCY);

        # 	пытаемся выявить сразу тип запуска
        $execType=ToolExecType::code($_GET['execType']);

        # 	если нажата кнопка ГОУ - мы полюбому записываем стату,
        # 	чтобы видеть, когда реально совершались попытки запуска, и контролить их фэйлы
        if($_REQUEST['go_btn'])
        {
            # 	начало создания отчёта
            $stat = new ToolStat();
            $stat->begin($toolType, $execType);
        }


        if($_GET[Core::CRON_KEY_1] == Core::CRON_VALUE_1)
        {
            if($execType)
            {
                echo '<h1>'.$toolType->name.'</h1>';
                echo 'Получаем валюту с Yahoo 
						<br>url: <a href="'.Currency::CURRENCY_SITE_2.'" target="_blank">'.Currency::CURRENCY_SITE_2.'</a> <br><input type="button" onclick="if(confirm(\'Запустить?\')){location.href=location.href+\'&go_btn=1\';} " value="Запустить" />';

                if($_REQUEST['go_btn'])
                {
                    ob_flush();
                    flush();

                    # 	ТУЛ
                    $t = new Timer('блабла');

                    /*// Get cURL resource
                    $curl = curl_init();
                    // Set some options - we are passing in a useragent too here
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL =>  ('http://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22USDKZT,USDRUB%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback='),
                        CURLOPT_USERAGENT => 'Chrome'
                    ));
                    // Send the request & save response to $resp
                    $res = curl_exec($curl);
                    vd($res);
                    // Close request to clear up some resources
                    curl_close($curl);*/

                    $res = file_get_contents(Currency::CURRENCY_SITE_2);
                    //vd($res);


                    if($res)
                    {

                        $res = json_decode($res, true);

                        $tg = floatval($res['rates']['KZT']);
                        $rub = floatval($res['rates']['RUB']);


                        $tg += $_CONFIG['SETTINGS']['exchange_increment_'.Currency::KZT];
                        $rub += $_CONFIG['SETTINGS']['exchange_increment_'.Currency::RUR];

//                        vd($tg);
//                        vd($rub);

                        //vd($res->query->results->rate[0]->Ask);
//                        $tg = floatval($res->query->results->rate[0]->Ask);
//                        $rub = floatval($res->query->results->rate[1]->Ask);
//
//
//                        $tg += $_CONFIG['SETTINGS']['exchange_increment_'.Currency::KZT];
//                        $rub += $_CONFIG['SETTINGS']['exchange_increment_'.Currency::RUR];
////                        vd($_CONFIG['SETTINGS']);

                        if($tg && $rub)
                        {
                            Settings::saveCurrency(array(
                                Currency::KZT=>$tg,
                                Currency::RUR=>$rub,
                            ));
                            $text = 'Успешно сохранено!<br>
									 <div>1 USD = '.$tg.' '.Currency::KZT.'</div>
									 <div>1 USD = '.$rub.' '.Currency::RUR.'</div>';
                            $stat->success($text);
                            echo '<p>'.$text;
                        }
                        else
                        {
                            $stat->fail('Сайт вернул некорректные значения.');
                        }
                    }
                    else
                    {
                        echo 'Сайт вернул FALSE';
                        $stat->fail('Сайт вернул FALSE');
                    }

                    $t->stop();
                    echo '<p>Завершено за '.$t->time.' сек.';
                    # 	/ТУЛ

                    # 	закрываем статистику выполнения


                }
            }
            else
            {
                if($_REQUEST['go_btn'])
                    $stat->fail('EXEC_TYPE_ERROR ['.$_REQUEST['execType'].']');
                echo 'EXEC_TYPE_ERROR. =(';
            }
        }
        else
        {
            if($_REQUEST['go_btn'])
                $stat->fail('CRON_SALT_ERROR');
            echo 'CRON_SALT_ERROR. =(';
        }
    }
	
	
	
	
}




?>