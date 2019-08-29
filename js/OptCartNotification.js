OptCartNotification = {

    w: $('#notification'),
    isLoading: false,

    // default шаблон для уведомлений на сайте о неоплаченных заказах/резервах, о безналичном расчете.
    notifyDefaultTemplate: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0} unpaid-alert" role="alert" data-dismiss="100">' +
        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
        '<span class="logo"><img src="/assets/img/favicon-96x96.png" height="64">222</span>' +
        '<span class="message" data-notify="message">{2}</span>' +
        '<a href="{3}" target="{4}" data-notify="url"></a>' +
        '</div>',


    showBookmark: function(onOff){
        if(typeof onOff == 'undefined') onOff = true
        if(onOff){
            this.w.find('.bookmark').animate({width: 'show'}, 'fast');
        }
        else{
            this.w.find('.bookmark').animate({width: 'hide'}, 'fast');
        }
    },


    expand: function(){
        //alert($('.notification .main-block').css('display'))
        if(this.w.find('.main-block').is(':hidden')){     //  открытие
            this.w.find('.arr').css('display', 'none')
            this.w.find('.inf').css('display', 'none')
            this.w.find('.arr.close1').css('display', 'inline-block')
        }
        else{    //  закрытие
            this.w.find('.arr').css('display', 'none')
            this.w.find('.inf').css('display', 'inline-block')
        }
        this.w.find('.main-block').animate({width: 'toggle'}, 'fast');
    },


    show: function(){
        if(this.w.find('.main-block').is(':hidden')){     //  открытие
            OptCartNotification.expand()
        }
    },
    hide: function(){
        //  alert('HiDe!')
        if(this.w.find('.main-block').is(':visible')){     //  открытие
            OptCartNotification.expand()
        }
    },


    quake: function(){
        var q = this.w.find('.bookmark')
        q.stop(true, true);
        // $(q).css( 'background', '#FFEA00').animate( { backgroundColor: "#555" } , 600)
        $(q).css( 'background', '#FFEA00').animate( { backgroundColor: "#0669B3" } , 600)
    },


    setCartQuan: function(quan){
        this.w.find('.cart-quan').html(formatPrice(quan))
    },
    setCartSum: function(sum){
        this.w.find('.cart-sum').html(formatPrice(sum)+' тг')
    },



    loading: function(onOff){
        if(typeof onOff == 'undefined') onOff = true
        if(onOff){
            this.w.css('opacity', '.6')
            this.w.find('.not-loading').css('display', 'none')
            this.w.find('.loading').css('display', 'inline-block')
            this.isLoading = true
        }
        else{
            this.w.css('opacity', '1')
            this.w.find('.not-loading').css('display', 'inline-block')
            this.w.find('.loading').css('display', 'none')
            this.isLoading = false
        }
    },



    updateInfo: function(show){
        if(typeof show == 'undefined')
            show = false

        $.ajax({
            url:'/profile/getOptCartNotificationsJSON',
            dataType: 'json',
            beforeSend: function(){ OptCartNotification.loading(); OptCartNotification.showBookmark();  },
            complete: function(){OptCartNotification.loading(false); },
            success: function(data){
                if(data.cart.info.totalQuan == 0){
                    OptCartNotification.showBookmark(false)
                    OptCartNotification.hide()
                    return
                }
                OptCartNotification.quake()
                if(show)
                    OptCartNotification.show()
                OptCartNotification.setCartQuan(data.cart.info.totalQuan)
                OptCartNotification.setCartSum(data.cart.info.totalPrice)
            },
            error: function(){ /*alert('Возникла ошибка на сервере.. Пожалуйста, попробуйте позднее')*/ }
        })
    },





}



