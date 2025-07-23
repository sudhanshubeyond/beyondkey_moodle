(function($) {
    $(document).ready(function() {
    // Custom Select Search w/ Icons
        $("div[id$='edvik_icon_class'], div[id^='edvik_icon_class'], .edvik_icon_class").each(function() {
            $(this).find(".custom-select").each(function() {
                $(this).wrap("<div class='ui_kit_select_search'></div>");
                $(this).find("option").each(function() {
                    var $edvikIcon = $(this).attr("value");
                    $(this).attr("data-tokens", $edvikIcon).attr("data-icon", $edvikIcon).attr("data-subtext", $edvikIcon);
                });
                $(this).addClass("selectpicker").attr("data-live-search", "true").attr("data-width", "100%").removeClass("custom-select");
            });
        });
    });

  }(jQuery));
