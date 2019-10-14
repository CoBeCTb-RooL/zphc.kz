var OptCart = {

    COOKIE_CART_KEY: 'optCart',

    ids: [],            //  инфа о корзине

    data: {},           //  текущее состояние корзины (суммы, количества и тд)

    settings: {},       //  настройки, типа ЦЕНЫ ДОСТАВКИ и тд

    Dict: {             //  словари
        products: [],
        deliveryTypes: [],
        paymentTypes: [],
    },

    formData: {},



    Product:{
        add: function(id, quan){
            if(typeof quan == 'undefined')
                quan=1
            if(typeof OptCart.ids[id] == 'undefined')
                OptCart.ids[id] = 0

            var newQuan = OptCart.ids[id] + quan
            this.setQuan(id, newQuan);
        },


        setQuan: function(id, quan)
        {
            OptCart.ids[id] = parseInt(quan)

            if(OptCart.ids[id] == 0){
                OptCart.UI.Table.showBtn(id)
                $('#cart-item-'+id).hide()     //  скрываем в корзине
            }

            OptCart.State.save()

            OptCart.Notificator.update()
            var step = OptCart.stepBySum()
            if(step != OptCart.step())
                OptCart.step(step)

            $('.form-wrapper').show()
            $('.overall-wrapper').show()

            if(OptCart.data.quan == 0){    //  если надо - скрываем корзину
                $('#optCartModal .modal-body .items').html('Корзина пуста.')
                $('.form-wrapper').slideUp('fast')
                $('.overall-wrapper').slideUp('fast')
            }

            SlonneDev.cart();
        },

        price: function(productId, step){
            step = step || 0
            // if(typeof step == 'undefined')
            //     step = 0
            alert(step)



        },

    },




    OptStep: {
        current: 0,
        steps: [],

        getStepBySum: function(sumInBucks){
            var step = 0;
            var sum = sumInBucks
            if(typeof sumInBucks == 'undefined')
                sum = OptCart.Calc.cartSum()
            if(sum < OptCart.OptStep.steps[0])
                return step;

            for(var i in OptCart.OptStep.steps){
                sum = OptCart.Calc.cartSum(OptCart.OptStep.steps[i])
                if(sum < OptCart.OptStep.steps[i])
                    return step;
                step = OptCart.OptStep.steps[i]
            }
            return step;
        },

        setStep: function(step){
            $('.product-price.price-step-'+OptCart.step()).stop().css('background', 'none').removeClass('current-step')

            OptCart.OptStep.current = step
            $('.product-price.price-step-'+step).addClass('current-step')
            $('.product-price.price-step-'+step).css( 'background', '#FFEA00').animate( { backgroundColor: "#0669B3" } , 600)

            OptCart.Notificator.update()
        },
    },



    UI:{
        Table: {
            init: function(){
                for(var i in OptCart.ids){
                    OptCart.UI.Table.showQuan(i);
                    OptCart.UI.Table.setValue(i, OptCart.ids[i])
                }
            },

            showQuan: function(id){
                var obj = $('.product-row-'+id)
                obj.find('.to-cart-btn-wrapper').slideUp('fast')
                obj.find('.quans-wrapper').slideDown('fast')
            },
            showBtn: function(id){
                var obj = $('.product-row-'+id)
                obj.find('.to-cart-btn-wrapper').slideDown('fast')
                obj.find('.quans-wrapper').slideUp('fast')
            },
            setValue: function(id, quan){
                // $('.product-row-'+id+' select').val(quan)
                $('.quan-'+id).val(quan)
            },
        },
    },



    State: {
        save: function(){
            var ts = Math.floor( Date.now() / 1000 )
            this.normalize()
            var obj = { ids: OptCart.ids, ts: ts,  }
            localStorage[this.COOKIE_CART_KEY] = JSON.stringify(obj);
            OptCart.UI.Table.init()
        },
        load: function(){
            obj = localStorage[this.COOKIE_CART_KEY] ? JSON.parse(localStorage[this.COOKIE_CART_KEY]) : {}
            alert(obj.ts)
            alert(Date.now() / 1000 )
            alert(Math.floor( Date.now() / 1000 ) - obj.ts)
            if(typeof obj.ts != 'undefined')
                if(Math.floor( Date.now() / 1000 ) - obj.ts > 10 /*3600*/){
                    localStorage[this.COOKIE_CART_KEY] = {}
                    obj = {}
                }
            OptCart.ids = obj.ids || {}
            this.normalize();

            OptCart.UI.Table.init()

            OptCart.step(OptCart.stepBySum())

            OptCart.Notificator.update()
        },
        normalize: function(){  //  вычистить несуществующие товары
            for(var i in OptCart.ids)
                if (typeof OptCart.Dict.products[i] == 'undefined' || OptCart.ids[i] <= 0)
                    delete OptCart.ids[i]
        },
    },





    Calc: {

        info: function(){
            var ret = {
                quan: 0,
                sum: 0,
                sumInCurrency: 0,
                sumStr: '',
                baseSum: 0,
                baseSumInCurrency: 0,
                baseSumStr: '',

            }

            for(var prId in OptCart.ids){
                var pr = OptCart.Dict.products[prId]
                var quan = OptCart.ids[prId]
                ret.quan += quan

                var price = pr.price

                ret.baseSumInCurrency +=  Currency.calcPrice(price)*quan

                if(OptCart.OptStep.current > 0 )
                    price = OptCart.Dict.products[pr.id].optPrices[OptCart.OptStep.current]

                ret.sumInCurrency += Currency.calcPrice(price)*quan
            }

            ret.baseSum = OptCart.Calc.cartSum(0)
            // ret.baseSumInCurrency = Currency.calcPrice(ret.baseSum)
            ret.baseSumStr = formatPrice(ret.baseSumInCurrency)+' '+Currency.current.sign

            ret.sum = OptCart.Calc.cartSum(OptCart.OptStep.current)
            // ret.sumInCurrency = Currency.calcPrice(ret.sum)
            ret.sumStr = formatPrice(ret.sumInCurrency)+' '+Currency.current.sign

            OptCart.data = ret

            return ret;
        },


        cartSum: function(step){
            if(typeof step == 'undefined')
                step = OptCart.OptStep.current
            // alert(step)

            ret = 0
            for(var prId in OptCart.ids){
                var pr = OptCart.Dict.products[prId]
                var quan = OptCart.ids[prId]

                var price = pr.price
                if(step > 0 )
                    price = OptCart.Dict.products[pr.id].optPrices[step]
                ret += price*quan
            }
            // alert(ret)

            return ret.toFixed(2)
        },

    },





    Notificator: {
        update: function(){
            OptCart.Notificator.setData(OptCart.Calc.info(), true)
        },

        setData: function(data, show)
        {
            // alert(Currency.calcPrice(OptCart.sum(0)))
            // alert(formatPrice(Currency.calcPrice(OptCart.sum(0))))
            show = show || false

            OptCartNotification.setCartQuan(data.quan)
            // alert(data.sumStr)
            var sumStr = data.sumStr
            if(OptCart.step() > 0){
                //sumStr = OptCart.sum(0)
                sumStr = '<span style="text-decoration: line-through; font-size: .9em; font-weight: normal; ">'+formatPrice(Currency.calcPrice(OptCart.sum(0)))+' '+Currency.current.sign+'</span><br>'
                        + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '
                        + sumStr
            }

            OptCartNotification.setCartSum(sumStr)
            if(data.quan == 0){
                OptCartNotification.showBookmark(false)
                OptCartNotification.hide()
            }
            else{
                OptCartNotification.showBookmark()
                OptCartNotification.quake()
                if(show)
                    OptCartNotification.show()
            }
        }
    },




    Modal: {
        show: function(){
            $('#optCartModal').modal('show');

            this.drawCart()
        },

        initModal: function(){
            OptCart.showSuccess(false)

            //  отрисовка товаров
            if(OptCart.data.quan > 0){
                $('#optCartModal .modal-body .items').empty()
                for(var i in OptCart.ids){
                    $('#optCartModal .modal-body .items').append( OptCart.Modal.HTML.product(i))
                    OptCart.Modal.ProductsTable.setProductRowInfo(i)
                }
            }
            else {  //  корзина пуста
                $('#optCartModal .modal-body .items').html('Корзина пуста.')
                $('.form-wrapper').slideUp('fast')
                $('.overall-wrapper').slideUp('fast')
            }
        },

        setTotalData: function(){
            //  выставляем селекты
            OptCart.Modal.ProductsTable.makeSelectsSet()

            //  рассчёт и вывод суммы ИТОГО за товары (пока без доставки)
            OptCart.Modal.ProductsTable.recountAndShowOverallPrice()

            //  выставление и рассчёт стоимости доставки при перерисовке
            if(typeof OptCart.formData != undefined && typeof OptCart.formData.deliveryType != undefined)
                OptCart.switchDeliveryType(OptCart.formData.deliveryType)

            OptCart.Modal.ProductsTable.showFinalPrice()
        },

        drawCart: function(){
            OptCart.Modal.initModal()
            OptCart.Modal.setTotalData()
        },

        ProductsTable: {
            makeSelectsSet: function(){
                for(var i in OptCart.ids){
                    $('.quan-'+i).val(OptCart.ids[i])
                }
            },

            recountAndShowOverallPrice: function(){
                var overallStr = $('#overallTmpl').html()
                overallStr = overallStr.replace(/_SUM_/g, OptCart.data.sumStr);
                $('#optCartModal .modal-body .overall-wrapper').html(  overallStr  )

                //  плюс ещё места, где нужно вывести цену
                $('.price-for-all-products').html(OptCart.data.sumStr)
                $('.price-primal-for-all-products').html(OptCart.data.baseSumStr)

            },

            showFinalPrice: function(){
                var str = ''
                var sumPrime = OptCart.data.baseSumInCurrency + Currency.calcPrice(OptCart.formData.deliveryCost)
                var sumFinal = OptCart.data.sumInCurrency + Currency.calcPrice(OptCart.formData.deliveryCost)
                if(sumPrime == sumFinal){
                    str = formatPrice(OptCart.data.sumInCurrency + Currency.calcPrice(OptCart.formData.deliveryCost))+' '+Currency.current.sign
                    $('.economy-info').html('')
                }
                else{
                    str = ''
                    //+'<span style="text-decoration: line-through; color: #665; font-size: .9em;   font-weight: normal; ">'+formatPrice(sumPrime)+' '+Currency.current.sign+'</span><br>'
                    + formatPrice(sumFinal)+' '+Currency.current.sign

                    $('.economy-info').html('Благодаря оптовому заказу вы экономите <b style="white-space: nowrap;">'+formatPrice(sumPrime - sumFinal)+' '+Currency.current.sign+'</b>')
                }
                $('.cart-price-final').html( str)
            },

            setProductRowInfo: function(productId){
                var $pr = $('#cart-item-'+productId)
                var prod = OptCart.Dict.products[productId];

                //  разруливаем с ценами
                var step = OptCart.step()
                var pricePrimeSingle = Currency.calcPrice(prod.price)
                var priceOptSingle = step == 0 ? pricePrimeSingle :  Currency.calcPrice(OptCart.Dict.products[productId].optPrices[step])
                var pricePrimeTotal = pricePrimeSingle * OptCart.ids[productId]
                var priceOptTotal = priceOptSingle * OptCart.ids[productId]

                var primeSingleStr = step>0 ? formatPrice(pricePrimeSingle)+' '+Currency.current.sign : ''
                var primeTotalStr = step>0 ? formatPrice(pricePrimeTotal)+' '+Currency.current.sign : ''

                $pr.find('.pricePrimeSingle').html(primeSingleStr + (priceOptSingle != pricePrimeSingle ? '<br>' : ''))
                $pr.find('.priceOptSingle').html(formatPrice(priceOptSingle)+' '+Currency.current.sign)
                $pr.find('.pricePrimeTotal').html(primeTotalStr + (priceOptTotal != pricePrimeTotal ? '<br>' : ''))
                $pr.find('.priceOptTotal').html(formatPrice(priceOptTotal)+' '+Currency.current.sign)
            },

            setQuan: function(id, quan){
                OptCart.Product.setQuan(id, quan);

                for(var i in OptCart.ids)
                    OptCart.Modal.ProductsTable.setProductRowInfo(i)
                OptCart.Modal.setTotalData()
                // OptCart.Modal.drawCart();
            },
            add: function(id, quan){
                quan = quan || 1
                OptCart.Modal.ProductsTable.setQuan(id, OptCart.ids[id] + quan)
            },
        },


        HTML: {
            product: function(id){
                var prod = OptCart.Dict.products[id];
                var str = $('#optCartProductRowTmpl').html()

                str = str.replace(/_ID_/g, prod.id);
                str = str.replace(/_URL_PIECE_/g, prod.id);
                str = str.replace(/_NAME_/g, prod.name);
                str = str.replace(/_PHOTO_/g, prod.photo);

                return str
            },
        },


    },




    switchPaymentType: function(type){
        OptCart.formData.paymentType = type
        var p = OptCart.Dict.paymentTypes[type]
        $('.order-info .paymentType .val').html('<img  src="'+p.icon+'" alt=""  width="58" /> '+p.name)
        $('.order-info .paymentType').slideDown('fast');

        //  успешный заказ
        $('.requisites .item').hide()
        $('.requisites .payment-type-lbl').html(p.name)
        $('.requisites #'+type).show()
    },
    switchDeliveryType: function(type){
        OptCart.formData.deliveryType = type
        OptCart.formData.deliveryCost = 0
        var p = OptCart.Dict.deliveryTypes[type]
        if(typeof p == 'undefined')
            return
        var str = p.name

        if(type != 'pickup'){
            if(OptCart.data.sum < OptCart.settings.orderSumForFreeDelivery) {
                str += '<br><span style="font-weight: normal; "> (' + formatPrice(Currency.calcPrice(OptCart.settings.deliveryCost)) + ' ' + Currency.current.sign + ')</span>'
                OptCart.formData.deliveryCost = OptCart.settings.deliveryCost
            }
            else
                str+='<br><span style="font-weight: normal; "> (Бесплатно)</span>'
        }

        $('.order-info .deliveryType .val').html(str)
        $('.order-info .deliveryType').slideDown('fast');

        OptCart.Modal.ProductsTable.showFinalPrice()
    },




    sendOrder: function(){

        var x = $('.cart form').serializeArray()

        //  сериализованную хрень приводим к просто объекту
        var a = {}
        $.each(x, function(i, field){
            a[field.name] = field.value
        });

        //  избавляемся от вонючих пробелов массивов жс
        a.products = []
        $.each(OptCart.ids, function(key, val){
            if(typeof val != 'undefined')
                a.products.push({id: key, quan: val})
        })

        $('.order-info .error').css('display', 'none')
        $('.customer-info *.field-error').removeClass('field-error')

        // SlonneDev.console.text(vd(a))
        // console.log(a)

        $.ajax({
            url: '/optCart/submit',
            data: a,
            dataType: 'json',
            beforeSend: function(){ $('.cart .loading').slideDown('fast'); $('#send-order-btn').attr('disabled', 'disabled') },
            complete: function(){$('.cart .loading').slideUp('fast'); $('#send-order-btn').removeAttr('disabled') },
            success: function(data){
                if(!data.errors){
                    OptCart.clear()
                    OptCart.showSuccess()
                }
                else{
                    OptCart.showErrors(data.errors)
                }
            },
            error: function (){},
        })
    },


    showErrors: function(errors){
        $('.order-info .error').html(getErrorsString(errors)).slideDown('fast')
    },

    showSuccess: function(isOn){
        if(typeof isOn == 'undefined')
            isOn = true
        if(isOn) {
            $('.cart form').slideUp()
            $('.cart .success1').slideDown()
        }
        else{
            $('.cart form').slideDown()
            $('.cart .success1').slideUp()
        }
    },




    /////////////////////////////////////////////
    sum: function(step){
        return OptCart.Calc.cartSum(step)
    },
    stepBySum: function(sum){
        return OptCart.OptStep.getStepBySum(sum)
    },
    step: function(step){
        if(typeof step == 'undefined')
            return OptCart.OptStep.current
        else
            OptCart.OptStep.setStep(step)
    },



    clear: function(){
        for(var i in OptCart.ids){
            OptCart.Product.setQuan(i, 0)
        }
    },





}