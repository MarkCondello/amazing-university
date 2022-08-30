import $ from 'jquery'

class Likes {
  constructor (){
    this.events()
  }
  events() {
    $('.like-box').on('click', (ev)=> this.ourClickDispatcher(ev))
  }
  ourClickDispatcher(ev) {
    const $likeBox = $(ev.target).closest('.like-box'),
    professorId = $likeBox.data('professor-id')
    console.log('dispatcher..', $likeBox.data('professor-id'))
    if ($likeBox.data('exists') === 'yes') {
      this.deleteLike(professorId)
    } else {
      this.createLike(professorId)
    }
  }
  createLike(professorId) {
    console.log('reached createLike: ')
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/add-like`,
      data: {
        professorId,
      },
      type: 'POST',
      success: (response) => {
        console.log(response)

      },
      error: (response) => {
        console.log('Unsuccessful: ', response)
      },
    })
  }
  deleteLike(professorId) {
    console.log('reached deleteLike')
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/delete-like`,
      data: {
        professorId,
      },
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