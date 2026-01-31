{{-- Company: Wreetu Helth. --}}
{{-- Author: Md Shadhin --}}
{{-- Developer: Md Shadhin --}}
{{-- Copywrite: 2024 --}}

<script>
    $(document).ready(function() {
        // each geo-select class 
        $('.geo-select').each(function() {
            const type = $(this).data('type');
            const parent = $(this).data('parant');
            const id = $(this).data('defualt');
            const name = $(this).data('default_name');
            console.log({
                type,
                parent,
                id,
                name
            });
            const locationSelect = new TomSelect(this, {
                placeholder: 'Select a ' + type,
                create: function(input, callback) {
                    // ajax call to create geo locations
                    $.ajax({
                        url: "{{ route('add-geo-location') }}", // Replace with the actual route to your controller
                        method: 'POST',
                        data: {
                            name: input,
                            type: type,
                            parent_id: parent ? $(parent).val() : null,
                            id: id ?? null
                        },
                        // csrf
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            console.log(response);
                            callback({
                                value: response.id,
                                text: response.name + " (now)"
                            });
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                    });

                }, // Set to true if you want to allow creating new items
                preload: true, // Preload options on initialization
                load: function(query, callback) {
                    // Make an AJAX request to your Laravel controller
                    $.ajax({
                        url: '{{ route('locations') }}', // Replace with the actual route to your controller
                        method: 'GET',
                        data: {
                            q: query,
                            type: type, //get type from data-type attribute on the select element,
                            parent_id: parent ? $(parent).val() : null,
                            id: id
                        },
                        success: function(response) {
                            // Map the response data to the format expected by Tom Select
                            var options = response.map(function(location) {
                                return {
                                    value: location.id,
                                    text: location.text,
                                };
                            });

                            // Callback with the options to be loaded into Tom Select
                            callback(options);
                        },
                        error: function(error) {
                            console.error('Error:', error);
                        }
                    });
                },
                onLoad: function() {
                    locationSelect.setValue(id);
                }
            });
            //default value 
            if (id) {
                locationSelect.addOption({
                    value: id,
                    text: name
                })
                locationSelect.setValue(id);
            }
            // if parent
            if (parent) {
                $(parent).on("change", function() {
                    locationSelect.clearOptions()
                    locationSelect.load("");
                    // console.log("LOG...");
                });
            }
        });

        // get current location lat and long on geolocate button click
        $('#geolocate').on('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lon = position.coords.longitude;

                    $('#latitude').val(lat);
                    $('#longitude').val(lon);
                })
            }
        })
    });
</script>
