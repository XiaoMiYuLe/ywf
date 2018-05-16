
if (!window['zeed']) {
    window['zeed'] = {};
}

zeed.configVars = {
    'loadBase': '/static/scripts/',
    'nocache': false,
    'version': '0.1'
};

zeed.loadedFiles = new Array();

/**
 * 载入一个JS文件
 * 
 * @todo OPTIONS配置结构化
 * @param {String} name
 * @param {String} ver
 * @param {Object} options
 */
zeed.load = function(name, ver, options){
    if (name.match(new RegExp("^(\/|http:\/\/|https:\/\/)", "i"))) {
        var url = name;
    } else {
        if (typeof ver == 'string' || typeof ver == 'number') {
            var ver = ver + '/';
        } else {
            var ver = '';
        }
        if (typeof options == 'object') {
            if (options.uncompressed == true) {
                var postfix = '.js';
            } else {
                var postfix = '.min.js';
            }
        } else {
            var postfix = '.min.js';
        }
        var url = zeed.configVars['loadBase'] + name + '/' + ver + name.replace('jquery-', 'jquery.') + postfix;
    }
    
    this.loadFile(url, 'js', options);
}

/**
 * 加载一个JS/CSS文件
 * 
 * @param {String} url
 * @param {String} type 可以选择JS或CSS
 */
zeed.loadfile = zeed.loadFile = function(url, type, options){
    if (typeof url == 'undefined') {
        return;
    } else if (zeed.loadedFiles.inArray(url)) {
        return;
    }
    url = url + '?build=' + zeed.configVars['version'];
    
    if (typeof type == 'undefined') {
        var ext = url.split(".").pop().toString().toLowerCase();
        switch (ext) {
            case 'js':
                type = 'script';
                break;
            case 'css':
                type = 'link';
                break;
            default:
                type = 'script';
        }
    } else {
        type = (type.toLowerCase() == 'css') ? 'link' : 'script';
    }
    
    if (typeof options == 'object') {
        var nocache = options.nocache ? true : false;
    } else {
        var nocache = false;
    }
    
    var ss = document.getElementsByTagName(type);
    for (i = 0; i < ss.length; i++) {
        if (ss[i].src && ss[i].src.indexOf(url) != -1) {
            zeed.onsuccess();
            return;
        }
    }
    
    if (type == 'link') {
        s = document.createElement("link");
        s.rel = "stylesheet";
        s.type = "text/css";
        s.href = nocache ? url + '&rand=' + Math.random() : url;
        s.disabled = false;
    } else {
        s = document.createElement('script');
        s.type = "text/javascript";
        s.src = nocache ? url + '&rand=' + Math.random() : url;
    }
    
    var head = document.getElementsByTagName("head")[0] ? document.getElementsByTagName("head")[0] : document.body.parentNode.appendChild(document.createElement("head"));
    head.appendChild(s);
    
    /**
     * 判断是否载入成功: IE, 使用readystatechange事件 其它, 使用onload事件
     */
    s.onload = s.onreadystatechange = function(){
        // 在此函数中this指针指的是s结点对象
        if (this.readyState && this.readyState == "loading") 
            return;
        zeed.onsuccess(url);
    };
    s.onerror = function(){
        head.removeChild(s);
        zeed.onfailure(url);
    };
};

// 定义载入成功事件
zeed.onsuccess = function(url){
    zeed.loadedFiles.push(url);
    return;
};
// 定义失败事件
zeed.onfailure = function(url){
};

/**
 * 检查一个URL文件是否已经被加载
 * 
 * @param string $url
 * @return boolean
 */
zeed.loaded = function(url) {
    if (zeed.loadedFiles.inArray(url)) {
        return true;
    }
    return false;
}

/**
 * 设置COOKIE
 * 
 * @param {String} name
 * @param {String} value
 * @param {Object} options
 */
zeed.cookie = function(key, value, options) {
    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);

        if (value === null || value === undefined) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

if (window['jQuery']) {
	jQuery.cookie = zeed.cookie;
}

/**
 * Checks if a value exists in an array
 * 
 * @param mixed $needle
 * @return boolean
 */
Array.prototype.inArray = function($needle) {
    for(var i = 0 ; i < this.length; i++){
        if(this[i] == $needle){
            return true;
        }
    }
    return false;
}
