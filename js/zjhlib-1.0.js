if(!''.trim){
	String.prototype.trim = function(){
		return this.replace(/^\s+|\s+$/g,'');
	};
}
if(![].forEach){
	Array.prototype.forEach = function(fn){
		var that = this;
		for(var i = 0,len = that.length;i<len;i++){
			fn.call(null,that[i],i);
		}
	};
}
(function(window,undefined){

	var zjh = {};

	zjh.body = document.body;
	zjh.html = document.documentElement;

	zjh.get = function(el){
		return typeof el === 'string' ? document.getElementById(el):el;
	};
	zjh.json = function(str){
		return eval("("+str+")");
	};
	//浏览器可视区域高度
	zjh.getViewport = function(){
		return {
			width:document.documentElement.clientWidth,
			height:document.documentElement.clientHeight
		}
	};
	//带borderWidth的文档高度，不包含与box周围的距离
	zjh.getPagearea = function(){
		//带borderWidth,不带borderWidth是document.body.clientHeight;
		return {
			height:document.documentElement.offsetHeight,
			width:document.documentElement.offsetWidth
		};
	};
	zjh.screen = function(){
		return {
			height:window.screen.height,
			width:window.screen.width
		};
	};
	zjh.css = function(el,attr,value){
		if(zjh.isObject(attr)){
			for(at in attr){
				zjh.css(el,at,attr[at]);
			}
		}else if(typeof value != 'undefined'){
			el.style[attr] = value;
		}else{
			var style = el.currentStyle ? el.currentStyle : window.getComputedStyle(el);
			if(style.getPropertyValue){
			    return style.getPropertyValue(attr);
			}else{
			    return style.getAttribute(attr);
			}
		}
	};

	zjh.animate = function(el,attr,fn,val){
	    var arg = arguments;

	    if(Object.prototype.toString.call(attr) === '[object Object]'){
	       for(key in attr){
	         arg.callee(el,key,fn,attr[key]);
	       }
	    }else{
	        el = zjh.get(el);
	        attr = attr.replace(/([A-Z])/g,'-$1').toLowerCase();
	        target = parseInt(val);

	        var original = parseInt(zjh.css(el,attr)),
	          far      = target-original,
	          step     = far/20,
	          now      = 0;
	        //单位 px,em .etc
	        var suffix = val.match(/[a-z]+$/i);
	        suffix === null ? 0 : suffix[0];

	        void function(){
	           if(Math.abs(far)-Math.abs(step) < Math.abs(now)){
	              el.style[attr] = target+suffix;
	              if(Object.prototype.toString.call(fn) === '[object Function]'){
	                fn.call();
	              }
	           }else{
	              el.style[attr] = original+now+suffix;
	              now = now+step;
	              setTimeout(arguments.callee,10);
	           }
	        }();
	    }
	};

	zjh.hasClass = function(el,clazz){
		el = zjh.get(el);
		var className = el.className;
		if(className === ''){
			return false;
		}
		var arr = className.split(' ');
		for(var i = 0,len = arr.length;i < len;i++){
			if(arr[i] == clazz) return true;
		}
		return false;
	};

	zjh.addClass = function(el,clazz){
		el = zjh.get(el);
		if(!zjh.hasClass(el,clazz)){
			el.className = el.className+' '+clazz;
		}
	};

	zjh.removeClass = function(el,clazz){
		var className = ' '+el.className+' ';
		el.className = className.replace(new RegExp('\\s'+clazz+'\\s'),'');
	};

	zjh.addEvent = function(el,type,fn){
		el = zjh.get(el);
		if(el.attachEvent){
	    	el.attachEvent('on'+type,fn);
		}else{
		    el.addEventListener(type,fn,false);
		}
	};

	zjh.removeEvent = function(el,type,fn){
		el = zjh.get(el);
		if(el.detachEvent){
		    el.detachEvent('on'+type,fn);
		}else{
		    el.removeEventListener(type,fn,false);
		}
	};

	//文档滚动到指定位置
	zjh.scrollTo = function(position,rate){

		var toz = document.documentElement.scrollTop || document.body.scrollTop,
			target = position ? position === 'top' ? 0 : position === 'bottom' ? document.body.scrollHeight : parseInt(position) : 0,
			far = target - toz,
			now = 0,
			setId,
			step = far/(rate ? parseInt(rate) : 20);

		// console.log(toz,target,far,now,step);
		void function(){
			if(step === 0) return;
			if(far <= 0 ? toz + now <= target : target-now <= step){
				window.scrollTo(0,target);
				// console.log('target',target);
			}else{
				window.scrollTo(0,toz+now);
				now = now + step;
				// console.log(toz+now,target-now);
				setId = setTimeout(arguments.callee,10);
			}
		}();

	};
	zjh.isFunction = function(fn){
		return Object.prototype.toString.call(fn) === '[object Function]';
	};
	zjh.isArray = function(array){
		return Object.prototype.toString.call(array) === '[object Array]';
	};
	zjh.isObject = function(object){
		return Object.prototype.toString.call(object) === '[object Object]';
	};
	zjh.isString = function(str){
		return Object.prototype.toString.call(str) === '[object String]';
	};
	zjh.hide = function(ele){
		ele.style.display = 'none';
	};
	zjh.show = function(ele){
		ele.style.display = 'block';
	};
	zjh.ajax = function(method,url,params,fn,async){

        async = typeof async === "undefined" ? true:async;
        method = method.toUpperCase();

        var xmlhttp,
        	param = '';

        if(window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest();
        }else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function(){
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                if(typeof fn === "function") fn(xmlhttp.responseText);
            }
        };
        for(p in params){
        	param = param + "&" + p + "=" + params[p];
        }
       	param = param.slice(1)+"&random="+Math.random();

       	if(method === "GET"){
       		xmlhttp.open(method,url+"?"+param,async);
       		xmlhttp.send();
       	}else if(method === "POST"){
       		xmlhttp.open(method,url,async);
       		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
       		xmlhttp.send(param);
       	}else{
       		if(window.console) console.log('未知的方法：'+method);
       		return false;
       	}
    };
    zjh.GET = function(url,params,fn,async){
    	zjh.ajax('GET',url,params,fn,async);
    };
    zjh.POST = function(url,params,fn,async){
    	zjh.ajax("POST",url,params,fn,async);
    };
    zjh.pop = function(title,content,width,height){
    	var popShadow = document.createElement('div'),
    		closeDiv = document.createElement('b'),
    		wrap = document.createElement('div'),
    		pop = document.createElement('div');

    	popShadow.setAttribute('id','pop-shadow');
    	pop.setAttribute('id','layer-pop');
    	closeDiv.setAttribute('id','pop-close');
    	closeDiv.innerHTML = '×';
    	zjh.addClass(wrap,'wrap');
    	title = "<h2>"+title+"</h2>";

    	closeDiv.onclick = function(){
    		// zjh.body.removeChild(popShadow);
    		// zjh.body.removeChild(pop);
    		document.body.removeChild(popShadow);
    		// document.body.removeChild(pop);
    		if(typeof content == 'object'){
    			zjh.hide(content);
    			document.body.appendChild(content);
    		}
    		document.body.removeChild(pop);
    	};

    	if(width) zjh.css(pop,{
    		'width':width+"px",
    		'height':height+"px",
    		'margin-left':-width/2+"px",
    		'margin-top':-height/2+"px"
    	});

    	pop.innerHTML = title;
    	
    	if(typeof content == 'object'){
    		// var copyContent = content.cloneNode(true);
    		// zjh.show(copyContent);
    		// pop.appendChild(copyContent);
    		zjh.show(content);
    		pop.appendChild(content);
    	}else{
    		wrap.innerHTML = content;
    	}
    	// zjh.addClass(wrap,'tc');
    	pop.appendChild(wrap);
    	document.body.appendChild(popShadow);
    	document.body.appendChild(pop);
    	pop.appendChild(closeDiv);
    };
	window.zjh = zjh;
})(window);