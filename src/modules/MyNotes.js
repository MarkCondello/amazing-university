$ = jQuery

class MyNotes {
  constructor(){
    this.events()
  }
  events(){
    $('.delete-note').on('click', this.deleteNote)
    $('.edit-note').on('click', this.editNote)

  }
  deleteNote() {
    const $note = $(this).closest('li'),
    noteId = $note.data('noteId')
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/wp/v2/note/${noteId}`,
      type: 'DELETE',
      success: (response) => {
        console.log('Successful: ', response)
        $note.slideUp()
      },
      error: (response) => {
        console.log('Unsuccessful: ', response)
      },
    })
  }
  editNote() {
    const $note = $(this).closest('li'),
    noteId = $note.data('noteId')
    $note.find('.note-title-field, .note-body-field').removeAttr('readonly').addClass('note-active-field')
    $note.find('.update-note').addClass('update-note--visible')
  }
}

export default MyNotes;