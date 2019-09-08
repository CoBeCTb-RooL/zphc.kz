
var OptCart = {

    COOKIE_CART_KEY: 'optCart',

    optStep: 0,
    ids: [],

    ProductsDict: {},



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
            if(OptCart.ids[id] == 0)
                OptCart.UI.Table.showBtn(id)

            OptCart.State.save()

            OptCart.Notificator.update()
            var step = OptCart.stepBySum()
            if(step != OptCart.step())
                OptCart.step(step)
            // alert(step+' = '+ OptCart.Calc.cartSum(step))

            SlonneDev.cart();
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
                $('.product-row-'+id+' select').val(quan)
            },
        },
    },



    State: {
        save: function(){
            this.normalize()
            var obj = { ids: OptCart.ids, }
            localStorage[this.COOKIE_CART_KEY] = JSON.stringify(obj);
            OptCart.UI.Table.init()
        },
        load: function(){
            obj = localStorage[this.COOKIE_CART_KEY] ? JSON.parse(localStorage[this.COOKIE_CART_KEY]) : {}
            OptCart.ids = obj.ids || {}
            this.normalize();

            OptCart.UI.Table.init()

            OptCart.step(OptCart.stepBySum())

            OptCart.Notificator.update()
        },
        normalize: function(){  //  вычистить несуществующие товары
            for(var i in OptCart.ids)
                if (typeof OptCart.ProductsDict[i] == 'undefined' || OptCart.ids[i] <= 0)
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
                var pr = OptCart.ProductsDict[prId]
                var quan = OptCart.ids[prId]
                ret.quan += quan

                var price = pr.price

                ret.baseSumInCurrency +=  Currency.calcPrice(price)*quan

                if(OptCart.OptStep.current > 0 )
                    price = OptCart.ProductsDict[pr.id].optPrices[OptCart.OptStep.current]

                ret.sumInCurrency += Currency.calcPrice(price)*quan
            }

            ret.baseSum = OptCart.Calc.cartSum(0)
            // ret.baseSumInCurrency = Currency.calcPrice(ret.baseSum)
            ret.baseSumStr = formatPrice(ret.baseSumInCurrency)+' '+Currency.current.sign

            ret.sum = OptCart.Calc.cartSum(OptCart.OptStep.current)
            // ret.sumInCurrency = Currency.calcPrice(ret.sum)
            ret.sumStr = formatPrice(ret.sumInCurrency)+' '+Currency.current.sign

            return ret;
        },


        cartSum: function(step){
            if(typeof step == 'undefined')
                step = OptCart.OptStep.current
            // alert(step)

            ret = 0
            for(var prId in OptCart.ids){
                var pr = OptCart.ProductsDict[prId]
                var quan = OptCart.ids[prId]

                var price = pr.price
                if(step > 0 )
                    price = OptCart.ProductsDict[pr.id].optPrices[step]
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
                sumStr = '<span style="text-decoration: line-through; font-size: .9em; font-weight: normal; ">'+formatPrice(Currency.calcPrice(OptCart.sum(0)))+''+Currency.current.sign+'</span><br>'
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







}