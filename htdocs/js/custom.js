var preload_array = [];

function ImagePreload() {
    if (typeof(arguments) != 'undefined') {
        for (i=0; i<arguments.length; i++ ) {
            if (typeof(arguments[i]) == "object") {
                for (k=0; k<arguments[i].length; k++) {
                    if(typeof(preload_array[arguments[i][k]]) != 'undefined'){
                        var oImage = new Image;
                        oImage.src = arguments[i][k];
                        preload_array[arguments[i][k]] = 1;
                    }
                }
            }
 
            if (typeof(arguments[i]) == "string") {
                if(typeof(preload_array[arguments[i]]) != 'undefined'){
                    var oImage = new Image;
                    oImage.src = arguments[i];
                }
            }
        }
    }
}

function buyItem(id){
    $.get('/catalog/buy/' + id + '/',function (response){
        $(".basket").html(response);
        $(".in-basket").show();
        
        if($("img").is(".b-catalog-item .image")){
            
            var i = $(".b-catalog-item .image");
            var b = $(".basket");
            
            var start_t = i.offset().top;
            var start_l = i.offset().left;
            var end_t = b.offset().top;
            var end_l = b.offset().left;
            var width = i.width();
            
            i  
            .clone()  
            .css({
                'position' : 'absolute', 
                'z-index' : '100',
                'top':start_t,
                'left':start_l,
                'width':width
            }) 
            .appendTo(".animate_wrap") 
            .animate({
                opacity: 0.5,   
                top: end_t, 
                left: end_l,
                width: 50,   
                height: 50
            }, 700, function() {  
                $(this).remove();  
            });
        }
        
    });
}

function deleteItem(id){
    $.get('/catalog/delete/' + id + '/',function (response){
        $(".basket").html(response);
        window.location.reload();
    });
}


$(document).ready(function(){
    if(location.pathname != '/'){
        $(".b-menu li a").each(function(){
            if(this.pathname != '/'){
                var link_path = this.pathname;
                
                if ($.browser.msie){
                    link_path = '/' + link_path;
                }
                if(link_path != '/'){
                    var l = link_path.length;
                    var loc = location.pathname.substring(0, l);
                    if(loc == link_path){
                        $(this).parent().addClass('active');
                    }
                }
            }
        });
    }
    
});
