(function( kevinw_framework, $, undefined ) {

  $(document).ready(function() {
    init();
  });

  var init = function() {
    handleTabs();
//    addColourPicker();
  };

  var handleTabs = function() {
    $( "#tabs" ).tabs();

    handleTabs_URL();
    handleTabs_URL_scrollTop();
  };

  /*
   * Change URL when tab is clicked
   */
  var handleTabs_URL = function() {
    $( "#tabs" ).on( "tabsactivate", function( event, ui ) {
      var href = ui.newTab.children('li a').first().attr("href");
      history.pushState(null, null, href);
      if(history.pushState) {
          history.pushState(null, null, href);
      }
      else {
          location.hash = href;
      }
    });
  };

  /*
   * When user calls a URL that contains a hash, scroll to top
   */
  var handleTabs_URL_scrollTop = function() {
    setTimeout(function() {
      if (location.hash) {
        $( "html, body" ).animate({ scrollTop: 0 }, 1000);
      }
    }, 1);
  };

  /*
   * Add colour picker
   */
  var addColourPicker = function() {
    $('.kevinw_picker_bgcolor').wpColorPicker();
  };

  /*
   * Make it possible to change a number input value per keystroke
   */
  $('input[type="number"]').keyup(function () {
      if (this.value !== this.value.replace(/[^0-9\.]/g, '')) {
         this.value = this.value.replace(/[^0-9\.]/g, '');
      }
  });

}( window.kevinw_framework = window.kevinw_framework || {}, jQuery ));