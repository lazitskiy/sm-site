/**
* jQuery Cookie plugin
*
* Copyright (c) 2010 Klaus Hartl (stilbuero.de)
* Dual licensed under the MIT and GPL licenses:
* http://www.opensource.org/licenses/mit-license.php
* http://www.gnu.org/licenses/gpl.html
*
*/
jQuery.cookie = function (key, value, options) {

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



/* Copyright (c) 2008 Kean Loong Tan http://www.gimiti.com/kltan
* Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
* Copyright notice and license must remain intact for legal use
* jHelpertip
* Version: 1.0 (Jun 2, 2008)
* Requires: jQuery 1.2+
*/

/* modification of Isaak Tyngylchav http://erum.ru
*/

(function($) {

    $.fn.jHelperTip = function(options) {
        // merge users option with default options
        var opts = $.extend({}, $.fn.jHelperTip.defaults, options);

        var containerBody =' <div class="jHWrap"><a href="#" class="jHClose" style="display:none;"></a><table><tbody><tr><td class="png jH1"></td><td class="png jH2" /><td class="png jH3" /></tr><tr><td class="png jH4" /><td class="jHTBody"><div class="jHBody"></div></td><td class="png jH5" /></tr><tr><td class="png jH6" /><td class="png jH7"></td><td class="png jH8" /></tr></tbody></table></div>';

        if (opts.autoClose == 2)  opts.autoClose = (opts.trigger == "hover");


        $('<div class="'+opts.ttC.slice(1)+'"></div>').appendTo("body");
        $('<div class="'+opts.dC.slice(1)+'"></div>').appendTo("body");

        var  jClose='.'+opts.ttC.slice(1)+' .jHClose';
        var  jBody='.'+opts.ttC.slice(1)+' .jHBody';

        // initialize our tooltip and our data container and also the close box
        $(opts.ttC).hide();
        $(opts.dC).hide();

        // close the tooltip box
        var closeBox = function(){
            $(opts.ttC).hide().empty();
            $('.jHIE6').remove();
            return false;
        };

        $(jClose).bind("click", closeBox);

        var iniBox = function()
        {
            $(opts.ttC).empty();
            $(containerBody).appendTo(opts.ttC);
            $(jClose).unbind("click", closeBox);
            $(jClose).bind("click", closeBox);
        }

        // the sources of getting data
        var getData = function(e){
            getPosition(e);

            iniBox();
            $(opts.dC).clone(true).show().appendTo(jBody);

            showBox();


        };

        // used to position the tooltip
        var getPosition = function (e){
            var top = e.pageY+10;
            var left = e.pageX-10;
            $(opts.ttC).css({
                top: top,
                left: left
            });
            if (opts.source != "container")
                { $('.AjaxLoading').remove();
                $('body').append('<div class="AjaxLoading" style="top:'+top+'px;left:'+left+'px;"></div>');
            }

        };




        var showBox = function ()
        {
            $('.AjaxLoading').remove();
            var x=(parseInt($('body').width())-parseInt($(opts.ttC).width()))/2;
            var y=0;

            if (opts.position == 'absolute')
                {
                var y=0;
                var windowHeight =0;
                if (self.pageYOffset)  {y = self.pageYOffset;windowHeight = self.innerHeight;}
                else if (jQuery.browser.msie)
                    { if (jQuery.browser.version = '6.0')
                        {y = document.documentElement.scrollTop; windowHeight = document.documentElement.clientHeight;}
                    else
                        {y = document.body.scrollTop;windowHeight = document.body.clientHeight;}
                }
                y=parseInt(y);
                h= parseInt($(opts.ttC).height());
                if ( h > windowHeight) y=y+50; else  y= (y+(windowHeight -h)/3)
                $(opts.ttC).css({
                    top:    y,
                    left:    x
                });
            }


            $(opts.ttC).show();
            if (!opts.autoClose) $(jClose).show();



            // ie6 bug select overflow z-index fix
            if (jQuery.browser.msie)  if (jQuery.browser.version = '6.0')
                {
                $('body').append("<div class='jHIE6'><iframe  style='width:2000px;height:2000px;' src='about:blank' marginheight='10000' marginwidth='10000' scrolling='no' frameborder='0' /></div>");
                $('.jHIE6').css({
                    position: 'absolute',
                    top:    parseInt($(opts.ttC).css('top'))+1,
                    left:    parseInt($(opts.ttC).css('left'))+1,
                    height: parseInt($(opts.ttC).height())-6,
                    width:  parseInt($(opts.ttC).width())-6
                }).show();
            }
        }




        // just close tool tip when not needed usually trigger by anything outside out tooltip target



        getData( e);


    };

    $.fn.jHelperTip.defaults = {
        trigger: "click",
        source: "container", /*  container, ajax,image */
        ttC: ".jHelperTipContainer", /* tooltip Container*/
        dC: "#jHelperTipDataContainer", /* data Container */
        type: "GET", /* data can be inline or CSS selector */
        url: '',
        data: '',
        autoClose: 2,
        position:'relative' /*  relative,absolute */
    };




})(jQuery);