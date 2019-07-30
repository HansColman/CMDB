$( '.dropdown-menu .dropdown-toggle' ).on('click', function() {
    
    var $el = $(this);
    var $parent = $el.offsetParent(".dropdown-menu");
    
    if (!$el.next().hasClass("show")) {
        $el.parents('.dropdown-menu').first().find(".show").removeClass("show");
    }
    $el.next(".dropdown-menu").toggleClass("show").parent("li").toggleClass("show");
    
    $el.parents("li.nav-item.dropdown.show").on("hidden.bs.dropdown", function () {
        $(".dropdown-menu .show").removeClass("show");
    });
    
    if (!$parent.parent().hasClass("navbar-nav")) {
        $el.next().css({"top":$el[0].offsetTop,"left":$parent.outerWidth()});
    }
    
    return false;
});
