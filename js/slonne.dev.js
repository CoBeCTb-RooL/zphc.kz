var SlonneDev = {

    w: $('.dev-panel'),     //  самый верхний враппер

    //  само окно консоли (чёрное)
    console: {
        w: $('.dev-panel').find('.data'),
        show: function(){$(this.w).slideDown('fast'); },
        hide: function(){$(this.w).slideUp('fast');},
        toggle: function(){
            if(this.isVisible())
                this.hide()
            else
                this.show()
        },
        isVisible: function(){return $(this.w).is(':visible')},
        text: function(txt){ $(this.w).html(txt) ; },
        clear: function(){this.text('')},
        vd: function(obj){ this.text('<pre11>'+vd(obj)+'</pre11>') }
    },



    //  короткие методы
    cookie: function(){this.console.vd(document.cookie)},

    cart: function(){
            
        this.console.show();
    },

}

