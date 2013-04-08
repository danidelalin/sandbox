$(document).ready(function() {
    $.each($('[chain-index]'), function(key, element) {
        var options = $(element).prop ? $(element).prop('options') : $(element).attr('options');
        if (options.length <= 1) {
            $(element).attr('disabled', 'disabled')
        }
    })
});

function generateOptions(selectObject, optionList) {
    var options = selectObject.prop ? selectObject.prop('options') : selectObject.attr('options');
    $.each(optionList, function(val, newOption) {
        options[options.length] = new Option(newOption.name, newOption.id);
    });
}

function loadOptions(selectObject, routeName, paramName) {
    var params = {},
            parent = selectObject.closest('[chain-container]'),
            chain = parent.attr('chain-container').split(',');

    params[paramName] = selectObject.val();
    $.ajax({
        type: "GET",
        url: Routing.generate(routeName, params),
        success: function(data) {
            var currentLinkIndex = $.inArray(selectObject.attr('chain-index'), chain);
            if (currentLinkIndex > -1) {

                //Remove options from every next link
                $.each(parent.find('[chain-index]'), function(index, link) {
                    var actualLinkIndex = $.inArray($(link).attr('chain-index'), chain);
                    if (actualLinkIndex > currentLinkIndex) {
                        $(link).find('option:gt(0)').remove();
                    }
                });
                var i;
                for (i = currentLinkIndex + 1; i < chain.length; i++) {
                    var linkName = chain[i];
                    if (data[linkName] && data[linkName].length > 0) {
                        parent.find('[chain-index=' + linkName + ']').removeAttr('disabled');
                        generateOptions(parent.find('[chain-index=' + linkName + ']'), data[linkName])
                        break;
                    } else {
                        parent.find('[chain-index=' + linkName + ']').attr('disabled', 'disabled');
                    }

                }
            } else {
                throw {
                    message: 'Selected object\'s chain-index not found in the chain',
                    selectedObject: selectObject,
                    chain: chain
                }
            }
        }
    });
}
