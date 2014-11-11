/* 
 * @desc:图片上传、裁剪js接口
 * @author:bruce
 */
var isSubmitButton = false;
var jcrop_api;
//引用图片上传的类
document.write("<script type='text/javascript' src='/Public/Js/uploadify/jquery.uploadify.min.js'></script>");
document.write("<link rel='stylesheet' type='text/css' href='/Public/Js/uploadify/uploadify-demo.css'>");

//引用图片裁剪的类
document.write("<script type='text/javascript' src='/Public/Js/jcrop/jquery.Jcrop.js'></script>");
document.write("<link rel='stylesheet' type='text/css' href='/Public/Js/jcrop/jquery.Jcrop.css'>"); 
var myImage = (function(my){
    /*
     * @desc：上传图片
     * @param:
     *      obj：要上传的图片按钮
     *      opt:上传的选项（图片按钮的宽度与高度）
     *      data:传给后端的参数
     *      callback1:每个图片上传成功之后的回调函数
     *      callback2:所有图片上传完成之后的回调函数
     * @return:
     *      callback1(data):
     *      data.status：状态码，0上传失败 1上传成功
     *      data.info：返回错误信息
     *      data.src:图片的url地址
     *      data.name:图片的文件名 
     *      data.width:图片的宽度
     *      data.height:图片的高度  
     *      
     *      callback2(data):
     *      data.uploadsSuccessful:上传成功的文件数量 
     *      data.uploadsErrored:上传失败的文件数量 
     */
    my.uploadImage = function(obj,opt,data,callback1,callback2){
                clearTimeout(obj.timer);
                obj.timer = setTimeout(function(){
                        obj.uploadify({
                                 'fileTypeExts' : '*.gif; *.jpg; *.jpeg; *.png; *.JPG; *.JPEG; *.GIF',
                                 'auto'            : true,
                                 'swf'      : '/Public/Js/uploadify/uploadify.swf',
                                 'uploader' : '/Api/Image/uploadImage',
                                 'width':opt.width,
                                 'height':opt.height,
                                 'multi':(opt.multi === "single")?false:true,
                                 'formData' : data,
                                 'onUploadSuccess' : function(file,data,response){
                                        callback1 && callback1(data);
                                },
                                 'onQueueComplete':function(data){
                                        callback2 && callback2(data);
                                 }
                        });                        
                },100);    
    };
    
    /*
     * @desc:裁剪图片
     * @param:
     *        src:要裁剪图片的原图地址
     *        opt:opt.width裁剪后图片的宽度、opt.height:裁剪后图片的高度
     *        dir:裁剪图片要保存的目录
     *        obj:裁剪后图片要放置的图片对象 
     *        callback:裁剪成功之后的回调函数，可为空
     */
    my.cutImage = function(src,opt,dir,obj,callback){ 
           if(parseInt(opt.width)<=0 || parseInt(opt.height)<=0){
               alert("参数有误：裁剪后的图片宽度或者高度不能为0");
               return false;
           }else{
                var orgImg = new Image();
                orgImg.src = src;
                if(orgImg.complete){    
                        if(parseInt(orgImg.width)<=0 || parseInt(orgImg.height)<=0){
                            alert("该图片不能裁剪：不能获取图片的宽度或者高度");
                            return false;
                        }else if(orgImg.width<opt.width && (opt.width-orgImg.width>2)){
                            alert("该图片不能裁剪：图片的宽度不能小于"+opt.width);
                            return false;
                        }else if(orgImg.height<opt.height && (opt.height-orgImg.height>2)){
                            alert("该图片不能裁剪：图片的高度不能小于"+opt.height);
                            return false;
                        }else{
                            if(dir === "" || dir === undefined){
                                alert("参数有误：图片保存的目录不能为空");
                            }else{  
                                startCutImage(src,opt,dir,obj,callback);
                            }
                        }                    
                }else{
                    orgImg.onload = function(){
                        if(parseInt(orgImg.width)<=0 || parseInt(orgImg.height)<=0){
                            alert("该图片不能裁剪：不能获取图片的宽度或者高度");
                            return false;
                        }else if(orgImg.width<opt.width && (opt.width-orgImg.width>2)){
                            alert("该图片不能裁剪：图片的宽度不能小于"+opt.width);
                            return false;
                        }else if(orgImg.height<opt.height && (opt.height-orgImg.height>2)){
                            alert("该图片不能裁剪：图片的高度不能小于"+opt.height);
                            return false;
                        }else{
                            if(dir === "" || dir === undefined){
                                alert("参数有误：图片保存的目录不能为空");
                            }else{    
                                startCutImage(src,opt,dir,obj,callback);
                            }
                        }
                    };
                }
                orgImg.onerror = function(){
                    alert("该图片不能裁剪：图片加载失败!");
                    return false;
                };
           };
           function startCutImage(src,opt,dir,obj,callback){
                //移除事件
                $("#js_close_reimg").unbind();
                $("#js_cutimg_btn").unbind();
                 //显示裁剪图片框，并居中对齐
                 var w = ($(window).width() - $('#js_cutimg_box').width())/2;
                 var h = ($(window).height() - $('#js_cutimg_box').height())/2 + $(document).scrollTop(); 
                 $("#js_cutimg_box").css({top:h+"px", left:w+"px"}).show();            
                 $("#js_cutimg_src").attr("src",src);
                 $("#picpath").val(src);
                 //关闭裁剪图片框
                 $("#js_close_reimg").click(function(){
                      $("#js_cutimg_box").hide();
                      if(jcrop_api){
                        jcrop_api.destroy();
                      }
                      window.onscroll = null;
                  });

                 //开始裁剪图片
                 $("#js_cutimg_btn").click(function(){
                     var picpath = $("#picpath").val();
                     var x = parseInt($("#x").val());
                     var y = parseInt($("#y").val());
                     var w = parseInt($("#w").val());
                     var h = parseInt($("#h").val());
                     if(isSubmitButton === false){
                         isSubmitButton = true;
                         $.ajax({
                             type:"post",
                             url:"/Image/cutImage",
                             data:{picpath: picpath, x: x, y: y, org_w: w, org_h: h,dst_w:opt.width,dst_h:opt.height,dst_dir:dir},
                             async:false,
                             success:function(data){
                                 var res = eval(data);
                                 if(res.status === 2){
                                     $("#js_cutimg_box").hide();
                                     obj.attr("src",res.src);
                                     obj.attr("wid",res.name);
                                     callback && callback();
                                 }else{
                                     alert(res.info);
                                     $("#js_cutimg_box").hide();
                                 }                                        
                             }
                         });
                         isSubmitButton = false;
                         if(jcrop_api){
                            jcrop_api.destroy();
                         }
                         window.onscroll = null;
                     }                         
                 }); 

                 //滚动条滚动时保持裁剪框居中对齐
                 window.onscroll = function(){
                         if($("#js_cutimg_box").is(":visible")){
                             var w = ($(window).width() - $('#js_cutimg_box').width())/2;
                             var h = ($(window).height() - $('#js_cutimg_box').height())/2 + $(document).scrollTop();        
                             $("#js_cutimg_box").css({top:h+"px", left:w+"px"});            
                         }    
                 };           
                  //裁剪图片插件初始化设置
                 initImage(opt);             
            };
            function initImage(opt) {
                if(jcrop_api){
                    jcrop_api.destroy();
                }        
                var aspectRatio = opt.width/opt.height;
                var preview_w = '',preview_h = '';
                if(opt.width>=opt.height){
                    if(opt.width>=300){
                        preview_w = 300;
                        preview_h = Math.floor(preview_w/aspectRatio);
                    }else{
                        preview_w = opt.width;
                        preview_h = Math.floor(preview_w/aspectRatio); 
                    }
                    if(preview_h>=200){              
                        preview_w = Math.floor(preview_w*200/preview_h);
                        preview_h = 200;
                    }
                }else{
                    if(opt.height>=200){
                        preview_h = 200;
                        preview_w = Math.floor(preview_h*aspectRatio);
                    }else{
                        preview_h = opt.height;
                        preview_w = Math.floor(preview_h*aspectRatio);
                    }
                } 
                $(".s-preview").css("width",preview_w+"px").css("height",preview_h+"px");
                $("#preview").attr("src",$("#js_cutimg_src").attr('src'));
                var boundx, boundy;
                $("#js_cutimg_src").Jcrop({
                    onChange: updatePreview,
                    onSelect: updatePreview,
                    aspectRatio:aspectRatio,
                    allowSelect: 0,
                    boxWidth:650,
                    boxHeight:440
                }, function() {
                    // Use the API to get the real image size
                    var bounds = this.getBounds();
                    boundx = bounds[0];
                    boundy = bounds[1];

                    // Store the API in the jcrop_api variable
                    jcrop_api = this;
                    jcrop_api.animateTo([0,0, opt.width, opt.height]);
                    jcrop_api.setOptions({minSize: [opt.width, opt.height]});
                });

                function updatePreview(c) {
                    if (parseInt(c.w) > 0) {
                        //预览图片
                        var rx = preview_w / c.w;
                        var ry = preview_h / c.h;
                        $("#preview").css({
                            width: Math.round(rx * boundx) + "px",
                            height: Math.round(ry * boundy) + "px",
                            marginLeft: "-" + Math.round(rx * Math.abs(c.x)) + "px",
                            marginTop: "-" + Math.round(ry * Math.abs(c.y)) + "px"
                        });
                        //裁剪范围
                        $('#x').val(c.x);
                        $('#y').val(c.y);
                        $('#w').val(c.w);
                        $('#h').val(c.h);
                    }
                }
            };            
    };
   
    return my;
})(myImage || {});

