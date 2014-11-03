//page plus of jquery

;
(function($){
    $.fn.page = function(options) {
        //init defaults
        var defaults = {
            prev : '.prev', 
            next : '.next',
            total : 5,
            pname : '<<上一页',
            nname : '下一页>>',
            callback : function(){}
        };
        
        //merge params
        options = $.extend(defaults, options);
        var main = this;
        var list = [];
        init();
        
        //init page
        function init() {
            if(defaults.total>0) {
                main.html('');
                var p = '<a class="prev" href="javascript:;">'+ options.pname +'</a>';
                var n = '<a class="next" href="javascript:;">'+ options.nname +'</a>';
                var a = '';
                for(var i=0; i<options.total; i++) {
                    a += '<a href="javascript:;">'+ (i+1) +'</a>';
                }
                main.html(p+'<span>'+a+'</span>'+n);

                if(options.total<6) {
                    main.find('span').find('a').eq(0).addClass('hover');
                    return false;
                }

                for(var j=0; j<options.total; j++) {
                    switch(j) {
                        case 0:
                            list[0] = 'show';
                            break;
                        case 1:
                            list[1] = 'show';
                            break;
                        case 2:
                            list[2] = 'show';
                            break;
                        case 3:
                            list[3] = 'nail';
                            break;
                        case options.total-1:
                            list[options.total-1] = 'show';
                            break;
                        case options.total-2:
                            list[options.total-2] = 'show'; 
                            break;
                        default:
                            list[j] = 'hide';
                            break;
                    }
                }
                var child = main.find('span').find('a');
                for(var n=0; n<options.total; n++) {
                    if(list[n]=='nail') {
                        child.eq(n).removeClass().addClass('naill').html('...');
                    }
                    child.eq(n).removeClass().addClass(list[n]);
                }
                child.eq(0).addClass('hover');
            }
        }
        
        //change page
        function change(page) {
            var index;
            if(page<1) {
                index = 1;
            } else if(page>options.total) {
                index = options.total;
            }else {
                index = page;
            }
            index = index - 1;
            var child = main.find('span').find('a');
            if(options.total<6) {
                child.eq(index).addClass('hover').siblings().removeClass('hover');
                return index+1;
            }

            for(var i=0; i<list.length; i++) {
                switch(i) {
                    case 0:
                        list[0] = 'show';
                        break;
                    case 1:
                        list[1] = 'show';
                        break;
                    case index-1:
                        list[index-1] = 'show';
                        break;
                    case index-2:
                        list[index-2] = 'show';
                        break;
                    case index:
                        list[index] = 'show';
                        break;
                    case index+1:
                        list[index+1] = 'show';
                        break;
                    case index+2:
                        list[index+2] = 'show'; 
                        break;
                    case options.total-1:
                        list[options.total-1] = 'show';
                        break;
                    case options.total-2:
                        list[options.total-2] = 'show'; 
                        break;
                    default:
                        list[i] = 'hide';
                        break;
                }
            }

            //赋值重新初始化
            for(var j=0; j<options.total; j++) {
                child.eq(j).html(j+1);
                child.eq(j).removeClass().addClass(list[j]);
            }
            //hide与show之间插入nail
            var nail = [];
            var m = 0;
            for(var n=2; n<list.length; n++) {
                if(list[n]=='hide' && list[n-1]=='show') {
                    nail[n] = n;
                }
            }
            for(var t=0; t<nail.length; t++) {
                child.eq(nail[t]).removeClass().addClass('nail').html('...');
            }
            child.eq(index).addClass('hover');
            
            return index+1;
        }
        
        // reback function
        return this.each(function(){
            main.find('span').find('a').bind('click', function(){
                var value= $(this).text();
                if(value=='...') {
                    return false;
                }else {
                    options.callback(change(parseInt(value)));
                }
            });
            main.find(options.prev).bind('click', function(){
                var now = parseInt(main.find('span').find('.hover').text());
                options.callback(change(now-1));
            });
            main.find(options.next).bind('click', function(){
                var now = parseInt(main.find('span').find('.hover').text());
                options.callback(change(now+1));
            });
        });
        //the end
    };
})(jQuery);