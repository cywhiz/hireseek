var autocomplete;
var city = document.getElementById('city');
var country = document.getElementById('country');

function initialize() {
  var options = {
    types: ['(cities)'],
    componentRestrictions: {
      country: country.value
    }
  };
  autocomplete = new google.maps.places.Autocomplete(city, options);
  google.maps.event.addDomListener(country, 'change', setAutocomplete);
}

function setAutocomplete() {
  city.value = '';
  autocomplete.setComponentRestrictions({
    country: country.value
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
