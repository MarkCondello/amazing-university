import $ from 'jquery/dist/jquery';
const slick = require("slick-slider")

class HeroSlider {
  constructor() {
    $(".hero-slider").slick({
      autoplay: true,
      arrows: false,
      dots: true
    })
    // this.els = $(".hero-slider")
    // this.initSlider()
    console.log('Hero Slider...', {this: this})

  }

  // initSlider() {
  //   this.els.slick({
  //     autoplay: true,
  //     arrows: false,
  //     dots: true
  //   })
  // }
}

export default HeroSlider;