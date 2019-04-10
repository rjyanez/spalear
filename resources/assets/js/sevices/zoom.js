const axios = require('axios');

import env from './../env/zoom'
import {
  generate
} from './jwt'



export function createMeeting(topic, start_time, timezone = 'UTC', country = null, teacher = null) {

  let splitTime = start_time.split(' '),
        headers   = {},
        meeting   = {},
        token     = generate(env.ZOOM_API_KEY, env.ZOOM_API_SECRET),
        duration  = env.ZOOM_CLASS_DURATION,
        zoomUser  = (teacher) ? teacher : env.ZOOM_API_USER,
        endpoint  = env.ZOOM_API_URL.replace('{userId}', zoomUser);

  start_time = `${splitTime[0]}T${splitTime[1]}Z`
  meeting = {
    topic,
    type: 2,
    start_time,
    duration,
    timezone,
    settings: {
      host_video: false,
      participant_video: true,
      auto_recording: 'cloud',
      approval_type: 0,
      registration_type: 1,
      join_before_host: false
    }
  }
  headers = {
    'Authorization': 'Bearer '+ token
  }
  console.log(token)
  return new Promise((resolve, reject) => {
    axios.get('https://api.zoom.us/v2/users', {
        crossDomain: true,
        headers
      })
      .then(res => {
        console.log('RES', res)
        resolve(res.data)
      })
      .catch(error => {
        console.log('err', error)
        reject(error)
      })
  })
}