var leaderBorad = {
    init:function(){
        var that = this;
        that.active = 0;
        that.as = $('#goods_pos li');

        that.bindEvent();
    },
    bindEvent:function(){
        var that = this;
        var ad = new TouchSlider({id:'goods_slider',before:function(index){
            that.as[that.active].className = '';
            that.active = index;
            that.as[that.active].className='cur';}});

        //ad.pause();
    }
};

$(function(){
    if($(".android-ics").size()==0){
       // $("body").css("overflow-y","hidden");
    }
    var mySwiper = new Swiper('#goods_slider',{
        pagination: '#goods_pos',
        loop:true,
        grabCursor: true,
        paginationClickable: true
    });
});

