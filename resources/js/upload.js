'use strict'

const $ = require('jquery')
require('./jquery.dragbetter')
let dragging = false

$(() => {
  let dropbox = $('.dropbox')
  let dropParent = $(dropbox.parent())
  $('body')
    .on('dragbetterenter', e => {
      e.preventDefault()
      if (!dragging) {
        dragging = true
        dropbox.css({height: dropParent.height() + 6, width: dropParent.width() + 6}).fadeIn()
      }
    })
    .on('dragbetterleave', e => {
      e.preventDefault()
      if (dragging) {
        dragging = false
        dropbox.fadeOut()
      }
    })
  dropbox.on('drop', e => {
    e.preventDefault()
    console.log(e)
  })
})
