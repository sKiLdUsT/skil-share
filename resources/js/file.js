/* eslint no-labels: ["error", { "allowLoop": true }] */
'use strict'
const $ = require('jquery')

$(() => {
  if ($('.audio-container').length === 1) {
    const WaveSurfer = require('wavesurfer.js')
    $('.audio-container .background').css({backgroundImage: `url('${$('.side-nav .cover').attr('src')}')`})
    let wavesurfer = new WaveSurfer({
      container: '#wavesurfer',
      progressColor: 'darkorange',
      barWidth: '3',
      normalize: true
    })
    wavesurfer.init()
    wavesurfer.load(window.location.pathname + '?raw')
    wavesurfer.on('ready', () => {
      $('.audio-container #wavesurfer .progress').hide()
      $('<div class="btn waves-effect waves-light grey darken-1" id="media_play"><i class="material-icons">play_arrow</i></div><div class="btn waves-effect waves-light grey darken-1" id="media_stop"><i class="material-icons">stop</i></div><div><i class="material-icons left">volume_up</i><p class="range-field"><input type="range" id="media_volume" min="0" max="100" /></p></div>').appendTo('.audio-container #wavesurfer')
      $('.audio-container #wavesurfer #media_play').on('click', e => {
        e.preventDefault()
        if (wavesurfer.isPlaying()) {
          wavesurfer.pause()
          $('i', e.currentTarget).text('play_arrow')
        } else {
          wavesurfer.play()
          $('i', e.currentTarget).text('pause')
        }
      })
      $('.audio-container #wavesurfer #media_stop').on('click', e => {
        e.preventDefault()
        wavesurfer.stop()
      })
      $('.audio-container #wavesurfer #media_volume').on('input change', e => {
        e.preventDefault()
        wavesurfer.setVolume($(e.currentTarget).val() / 100)
      }).val(20)
      wavesurfer.setVolume(0.2)
    })
    wavesurfer.on('finish', () => {
      $('.audio-container #wavesurfer #media_play i').text('play_arrow')
    })
  } else if ($('.reader-container').length > 0) {
    let plugins = window.navigator.plugins
    let hasPDF = false
    masterLoop: for (let i = 0; i < plugins.length; i++) {
      for (let l = 0; l < plugins[i].length; l++) {
        if (plugins[i][l].type !== 'application/pdf') {
          hasPDF = true
          break masterLoop
        }
      }
    }
    if (hasPDF) {
      $('.reader-container').html(`<iframe src="${window.location.pathname}?raw"></iframe>`)
    } else {
      $('.reader-container').html('<p><i class="material-icons">report_problem</i>No PDF reader capability detected!</p>')
    }
  }
})
