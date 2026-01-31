(function($) {
    $.fn.loadWithSpinner = function(url, callback=null) {
      var $element = this;
      
      // Append spinner
      $element.addClass('loading-container');
      var $spinner = $('<div class="d-flex justify-content-center mt-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
      $element.empty();
      $element.append($spinner);
      
      // Load content
      $element.load(url, function(response, status, xhr) {
        // Remove spinner
        $spinner.remove();
        $element.removeClass('loading-container');

        if (status == "error") {
          var msg = "Sorry but there was an error: ";
          $element.html(msg + xhr.status + " " + xhr.statusText);
        }

        if (callback) {
          callback( $element );
        }
      });

      return this;
    };
  }(jQuery));