(function() {
  var $url = document.getElementById('js-url'),
      $date = document.getElementById('js-date'),
      $ip = document.getElementById('js-ip');

  // Add URL of the page
  $url.innerHTML = window.location.href;

  // Add current UTC Date
  var currDate = new Date().toUTCString();
  $date.innerHTML = currDate;
})();