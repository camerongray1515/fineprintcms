// This file provides functions that are used in many different areas of Fine Print CMS

var functions = {
    'checkbox_array': function(selector)
    {
        var arr = $.map($(selector), function(e,i) {
            return +e.value;
        });

        return arr;
    }
};