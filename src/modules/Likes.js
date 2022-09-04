import $ from 'jquery'

class Likes {
  constructor (){
    this.events()
  }
  events() {
    $('.like-box-container').on('click', '.like-box', (ev)=> this.ourClickDispatcher(ev))
  }
  ourClickDispatcher(ev) {
    const $likeBox = $(ev.target).closest('.like-box')
    console.log('dispatcher..', $likeBox)
    if ($likeBox.attr('data-exists') == 'yes') {
      this.deleteLike($likeBox)
    } else {
      this.createLike($likeBox)
    }
  }
  createLike($likeBox) {
    console.log('reached createLike: ')
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/add-like`,
      data: {
        professorId: $likeBox.attr('data-professor-id'),
      },
      type: 'POST',
      success: (response) => {
        $likeBox.attr('data-exists', 'yes')
        $likeBox.attr('data-like-id', response) // dynamically add the like-id so it can be toggled
        const $likeBoxCounter = $likeBox.find('.like-count'),
        currentCount = parseInt($likeBoxCounter.text())
        $likeBoxCounter.html(currentCount + 1)
        console.log('Reached success', response, $likeBox)
      },
      error: (response) => {
        console.log('Unsuccessful: ', response)
      },
    })
  }
  deleteLike($likeBox) {
    console.log('reached deleteLike')
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/delete-like`,
      data: {
        likeId: $likeBox.attr('data-like-id'),
      },
      type: 'DELETE',
      success: (response) => {
        console.log('reached deleteLike:', response)
        $likeBox.attr('data-exists', 'no')
        $likeBox.attr('like-id', null) // dynamically add the like-id  to null so it can be toggled
        const $likeBoxCounter = $likeBox.find('.like-count'),
        currentCount = parseInt($likeBoxCounter.text())
        $likeBoxCounter.html(currentCount - 1)
      },
      error: (response) => {
        console.log('Unsuccessful: ', response)
      },
    })
  }
}

export default Likes;