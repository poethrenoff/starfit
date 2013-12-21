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
    $.get('/cart/add/' + id + '/',function (response){
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
                'z-index' : '5000',
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

function incItem($incLink){
    shiftItem($incLink, +1);
}

function decItem($decLink){
    shiftItem($decLink, -1);
}

function shiftItem($shiftLink, shift){
    var $row = $shiftLink.parents('tr:first');
    var $qntInput = $row.find('input[name^=quantity]');
    var $priceInput = $row.find('input[name^=price]');
    var qnt = parseInt($qntInput.val());
    var price = parseInt($priceInput.val());
    var $qntCell = $row.find('td').eq(3);
    var $costCell = $row.find('td').eq(4);
    
    qnt = qnt + shift;
    
    if (qnt > 0) {
        $qntInput.val(qnt);
        $qntCell.html(qnt);
        $costCell.html(qnt * price);
        
        updateCart();
    }
}

function updateCart(){
    var totalQnt = 0; var totalSum = 0;
    $('#cart').find('input[name^=quantity]').each(function(){
        var $qntInput = $(this);
        var $priceInput = $qntInput.parent().find('input[name^=price]');
        var qnt = parseInt($qntInput.val());
        var price = parseInt($priceInput.val());
        totalQnt += qnt;
        totalSum += qnt * price;
    });
    
    var $totalRow = $('#cart').find('tr:last');
    var $totalQntCell = $totalRow.find('td').eq(1);
    var $totalSumCell = $totalRow.find('td').eq(2);
    $totalQntCell.find('strong').html(totalQnt);
    $totalSumCell.find('strong').html(totalSum);
    
    $('#cart').ajaxSubmit(function(response){
        $(".basket").html(response);
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
