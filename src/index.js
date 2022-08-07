import "../scss/style.scss"

// Our modules / classes
// import HeroSlider from './modules/HeroSlider'
import MobileMenu from './modules/MobileMenu'
import GoogleMap from './modules/GoogleMap'
import Search from './modules/Search'


const mobileMenu = new MobileMenu(),
  // heroSlider = new HeroSlider(),
googleMap = new GoogleMap(),
siteSearch = new Search()
// // Instantiate a new object using our modules/classes
jQuery(document).ready(function($){
  $('.hero-slider').owlCarousel({ items: 1, autoplay: true, });
});
