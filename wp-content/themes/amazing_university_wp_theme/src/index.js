import "../scss/style.scss"

// Our modules / classes
// import HeroSlider from './modules/HeroSlider'
import MobileMenu from './modules/MobileMenu'
import GoogleMap from './modules/GoogleMap'
import Search from './modules/Search'
import MyNotes from './modules/MyNotes'
import Likes from './modules/Likes'
import Ratings from './modules/Ratings'


const mobileMenu = new MobileMenu(),
  // heroSlider = new HeroSlider(),
googleMap = new GoogleMap(),
siteSearch = new Search(),
myNotes = new MyNotes(),
likes = new Likes(),
ratings = new Ratings()

// // Instantiate a new object using our modules/classes
jQuery(document).ready(function($){
  $('.hero-slider').owlCarousel({ items: 1, autoplay: true, });
});
