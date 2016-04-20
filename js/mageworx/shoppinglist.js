if (!window.ShoppingList) var ShoppingList = new Object();

ShoppingList.Methods = {
    title: 'Add to',
    width: 450,
    overlay: false,
    overlayClose: false, 
    autoFocusing: false,
  
    init: function(options) {
        Object.extend(this, options || {});
    },
  
    updateLinks: function () {
        var links = $$('a');
        //first_wishlist_flag = false;        
        links.each( function (link) {
            if(link.href.indexOf('ShoppingList.')==-1) {
                if (link.href.indexOf('/wishlist/index/add/') > 0){
                    $(link).writeAttribute('onclick', 'ShoppingList.showLists(\''+link.href+'\'); return false;');
                    $(link).writeAttribute('href', 'javascript:ShoppingList.showLists("'+link.href+'");');
                } 
//                else if (first_wishlist_flag==false && link.href.substr(link.href.length-10, 10)=="/wishlist/") {                
//                    $(link).writeAttribute('onclick', 'ShoppingList.showMenu(event); return false;');
//                    $(link).writeAttribute('href', 'javascript:ShoppingList.showMenu(event);');
//                    first_wishlist_flag = true;
//                }
            }    
        });          
    },
    
    fade:function(el) {        
        el.fade({
            duration:0.3,
            from:1,
            to:0.2
        });        
        el.style.backgroundImage = 'url(/skin/frontend/default/default/css/mageworx/spinner.gif)';
        el.style.backgroundRepeat = 'no-repeat';
        el.style.backgroundPosition = 'center center';
    },
    
    showLists: function(url){        
        form = $('product_addtocart_form');
        if (form) params = form.serialize(); else params = '';
        
        if (url.length>7) {        
            url = url.replace('/wishlist/index/add', '/shoppinglist/index/showLists');
            if ($('qty') && $('qty').value!="") qty=$('qty').value; else qty=1;
            url+='qty/'+qty+'/';
        } else {
            url = form.action;
        }        
        this.open(url + '?dialog_mode=1', {params: params});
    },
    
    showMenu: function(event){        
        $('popup_menu').style.display = 'block';
	$('popup_menu').style.top = event.pageY-15 +"px";
	$('popup_menu').style.left = event.pageX +"px";	
    },
    
    
    open: function(url, params){
        url=this.checkProtocol(url, params);
	Modalbox.setOptions({title: this.title, width: this.width, overlay: this.overlay, overlayClose: this.overlayClose, autoFocusing: this.autoFocusing});
        Modalbox.show(url, params);
    },
  
    checkProtocol:function(url, params) {
        if (window.location.protocol == 'https:') {        
            return url.replace('http://', 'https://');
        } else {            
            return url.replace('https://', 'http://');
        }
    },    
  
    submitForm: function(form, method) {           
        this.open(form.action, {
            params: form.serialize(), 
            method: method
        });
    },  
  
    close: function(){
        Modalbox.hide({
            transitions: true
        });
    },    
    
    updateBlock:function(elements,json){
        try{
            elements.update(json);
            this._eval(json);
        }catch(e){}
    },
    
    setPLocation:function(url,setFocus){
        if(url.indexOf('/wishlist/index/add/') > 0){
            url = url.replace('/wishlist/index/add', '/shoppinglist/index/showLists');
            this.open(url, this.cart);
        } else{
            if(setFocus){
                window.opener.focus();
            }
            window.opener.location.href=url;
        }
    }    

};

Object.extend(ShoppingList, ShoppingList.Methods);
