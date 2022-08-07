$ = jQuery
class GMap {
  constructor() {
    const self = this;
    $('.acf-map').each(function(){
      self.new_map($(this));
    });
  }
  new_map($el) {
    const $markers = $el.find('.marker'),
    args = {
      zoom : 16,
      center : new google.maps.LatLng(0, 0),
      mapTypeId : google.maps.MapTypeId.ROADMAP
    },
    map = new google.maps.Map($el[0], args)
    // add a markers reference
    map.markers = [];
    var that = this; // that???
    $markers.each(function(){
      that.add_marker( $(this), map);
    });
    this.center_map(map);
  }
  add_marker( $marker, map ) {
    const latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng')),
    marker = new google.maps.Marker({
      position : latlng,
      map : map
    })

    map.markers.push(marker)
    // if marker contains HTML, add it to an infoWindow
    if ($marker.html()) {
      const infowindow = new google.maps.InfoWindow({
        content : $marker.html()
      });
      // show info window when marker is clicked
      google.maps.event.addListener(marker, 'click', function() {
        infowindow.open( map, marker );
      });
    }
  }
  center_map(map) {
    const bounds = new google.maps.LatLngBounds()
    $.each( map.markers, function( i, marker ){ // loop through all markers and create bounds
      var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
      bounds.extend( latlng );
    });
    if ( map.markers.length == 1 ) {
        map.setCenter( bounds.getCenter() );
        map.setZoom( 16 );
    } else {
      map.fitBounds( bounds );
    }
  }
}

export default GMap;