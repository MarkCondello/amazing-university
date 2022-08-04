import $ from 'jquery/dist/jquery';

class HeroSlider {
  constructor() {
    this.els = $(".hero-slider");
    this.initSlider();
  }

  initSlider() {
    this.els.slick({
      autoplay: true,
      arrows: false,
      dots: true
    });
  }
}

export default HeroSlider;