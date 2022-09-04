import $ from 'jquery'

export default class Ratings {
  constructor() {
    this.events()
  }
  events(){
    $('.submit-rating').on('click', ev => this.handleSubmit(ev))
    // ToDo: Add delete and edit events with event delegation
  }
  handleSubmit(ev){
    const $submitRatingBtn = $(ev.target),
    // Disable button and change text to sending
    $ratingNumber = $submitRatingBtn.closest('.create-rating').find('.new-rating-number'),
    $ratingContent = $submitRatingBtn.closest('.create-rating').find('.new-rating-body')
    // clear out the comment field on success
    console.log('reached handleSubmit', {
      ratingNumb: $ratingNumber.val(),
      ratingContent: $ratingContent.val(),
      programId: $submitRatingBtn.attr('data-program-id'),
    })

    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/add-rating`,
      data: {
        programId: $submitRatingBtn.attr('data-program-id'),
        rating: $ratingNumber.val(),
        content: $ratingContent.val(),
      },
      type: 'POST',
      success: (response) => {
        console.log('Reached rating success', response)
      },
      error: (response) => {
        console.log('Unsuccessful: ', response)
      },
    })

  }

}