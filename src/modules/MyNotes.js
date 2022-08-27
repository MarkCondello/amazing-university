$ = jQuery

class MyNotes {
  constructor(){
    this.events()
   }
  events(){
    $('.delete-note').on('click', (ev) => this.deleteNote($(ev.target)))
    $('.edit-note').on('click', (ev) => this.handleEditBtnClick($(ev.target)))
    $('.update-note').on('click', (ev) => this.saveNote($(ev.target)))
  }
  deleteNote($btn) {
    const $note = $btn.closest('li'),
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
  handleEditBtnClick($btn) {
    const $note = $btn.closest('li')
    if ($note.data('editing')) {
      this.readOnlyNote($note)
    } else {
      this.editNote($note)
    }
  }
  editNote($note) {
    const $editBtn = $note.find('.edit-note')
    $note.data('editing', true)
    $note.find('.note-title-field, .note-body-field').removeAttr('readonly').addClass('note-active-field')
    $note.find('.update-note').addClass('update-note--visible')
    $editBtn.html('<i class="fa fa-times" aria-hidden="true"></i> Cancel')
  }
  readOnlyNote($note) {
    const $editBtn = $note.find('.edit-note')
    $note.data('editing', false)
    $note.find('.note-title-field, .note-body-field').attr('readonly', true).removeClass('note-active-field')
    $note.find('.update-note').removeClass('update-note--visible')
    $editBtn.html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit.')
  }
  saveNote($btn) {
    const $note = $btn.closest('li'),
    noteId = $note.data('noteId'),
    updatedNote = {
      'title' : $note.find('.note-title-field').val(),
      'content' : $note.find('.note-body-field').val(),
    }
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/wp/v2/note/${noteId}`,
      type: 'POST',
      data: updatedNote,
      success: (response) => {
        console.log('Successful: ', response)
        this.readOnlyNote($note)
      },
      error: (response) => {
        console.log('Unsuccessful: ', response)
      },
    })
  }
}

export default MyNotes;