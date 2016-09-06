/*global jQuery, __gaTracker, ga, ouibounce */
(function ($) {
  'use strict';

  if (typeof __gaTracker === 'function') {
    __gaTracker(function () {
      window.ga = __gaTracker;
    });
  }

  /**
   * Reference: http://stackoverflow.com/a/15983064/2706988
   * @returns {*}
   */
  function isIE() {
    var myNav = navigator.userAgent.toLowerCase();
    return (myNav.indexOf('msie') !== -1) ? parseInt(myNav.split('msie')[1], 10) : false;
  }

  function isInteger(x) {
    return (typeof x === 'number') && (x % 1 === 0);
  }

  $(function () {
    var WBOUNCE_CONFIG = JSON.parse($('#wbounce-config').text()),

      wBounceModal = document.getElementById('wbounce-modal'),
      wBounceModalSub = document.getElementById('wbounce-modal-sub'),
      wBounceModalFlex = document.getElementById('wbounce-modal-flex'),

      // Assuming, if (animation !== 'none' or IE > 9)
      isAnimationIn = false,
      isAnimationOut = false,
      animationInClass,
      animationOutClass,
      _ouibounce,
      $wBounceModal,
      autoFire,
      OUIBOUNCE_CONFIG;

    function sendAnalyticsEvent(action) {
      if (WBOUNCE_CONFIG.isAnalyticsEnabled) {
        ga('send', 'event', 'wBounce', action, document.URL);
      }
    }

    if (typeof ga !== 'function') {
      WBOUNCE_CONFIG.isAnalyticsEnabled = false;
    }

    if (WBOUNCE_CONFIG.openAnimation) {
      animationInClass = 'animated ' + WBOUNCE_CONFIG.openAnimation;
      isAnimationIn = true;
    }

    if (WBOUNCE_CONFIG.exitAnimation) {
      animationOutClass = 'animated ' + WBOUNCE_CONFIG.exitAnimation;
      isAnimationOut = true;
    }

    // Time to correct our assumption
    if (isIE() && isIE() < 10) {
      isAnimationIn = false;
      isAnimationOut = false;
      animationOutClass = 'belowIE10';
      $(wBounceModalSub).addClass(animationOutClass);
    } else {
      $(wBounceModalFlex).addClass('wbounce-modal-flex-activated');
    }

    if (typeof ouibounce !== 'undefined' && $.isFunction(ouibounce)) {
      OUIBOUNCE_CONFIG = {
        // Aggressive Mode
        aggressive: WBOUNCE_CONFIG.isAggressive,
        // Cookie per page (sitewide cookie)
        sitewide: WBOUNCE_CONFIG.isSitewide,
        // Custom cookie name
        cookieName: WBOUNCE_CONFIG.cookieName,
        cookieDomain: WBOUNCE_CONFIG.cookieDomain,
        // Timer (Set a min time before wBounce fires)
        timer: parseInt(WBOUNCE_CONFIG.timer, 10),
        sensitivity: parseInt(WBOUNCE_CONFIG.sensitivity, 10)
      };

      WBOUNCE_CONFIG.hesitation = parseInt(WBOUNCE_CONFIG.hesitation, 10);

      // Expiration time in days
      if (WBOUNCE_CONFIG.cookieExpire) {
        OUIBOUNCE_CONFIG.cookieExpire = WBOUNCE_CONFIG.cookieExpire;
      }

      // Hesitation
      if (isInteger(WBOUNCE_CONFIG.hesitation)) {
        OUIBOUNCE_CONFIG.delay = WBOUNCE_CONFIG.hesitation;
      }

      // Callback
      OUIBOUNCE_CONFIG.callback = function () {
        sendAnalyticsEvent("fired");
        if (isAnimationIn) {
          $(wBounceModalSub)
            .addClass(animationInClass)
            .one(
              'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
              function () {
                $(this).removeClass(animationInClass);
              }
            );
        }
      };

      // Init
      _ouibounce = ouibounce(wBounceModal, OUIBOUNCE_CONFIG);
    }

    $wBounceModal = $(wBounceModal);

    function hidePopup() {
      if (!isAnimationOut) {
        return $wBounceModal.hide();
      }

      $(wBounceModalSub)
        .addClass(animationOutClass)
        .one(
          'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend',
          function () {
            $(this).removeClass(animationOutClass);
            $wBounceModal.hide();
          }
        );
    }

    $wBounceModal.on('click', function () {
      hidePopup();
      sendAnalyticsEvent("hidden_outside");
    });

    $wBounceModal.find('.modal-close').on('click', function () {
      hidePopup();
      sendAnalyticsEvent("hidden_close");
    });

    $wBounceModal.find('.modal-footer').on('click', function () {
      hidePopup();
      sendAnalyticsEvent("hidden_footer");
    });

    $(wBounceModalSub).on('click', function (e) {
      e.stopPropagation();
    });

    $(document).keyup(function (e) {
      if (e.which === 27 && $wBounceModal.is(":visible")) {
        hidePopup();
        sendAnalyticsEvent("hidden_escape");
      }
    });

    /*
     * AUTOFIRE JS
     */
    autoFire = parseInt(WBOUNCE_CONFIG.autoFire, 10);
    if (autoFire < 1000) {
      autoFire = 1000;
    }

    function handleAutoFire(delay) {
      if (_ouibounce.isDisabled()) {
        return;
      }
      setTimeout(_ouibounce.fire, delay);
    }
    if (isInteger(autoFire)) {
      handleAutoFire(autoFire);
    }
  });
}(jQuery));