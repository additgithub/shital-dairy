$(document).ready(function() {
    
    /**
     * Initializer remove menu function
     */ 
    $(document).on("click", ".remove-menu", function(event) {
        event.preventDefault();
        var ts = $(this);
        var cid = ts.attr("data-menu-id");
        var url = 'nav-menu/remove/';
        var $target = $(this).closest('tr'); // Add Reference </tr> of Link
        
        $.post(url, {id: cid}, function(ajaxdata) {
            if (ajaxdata.status == "ok") {
                toster_message(ajaxdata.message, ajaxdata.heading, "success");
                ts.closest("li").remove();
            }
        }, "json");
    });
    
    /**
     * Initializer add new menu item function
     */    
    $(document).on("click", "#menu_submit", function(event) {
        event.preventDefault();
        ordering = $("#nestable_list_1_output").val();
        var url = BASE_URL +'nav-menu/ordering/';
        $(this).val("Please wait...");
        $.post(url, {data: ordering}, function(ajaxdata) {
            if (ajaxdata.status == "ok") {
                showSuccess(ajaxdata.message);
                window.location.reload()
            }
            $("#menu_submit").val("Save Menu");
        }, "json");
        return false;
    });

});