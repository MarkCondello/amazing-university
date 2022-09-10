import $ from 'jquery'

export default class Ratings {
  constructor() {
    this.events()
  }
  events(){
    $('.submit-rating').on('click', ev => this.handleSubmit(ev))
    $('#rating-list').on('click', '.delete-rating', ev => this.handleDelete(ev))
    $('#rating-list').on('click', '.edit-rating', ev => this.showModal(ev))
  }
  showModal(ev){
    const $editRatingBtn = $(ev.target),
    $editModal = $editRatingBtn.closest('.rating').find('.modal'),
    $editModalCloseBtn = $editModal.find('.close'), // need click event for this one
    $updateRatingBtn = $editModal.find('.update-rating') // need click for this one too
    $('body').css({'overflow': 'hidden', 'overflow-y': 'hidden'})
    $editModal.fadeIn()
    $editModalCloseBtn.on('click', function(){
      $editModal.fadeOut()
      $('body').css({'overflow': 'visible' , 'overflow-y': 'visible'})
    })
    $updateRatingBtn.on('click', () => this.handleUpdate($updateRatingBtn, $editRatingBtn))
  }
  handleUpdate($updateRatingBtn, $editRatingBtn) {
    // console.log('reached handleUpdate', {$updateRatingBtn, $editRatingBtn})
    const ratingId = $updateRatingBtn.attr('data-rating-id'),
    $modal = $updateRatingBtn.closest('.modal'),
    rating = $modal.find('.new-rating-number').val(),
    content = $modal.find('.new-rating-body').val()
    
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/update-rating`,
      data: {
        ratingId,
        rating,
        content,
      },
      type: 'POST',
      success: (response) => {
        console.log('reached handleUpdate success', {response})
        const $ratingRow = $editRatingBtn.closest('.rating'),
        $stars = $ratingRow.find('.stars'),
        $content = $ratingRow.find('.content')
        let starsMarkup = []
        for(let i = 0; i < parseInt(response.rating); i++){
          starsMarkup.push(`<span class='fa fa-star'></span>`)
        }
        starsMarkup = starsMarkup.join('')
        $content.text(response.post_content)
        $stars.html(starsMarkup)
        setTimeout(function(){
          $modal.fadeOut()
          $('body').css({'overflow': 'visible', 'overflow-y': 'visible'})
        }, 250)
      },
      error: (response) => {
        console.log('Unsuccessful update: ', response)
      },
    })
  }
  handleDelete(ev){
    const $deleteRatingBtn = $(ev.target),
    $rating = $deleteRatingBtn.closest('.rating'),
    ratingId = $deleteRatingBtn.attr('data-rating-id')
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/delete-rating`,
      data: {
        ratingId
      },
      type: 'DELETE',
      success: (response) => {
        console.log('Reached rating successful deletion', response)
        $rating.slideUp()
      },
      error: (response) => {
        console.log('Unsuccessful delete: ', response)
      },
    })
  }
  handleSubmit(ev){
    const $submitRatingBtn = $(ev.target),
    $ratingForm = $submitRatingBtn.closest('.create-rating'),
    $ratingNumber = $ratingForm.find('.new-rating-number'),
    $ratingContent = $ratingForm.find('.new-rating-body')

    $submitRatingBtn.text("sending...") // ToDo: test this
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
        $ratingContent.val('')
        console.log('Reached rating success for create', response)
        $submitRatingBtn.text('New rating created!')
        $('.no-ratings-message').hide()
        $ratingForm.slideUp()
        let starsMarkup = []
        for(let i = 0; i < parseInt(response.rating); i++){
          starsMarkup.push(`<span class='fa fa-star'></span>`)
        }
        starsMarkup = starsMarkup.join('')
        $(`
        <li class="rating">
          <div class="row">
            <div class="two-thirds">
              <div class="stars">${ starsMarkup }</div>
              <div class="content">${ response.post_content }</div>
            </div>
            <div class="one-third">
              <span class="edit-rating"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
              <span class="delete-rating" data-rating-id="${ response.ID }"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            </div>
          </div>
          <div class="modal">
          <div class="modal-content">
            <span class="close">&times;</span>
            <div class="create-rating">
              <h2>Update your rating this program</h2>
              <label for="program_rating">Rating out of 5.</label>
              <div>
                <input class="new-rating-number" type="range" min="1" max="5" name="program_rating" value="${response.rating}"/>
                <br></p></br>
              </div>
              <textarea class="new-rating-body" placeholder="Your rating goes here...">${ response.post_content }</textarea>
              <span class="update-rating" data-rating-id="${ response.ID }">Update rating</span>
            </div>
          </div>
        </div>
        </li>`).prependTo('#rating-list').hide().slideDown()
      },
      error: (response) => {
        $submitRatingBtn.text('error..')
        console.log('Unsuccessful create: ', response)
      },
    })

  }
}