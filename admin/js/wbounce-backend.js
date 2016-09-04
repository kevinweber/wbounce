(function( wbounce_backend, $, undefined ) {

	var select_id = '#wbounce_template';
	var classForNotSelected = 'hidden-by-default';
	var classForNotSelectedDot = '.'+classForNotSelected;
	var currentTemplate = '';
	var prefixHash = '#wbounce-';

  $(document).ready(function() {
    init();
  });

  var init = function() {
  	handleTemplateSelection(select_id);
  };

  function handleTemplateSelection(id) {
  	setCurrentTemplate(id);
  	updateNotSelectedElements();
  	handleTemplateUpdate(id);
  }

  function setCurrentTemplate(id) {
    if ( $(id).length > 0 ) {
	    currentTemplate = $(id).val();
    }
  }

  function handleTemplateUpdate(id) {
  	$(id).on('change', function() {
  		setCurrentTemplate(id);
  		updateNotSelectedElements();
  	});
  }

  function updateNotSelectedElements() {
  	var elements = $(classForNotSelectedDot);
 		for (var i = elements.length - 1; i >= 0; i--) {
 			updateVisibilityOf(elements[i]);
 		}
  }

  function updateVisibilityOf(element) {
  	var elementIdHash = '#'+element.id;

  	if (elementIdHash === getCurrentTemplateWithPrefix()) {
  		element.style.display = 'block';
  	} else {
  		element.style.display = 'none';
  	}
  }

  function getCurrentTemplateWithPrefix() {
  	return prefixHash+currentTemplate;
  }

}( window.wbounce_backend = window.wbounce_backend || {}, jQuery ));
