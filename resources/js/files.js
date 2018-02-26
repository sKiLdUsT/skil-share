'use strict'

const $ = require('jquery')

$(() => {
  $('.main-content').css({marginTop: 'calc(' + $('.main-content').height() + 'px - 75vh)'})
  $('td#time').each((index, value) => {
    $(value).text((new Date(parseInt($(value).text()) * 1000)).toLocaleDateString())
  })
})
