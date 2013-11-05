$(document).ready(function(){
            
    $("#featured").tabs();
        
    $('#mycarousel').jcarousel({
        visible: 1,
        wrap: 'circular'
    });
    
    $("#mycarousel li").each(function(){
        
        var div_h = 110;
        var h = $(this).height();
        margin_h = (div_h - h) / 2;

        $(this).css('margin-top', margin_h + 'px');
        
    });

    
});

