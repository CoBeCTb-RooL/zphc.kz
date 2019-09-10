<?php
class OptCart{

    public $ids;
    public $step;
    public $info;
    public $currency;
    public $productsDict;


    function __construct($ids)
    {
        global $_GLOBALS;

        $this->ids = $ids;

        $this->currency = $_GLOBALS['currency'];

        # 	инициализируем все товары корзины
        $this->productsDict = ProductSimple::getList(array(
            'status' => Status::code(Status::ACTIVE),
            'ids' => array_keys($this->ids),
        ));
        foreach ($this->productsDict as $v)
            $v->initOptPrices();


        $this->info['totalSumPrimal'] = $this->sum(0, Currency::code(Currency::USD));
        $this->info['totalSumPrimalInCurrency'] = $this->sum();

        $this->step = $this->calcStep();

        $this->info['totalSumFinal'] = $this->sum($this->step, Currency::code(Currency::USD));
        $this->info['totalSumFinalInCurrency'] = $this->sum($this->step);

        #   считаем количество
        foreach ($this->ids as $id=>$quan)
            $this->info['quan'] += $quan;


        #   формируем OrderItems
        foreach ($this->ids as $id=>$quan)
        {
            $prod = $this->productsDict[$id];

            //  фиксируем скидочную стоимость товара
            $prod->discountPrice = $this->step ? $prod->optPricesArr[$this->step] : $prod->price;

            $discount = null;
            $productRelationType = null;
            $param1 = null;

            $this->orderItems[] = new OrderItem(
                $prod,
                $productRelationType,
                $quan,
                $discount,
                $param1
            );
        }
//        vd($this->orderItems);
    }



    function sum($step=0, $currency=null)
    {
        $currency = $currency ? $currency : $this->currency;
        $sum = 0;
        foreach ($this->ids as $id=>$quan)
        {
            $prod = $this->productsDict[$id];
            $price = $prod->price;
//            vd($price);
            if($step)
                $price = $prod->optPricesArr[$step];
            $sum += round(Currency::calculatePrice($price, $currency), 2) * $quan;
        }

        return $sum;
    }


    function calcStep()
    {
        $step = 0;
        foreach (ProductSimple::$optPrices as $price=>$v)
        {
            if($v)
            {
    //            echo '--'.$price.'--';
                $tmp = $this->sum($price, Currency::code(Currency::USD));
    //            vd($tmp);
                if($tmp < $price)
                    return $step;
                $step = $price;
            }
        }

        return $step;
    }


}