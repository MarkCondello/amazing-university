import $ from 'jquery'

class Likes {
  constructor (){
    this.events()
  }
  events() {
    $('.like-box').on('click', (ev)=> this.ourClickDispatcher(ev))
  }
  ourClickDispatcher(ev) {
    const $likeBox = $(ev.target).closest('.like-box')
    console.log('dispatcher..', $likeBox, $likeBox.data('exists'))
    if ($likeBox.data('exists') === 'yes') {
      this.deleteLike()
    } else {
      this.createLike()
    }
  }
  createLike() {
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/add-like`,
      type: 'POST',
      success: (response) => {
        console.log('reached createLike', response)

      },
      error: (response) => {
        console.log('Unsuccessful: ', response)
      },
    })
  }
  deleteLike() {
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/delete-like`,
      type: 'DELETE',
      success: (response) => {
        console.log('reached deleteLike:', response)

      },
      error: (response) => {
        console.log('Unsuccessful: ', response)
      },
    })

  }
}

export default Likes;