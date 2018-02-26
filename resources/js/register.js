'use strict'

const $ = require('jquery')

$(() => {
  $('input#password_confirm').on('blur', () => {
    if ($('input#password').val() !== $('input#password_confirm').val() && !$('input#password_confirm').hasClass('invalid')) {
      $('input#password_confirm').removeClass('valid').addClass('invalid')
    }
  })
  $('form').on('submit', (event) => {
    if ($('input#password').val() !== $('input#password_confirm').val() && !$('input#password_confirm').hasClass('invalid')) {
      event.preventDefault()
      $('input#password_confirm').removeClass('valid').addClass('invalid')
    } else {
    }
  })
})
