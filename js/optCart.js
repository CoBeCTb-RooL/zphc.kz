
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

            SlonneDev.cart();
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
        },
        normalize: function(){  //  вычистить несуществующие товары
            for(var i in OptCart.ids)
                if (typeof OptCart.ProductsDict[i] == 'undefined' || OptCart.ids[i] <= 0)
                    delete OptCart.ids[i]
        },
    },





    Calc: {

        info: function(){
            var ret = { quan: 0, sum: 0, sumStr: '', baseSum: 0, baseSumStr: '', }

            for(var prId in OptCart.ids){
                // var pr = OptCart.ProductsDict[prId]
                var quan = OptCart.ids[prId]
                ret.quan += quan

                // var basePrice = pr.price
                // var price = basePrice
                // if(OptCart.optStep > 0 )
                //     price = OptCart.ProductsDict[pr.id].optPrices[OptCart.optStep]
                // // alert(pr.price)
                // ret.baseSum += basePrice*quan
                // ret.sum += price*quan
            }

            ret.baseSum = OptCart.Calc.cartSum(0)
            ret.baseSumStr = formatPrice(ret.baseSum)+' $'

            ret.sum = OptCart.Calc.cartSum(OptCart.optStep)
            ret.sumStr = formatPrice(ret.sum)+' $'

            return ret;
        },


        cartSum: function(step){
            if(typeof step == 'undefined')
                step=0
            ret = 0
            for(var prId in OptCart.ids){
                var pr = OptCart.ProductsDict[prId]
                var quan = OptCart.ids[prId]

                var price = pr.price
                if(OptCart.optStep > 0 )
                    price = OptCart.ProductsDict[pr.id].optPrices[OptCart.optStep]
                ret += price*quan
            }

            return ret
        },

    },





    Notificator: {
        update: function(){
            OptCart.Notificator.setData(OptCart.Calc.info(), true)
        },

        setData: function(data, show)
        {
            show = show || false

            OptCartNotification.setCartQuan(data.quan)
            OptCartNotification.setCartSum(data.sumStr)
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




    setStep: function(step){
        $('.product-price.price-step-'+OptCart.optStep).stop().css('background', 'none').removeClass('current-step')

        OptCart.optStep = step
        $('.product-price.price-step-'+step).addClass('current-step')
        $('.product-price.price-step-'+step).css( 'background', '#FFEA00').animate( { backgroundColor: "#0669B3" } , 600)

        OptCart.Notificator.update()
    },




}