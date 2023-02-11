/////////   CHANGES MADE BY PRIYANSHU FOR DEVELOPER
// Commented the leanmodal line
//Commented the perfect scrollbar section
//Commented the pushpin section

$(function() {

  "use strict";

  var window_width = $(window).width();


  // Pikadate datepicker
  $('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15 // Creates a dropdown of 15 years to control year
  });


  // Detect touch screen and enable scrollbar if necessary
  function is_touch_device() {
    try {
      document.createEvent("TouchEvent");
      return true;
    }
    catch (e) {
      return false;
    }
  }
  if (is_touch_device()) {
    $('#nav-mobile').css({
      overflow: 'auto'
    })
  }

}); // end of document ready