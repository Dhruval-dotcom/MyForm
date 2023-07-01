var $locationSelect = $('.js-article-form-location');
var $specificLocationTarget = $('.js-specific-location-target');
console.log($locationSelect);

$locationSelect.on('change', function(e) {
    $.ajax({
        url: $locationSelect.data('specific-location-url'),
        data: {
            location: $locationSelect.val()
        },
        success: function (html) {
            console.log('inside success');
            if (!html) {
                $specificLocationTarget.find('select').remove();
                $specificLocationTarget.addClass('d-none');
            
                return;
            }

            // Replace the current field and show
            $specificLocationTarget
                .html(html)
                .removeClass('d-none')
        }
    });
});
