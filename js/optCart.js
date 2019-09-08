
var OptCart = {

    COOKIE_CART_KEY: 'optCart',

    optStep: 0,
    ids: [],

    data: {},

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

            OptCart.data = ret

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




    Modal: {
        show: function(){
            // $('#optCartModal .modal-body').html($('#cartModalTmpl').html())
            $('#optCartModal').modal('show');

            this.drawCart()
        },

        drawCart: function(){

            return this.drawCart2()
            // var str = ''
            // str+=''
            //     +'<div class="cart">'
            //     +'    <div id="products">'
            //     +'        <div class="products-wrapper">'
            //     +'            <div class="block1 cart-individuals1">'
            //     +'                <div class="item">'
            //     +'                    <div class="kol title">'
            //     +'		<span class=" pic">'
            //     +'			<a href="/catalog/item/16_methandienone" target="_blank" title="Methandienone"><img src="/include/resize.slonne.php?img=../upload/images/catalogSimple/0641_5aed99019eaa0.jpg&amp;width=500" alt="Methandienone"></a>'
            //     +'		</span>'
            //     +'                        <a href="/catalog/item/16_methandienone" target="_blank">Methandienone</a>'
            //     +'                    </div>'
            //     +'                    <div class="kol price" style="line-height: 100%; ">'
            //     +'                        <span class="old-price">7 747,13 <span class="arial-narrow-tenge" style="font-weight: normal; ">т</span></span><br>'
            //     +'                        <b>5 422,99 <span class="arial-narrow-tenge" style="font-weight: normal; ">т</span></b>'
            //     +'                    </div>'
            //     +'                    <div class="kol quan">'
            //     +'                        <div class="val">'
            //     +'                            <a href="#" class="change-quan-btn" onclick="addQuan(16, -1); return false; " title="убрать">–</a>'
            //     +'                            ×3			<a href="#" class="change-quan-btn" onclick="addQuan(16, 1);return false; " title="добавить">+</a>'
            //     +'                        </div>'
            //     +'                    </div>'
            //     +'                    <div class="kol final-price" style="line-height: 100%; ">'
            //     +'                        <span class="old-price">23 241,39 <span class="arial-narrow-tenge" style="font-weight: normal; ">т</span></span><br>'
            //     +''
            //     +'                        <b>16 268,97 <span class="arial-narrow-tenge" style="font-weight: normal; ">т</span></b>'
            //     +'                    </div>'
            //     +'                    <div class="kol delete">'
            //     +'                        <a href="#delete" title="Убрать товар" onclick="if(confirm(\'Убрать товар из корзины?\')){addQuan(16, -3)}; return false; ">'
            //     +'                            <span class="word-delete"> <span style="font-size: 20px; font-weight: bold; line-height:50%; vertical-align: middle;  display: inline-block;   border: 0px solid red;   font-size: 30px;  ">×</span> <span style="display: inline-block; vertical-align: middle;">убрать из корзины</span></span>'
            //     +'                            <span class="x">×</span>'
            //     +'                        </a>'
            //     +'                    </div>'
            //     +'                </div>'
            //     +'                <hr>'
            //     +'                <div class="overall">'
            //     +'                    <div class="lbl">Итого товаров на сумму: </div>'
            //     +'                    <b>{{(cart.data.sum)}}</b>'
            //     +'                </div>'
            //     +'            </div>'
            //     +'        </div>'
            //     +'    </div>'
            //     +'</div>';
            //
            //
            //     $('#optCartModal .modal-body').html(str)
            //     return str
        },


        drawCart2: function(){
            //  скелет корзины
            $('#optCartModal .modal-body').html($('#cartTmpl').html())


            if(OptCart.data.quan > 0){
                //  втыкаем ряд
                for(var i in OptCart.ids){
                    // alert(OptCart.ProductsDict[i].name)
                    $('#optCartModal .modal-body .items').append( OptCart.Modal.HTML.product(i)/*$('#optCartProductRowTmpl').html()*/ )
                }
            }

            //  выставляем селекты
            for(var i in OptCart.ids){
                $('.quan-'+i).val(OptCart.ids[i])
            }

        },


        HTML: {
            product: function(id){
                var prod = OptCart.ProductsDict[id];
                var str = $('#optCartProductRowTmpl').html()

                str = str.replace(/_ID_/g, prod.id);
                str = str.replace(/_URL_PIECE_/g, prod.id);
                str = str.replace(/_NAME_/g, prod.name);
                str = str.replace(/_PHOTO_/g, prod.photo);
                // alert(str)

                // alert(OptCart.Product.price(id))

                var step = OptCart.step()

                var pricePrimeSingle = prod.price
                var priceOptSingle = step == 0 ? prod.price :  OptCart.ProductsDict[id].optPrices[step]
                var pricePrimeTotal = pricePrimeSingle * OptCart.ids[id]
                var priceOptTotal = priceOptSingle * OptCart.ids[id]

                str = str.replace(/_PRICE_PRIME_SINGLE_/g, formatPrice(Currency.calcPrice(pricePrimeSingle))+''+Currency.current.sign);
                str = str.replace(/_PRICE_OPT_SINGLE_/g, formatPrice(Currency.calcPrice(priceOptSingle))+''+Currency.current.sign);
                str = str.replace(/_PRICE_PRIME_TOTAL_/g, formatPrice(Currency.calcPrice(pricePrimeTotal))+''+Currency.current.sign);
                str = str.replace(/_PRICE_OPT_TOTAL_/g, formatPrice(Currency.calcPrice(priceOptTotal))+''+Currency.current.sign);


                var quanStr = $('#quansWrapperTmpl').html()
                quanStr = quanStr.replace(/_ID_/g, id);
                str = str.replace(/_QUAN_SECTION_/g, quanStr);



                // alert(step)
                // if(OptCart.)

                // var price = OptCart.ProductsDict[pr.id].optPrices[OptCart.OptStep.current]
                // ret.sumInCurrency += Currency.calcPrice(price)*quan

                return str
            },
        },


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