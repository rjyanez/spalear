<template>
  <div ref="body">
<h1>Local Screensharing</h1>
    <p>This sample demonstrates sharing your local screen</p>
    <form id="credentials">
      <fieldset>
        <legend>Credentials</legend>
        <input
          id="access-token"
          name="accessToken"
          placeholder="Your access token"
          type="text">
        <input id="connect" title="connect" type="submit" value="connect">
        <p id="connection-status">disconnected</p>
      </fieldset>
    </form>

    <form id="dialer">
      <fieldset>
        <legend>Dialer</legend>
        <input
          id="invitee"
          name="invitee"
          placeholder="Person ID or Email Address or SIP URI or Room ID"
          size="60"
          type="text">
          <input title="dial" type="submit" value="dial">
      </fieldset>
    </form>

    <form id="call-controls">
      <fieldset>
        <legend>Call Controls</legend>
        <button id="hangup" title="hangup" type="button">cancel/hangup</button>

        <button id="share-screen" title="share screen" type="button">share screen</button>
        <button id="share-application" title="share application" type="button">share application</button>
        <button id="stop-screen-share" title="stop screen share" type="button">stop screen share</button>
      </fieldset>
    </form>

    <div style="align-items: flex-start; display: flex; max-width: 100%">
      <div id="screenshare-tracks"></div>
      <div style="flex: 1 1 auto; width:30%">
        <video style="width: 100%" id="self-view" muted autoplay></video>
        <video style="width: 100%" id="remote-view-video" autoplay></video>
        <audio id="remote-view-audio" autoplay></audio>
      </div>
      <div style="flex: 1 1 auto; width:60%" >
        <video style="width: 100%;" id="self-screen" muted autoplay></video>
      </div>
      <div style="flex: 1 1 auto; height: 400px;  width: 20%;">
        <h3>Events</h3>
        <pre id="event-list" style="overflow-y: auto; height: 100%;"></pre>
      </div>
    </div>

  </div>
</template>
<script>
export default {
}
</script>

