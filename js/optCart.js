
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

            OptCart.ids[id] = OptCart.ids[id] + quan
            if(OptCart.ids[id] == 0)
                OptCart.UI.Table.showBtn(id)

            OptCart.State.save()

            SlonneDev.cart();
        },

        setQuan: function(id, quan)
        {
            OptCart.ids[id] = parseInt(quan)
            OptCart.State.save()

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



}