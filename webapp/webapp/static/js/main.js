(function(PjaxrIO, $, undefined) {
    PjaxrIO.resolveMethodName = function(string) {
        // splitting the strings at dots to be able to resolve method names in namespaces
        // setting Pointer to window to be in the root of namespaces
        try {
            var data = string.split('.');
            var pointer = window;
            $.each(data, function(key, value) {
                // for every namespace or method of the callback set the pointer on it
                pointer = pointer[value];
            });
            // if the last part of the namespace points to a function, set it as the callback
            if (typeof pointer === 'function') {
                return pointer;
            }
        } catch (e) {
            // given callback method is invalid
        }
        return function() {};
    };

    PjaxrIO.toastr_opts = { closeButton: true,
                            closeHtml: '<button><span class="fa fa-times"></span></button>', 
                            positionClass: 'toast-top-left',
                            hideDuration: 300 };

    PjaxrIO.register_api_forms = function() {
        $('.api-form').off('submit').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            $form.find('.btn-primary').addClass('disabled');
            var data = new FormData( this );
            data.append('_method', $form.attr('method'));
            $.ajax({
                method: "POST",
                url: $form.attr('action'),
                data: data,
                processData: false,
                contentType: false,
                dataType: 'json',
                form: $form,
                success: function(data, successCode, jqXHR) {
                    $form.find('.form-group').removeClass('has-error');
                    if (data.url !== undefined) {
                      $(document).pjaxr.request(data.url);
                    } else {
                        for (var field in data) {
                            if (field == 'error_msgs') {
                                for (var msg_index in data[field]) {
                                    toastr.error(data[field][msg_index][0],data[field][msg_index][1], PjaxrIO.toastr_opts);
                                }
                            } else if (field == 'info_msgs') {
                                for (var msg_index in data[field]) {
                                    toastr.error(data[field][msg_index][0],data[field][msg_index][1], PjaxrIO.toastr_opts);
                                }
                            } else if (field == 'success_msgs') {
                                for (var msg_index in data[field]) {
                                    toastr.error(data[field][msg_index][0],data[field][msg_index][1], PjaxrIO.toastr_opts);
                                }
                            } else {
                                if (data[field] == 'error') {
                                    $form.find('[name="'+field+'"]').closest('.form-group').addClass('has-error');
                                }
                            }
                        }
                    }
                    PjaxrIO.resolveMethodName($form.attr('successCallback'))();
                },
                error: function(jqXHR, errorCode, errorThrown) {
                    PjaxrIO.resolveMethodName($form.attr('errorCallback'))();
                    toastr.error(PjaxrIO.error_label, PjaxrIO.error_title, PjaxrIO.toastr_opts);
                },
                complete: function(jqXHR, statusCode) {
                    try {
                        var data = $.parseJSON(jqXHR.responseText);
                        if (data != null && data != "") {
                            for (var field in data) {
                                if (field == 'error_msgs') {
                                    for (var msg_index in data[field]) {
                                        toastr.error(data[field][msg_index][0],data[field][msg_index][1], PjaxrIO.toastr_opts);
                                    }
                                } else if (field == 'info_msgs') {
                                    for (var msg_index in data[field]) {
                                        toastr.error(data[field][msg_index][0],data[field][msg_index][1], PjaxrIO.toastr_opts);
                                    }
                                } else if (field == 'success_msgs') {
                                    for (var msg_index in data[field]) {
                                        toastr.error(data[field][msg_index][0],data[field][msg_index][1], PjaxrIO.toastr_opts);
                                    }
                                } else {
                                    if (data[field] == 'error') {
                                        $form.find('[name="'+field+'"]').closest('.form-group').addClass('has-error');
                                    }
                                }
                            }
                        }
                    } catch (e) {
                    }
                    PjaxrIO.resolveMethodName($form.attr('completeCallback'))();
                    $form.find('.btn-primary').removeClass('disabled');
                }
            });
            $form.find('.btn-primary').removeClass('disabled');
        }).find('input').off('keypress').on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $(this).submit();
            }
        });
    };

    PjaxrIO.closeNavbar = function() {
        $('#inner_navbar.dropdown.open .dropdown-toggle').dropdown('toggle');
    };

    PjaxrIO.defaultUserCenter = { 'latitude': 50.36232, 'longitude': 7.5608 };
    try {
        PjaxrIO.userCenter = new google.maps.LatLng(PjaxrIO.defaultUserCenter.latitude, PjaxrIO.defaultUserCenter.longitude);
    } catch (e) {

    }

    PjaxrIO.mapStyleArray = [ { "stylers": [{ "visibility": "off" }] }, { "featureType": "road", "stylers": [{ "visibility": "on" }, { "color": "#ffffff" }] }, { "featureType": "road.arterial", "stylers": [{ "visibility": "on" }, { "color": "#ffffff" }] }, { "featureType": "road.highway", "stylers": [{ "visibility": "on" }, { "color": "#fee379" }] }, { "featureType": "landscape", "stylers": [{ "visibility": "on" }, { "color": "#f3f4f4" }] }, { "featureType": "water", "stylers": [{ "visibility": "on" }, { "color": "#6faecf" }] }, {}, { "featureType": "road", "elementType": "labels", "stylers": [{ "visibility": "off" }] }, { "featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{ "visibility": "on" }, { "color": "#83cead" }] }, { "elementType": "labels", "stylers": [{ "visibility": "on" }] }, { "featureType": "landscape.man_made", "elementType": "geometry", "stylers": [{ "weight": 0.9 }, { "visibility": "off" }] }];
    try {
        PjaxrIO.mapOptions = {
            zoom: 15,
            keyboardShortcuts: false,
            disableDefaultUI: true,
            center: PjaxrIO.userCenter, //localization
            styles: PjaxrIO.mapStyleArray,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false,
            scaleControl: false,
            draggable: false,
            panControl: false,
            zoomControl: false,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.LEFT_CENTER
            }
        };
    } catch (e) {
        PjaxrIO.mapOptions = {}
    }

    PjaxrIO.createMap = function(element, mapOptions) {
        var map = new google.maps.Map(element[0], mapOptions);
        google.maps.event.addDomListener(window, 'resize', function() {
            map.setCenter(PjaxrIO.userCenter);
        });
        PjaxrIO.createMarker(map, {"position": PjaxrIO.userCenter});
        return map;
    };

    PjaxrIO.createMarker = function(map, markeroptions) {
        markeroptions.map = map;
        markeroptions.draggable = false;
        return new google.maps.Marker(markeroptions);
    }

}(window.PjaxrIO = window.PjaxrIO || {}, jQuery));
if (PjaxrIO.PJAXR == true) {
    $(document).pjaxrAlways(function() {
        PjaxrIO.register_api_forms();
        if ($.support.pjaxr) {
            $('a').not('[data-non-pjaxr]').on('click', function(event) {
                $(document).pjaxr.click(event, {timeout: 2500});
            });
        }
        PjaxrIO.closeNavbar();
    });
} else {
    $(document).ready(function() {
        PjaxrIO.register_api_forms();
    });
}
