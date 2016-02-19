
jQuery(document).ready(function($) {
    wow = new WOW(
      {
        boxClass:     'ves-animate',      // default
        animateClass: 'animated', // default
        offset:       0,          // default
        mobile:       true,       // default
        live:         true        // default
      }
    )
    wow.init();
});

/* Offcanvas Sidebars */
jQuery(document).ready( function ($){
    if( $(".offcanvas-widget-siderbars").length > 0 ) { 
        //$('.offcanvas-sidebars-buttons button').hide();
        $( ".widget-sidebar" ).each( function(){
            $('[data-for="'+$(this).attr("id")+'"]').show();
            $(this).attr("id","ves-"+$(this).attr("id") ).addClass("offcanvas-widget-sidebar");
        } );
        $(".offcanvas-widget-sidebars-buttons button").bind( "click", function(){

            if( $(this).data("reffor") == "column-left" ){
                $(".offcanvas-widget-siderbars").removeClass("column-right-active");
            }else {
                $(".offcanvas-widget-siderbars").removeClass("column-left-active");
            }
            $(".offcanvas-widget-siderbars").toggleClass( $(this).data("reffor")+"-active" );
            $("#ves-"+$(this).data("for") ).toggleClass("canvas-show");
        } );
     }

    if($(".ves-parallax").length > 0) {
        $(".ves-parallax").css("background-attachment", "fixed");
        $(".ves-parallax").each( function () {
            var percent = $(this).data("parallax-percent");
            percent = (percent!='')?percent:'50%';
            var scroll = $(this).data("parallax-scroll");
            scroll = (scroll!='' && scroll!='0')?scroll:'0.4';
            $(this).parallax(percent, scroll);  
        })
    }
} );