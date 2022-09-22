$ = jQuery

class MyNotes {
  constructor(){
    this.events()
  }
  events() { // delegate click event to buttons so dynamically added notes register the click as well
    $('#my-notes').on('click', '.delete-note', (ev) => this.deleteNote($(ev.target)))
    $('#my-notes').on('click', '.edit-note', (ev) => this.handleEditBtnClick($(ev.target)))
    $('#my-notes').on('click', '.update-note', (ev) => this.saveNote($(ev.target)))
    $('.submit-note').on('click', (ev) => this.createNote($(ev.target)))
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
        if (response.notesCount < 5) {
          $('.note-limit-message').removeClass('active') // remove limit message if shown and less than 5 notes
        }
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
  createNote() {
    const newNote = {
      'title' : $('.new-note-title').val(),
      'content' : $('.new-note-body').val(),
      'status' : 'publish' // posts are set to draft by default
    }
    $.ajax({
      beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce) //Number used once
      },
      url: `${uniData.root_url}/wp-json/wp/v2/note/`,
      type: 'POST',
      data: newNote,
      success: (response) => {
        console.log('Successful: ', response)
        $('.new-note-title, .new-note-body').val('')
        $(`
          <li data-note-id='${response.id}'>
            <input value="${response.title.raw}" class="note-title-field" readonly>
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            <textarea class="note-body-field" readonly>${response.content.raw}</textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
          </li>
        `).prependTo('#my-notes').hide().slideDown()
       },
      error: (response) => {
        console.log('Unsuccessful: ', response)
        const responseText = response.responseText
        if (response.responseText === "You have reached your note limit") {
          $('.note-limit-message').addClass('active')
        }
      },
    })
  }
}

export default MyNotes;