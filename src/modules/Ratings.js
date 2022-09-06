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
    console.log('reached HandleEdit')
    const $deleteRatingBtn = $(ev.target),
    $editModal = $deleteRatingBtn.closest('.rating').find('.modal'),
    $editModalCloseBtn = $editModal.find('.close'), // need click event for this one
    $updateRatingBtn = $editModal.find('.update-rating') // need click for this one too

    $editModal.show()
    $editModalCloseBtn.on('click', function(){
      $editModal.hide()
    })
  }
  handleDelete(ev){
    const $deleteRatingBtn = $(ev.target),
    $rating = $deleteRatingBtn.closest('.rating'),
    ratingId = $deleteRatingBtn.attr('data-rating-id')
// Check this
    console.log('reached handleDelete',{
      $rating, ratingId
    })

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
        console.log('Unsuccessful: ', response)
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
        console.log('Reached rating success', response)
        $submitRatingBtn.text('New rating created!')
        $('.no-ratings-message').hide()
        $ratingForm.slideUp()
        let starsMarkup = []
        for(let i = 0; i < parseInt(response.rating); i++){
          starsMarkup.push(`<span class='fa fa-star'></span>`)
        }
        starsMarkup = starsMarkup.join('')
        $(`
        <li style="height: 40px;" class="rating">
          <div class="row">
            <div class="two-thirds">
            ${ starsMarkup }
            ${ response.post_content }
            </div>
            <div class="one-third">
              <span class="edit-rating"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
              <span class="delete-rating" data-rating-id="${ response.ID }"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            </div>
          </div>
        </li>`).prependTo('#rating-list').hide().slideDown()
      },
      error: (response) => {
        $submitRatingBtn.text('error..')
        console.log('Unsuccessful: ', response)
      },
    })

  }

}


// 

{/* <li data-note-id='${response.id}'>
<input value="${response.title.raw}" class="note-title-field" readonly>
<span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
<span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
<textarea class="note-body-field" readonly>${response.content.raw}</textarea>
<span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
</li> */}