var SlonneDev = {

    w: $('.dev-panel'),     //  самый верхний враппер

    //  само окно консоли (чёрное)
    console: {
        w: $('.dev-panel').find('.data'),
        show: function(){/*$('.dev-panel').find('.data').slideDown('fast');*/$('.dev-panel').find('.data').slideDown('fast') },
        hide: function(){/*$('.dev-panel').find('.data').slideUp('fast');*/$('.dev-panel').find('.data').slideUp('fast')},
        toggle: function(){
            // $('.dev-panel').find('.data').slideDown();
            if(this.isVisible())
                this.hide()
            else
                this.show()
        },
        isVisible: function(){return $('.dev-panel').find('.data').is(':visible')},
        text: function(txt){ $('.dev-panel').find('.data').html(txt) ; },
        clear: function(){this.text('')},
        vd: function(obj){ this.text('<pre11>'+vd(obj)+'</pre11>') }
    },



    //  короткие методы
    cookie: function(){this.console.vd(document.cookie)},
    cart: function(){
        Cart2.load();
        //Cart2.UI.updateNotificationBox(Cart2.Calc.stat(), true)
        var txt = ''
            + 'oRG iNFo: <br>' + vd(Cart2.org)
            +'<br>-------------------------------------<br>'


        txt+=''
            + 'TiMeR: <br>'
        //+'&nbsp;&nbsp;&nbsp;TS: '+Cart2.CartTimer.get()+' s<br>'
        if(Cart2.CartTimer.get())
            txt+=''
                //+'&nbsp;&nbsp;&nbsp;iDLe: '+Cart2.CartTimer.idleTime()+' s <br>'
                +'&nbsp;&nbsp;&nbsp;eXPiReS iN: '+Cart2.CartTimer.expiresIn()+' s <br>'
                +'&nbsp;&nbsp;&nbsp;iS eXPiReD: '+(Cart2.CartTimer.isExpired() ? "<span style='color: red; font-weight: bold'>TRUE</span>" : "false")  +'  <br>'

        txt+=''
            +'<br>-------------------------------------<br>'
            +'PRoDuCTs:<br>' + vd(Cart2.products)
            +'<br>-------------------------------------<br>'
            +'PRoDuCTs iNFo:<br>' + vd(Cart2.productsDict)

        this.console.text(txt)
        //this.console.show();
    },

}

