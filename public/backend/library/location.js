(function ($) {

    // Select initialization
    "use strict"; // Correct typo here
    // var HT = {};
    var doc = $(document); // Renamed variable to `doc` to avoid conflict

    var getLocation = () => {
        doc.on('change', '.location', function () {
            // console.log('Province changed');  // Add this log
            let _this = $(this);

            // Log the selected element

            let option = {
                'data': {
                    'location_id': _this.val(),
                },
                'target': _this.attr('data-target')
            };
            // console.log('Sending AJAX request:', option);

            // Log the final option object
            // console.log('Constructed option object:', option);
            // dd(option);
            // If everything is fine, continue to send the AJAX request
            sendDataTogetLocation(option);
        });
    };
    var sendDataTogetLocation = (option) => {
        $.ajax({
            url: '/ajax/location/getLocation', // The server endpoint where the data will be sent
            type: 'GET', // Method to send the request (e.g., POST or GET)
            data: option,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                // Handle the successful response from the server
                $('.' + option.target).html(response.html)
                if (district_id != '' && option.target ==
                    'districts'
                ) {
                    $('.districts').val(district_id).trigger('change')
                }
                if (ward_id != '' && option.target == 'wards') {
                    $('.wards').val(ward_id).trigger('change')

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Handle any errors
                console.error("AJAX error:" + textStatus + errorThrown);
            }
        });
    }

    var cityLoad = () => {
        if (province_id != '') {
            $(".provinces").val(province_id).trigger('change');
        }
    }
    doc.ready(function () {
        getLocation();
        cityLoad();
        // sendDataTargetLocation();
    });

})(jQuery);