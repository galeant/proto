<html>
  <body>
    <style>
      #outgoing {
        width: 600px;
        word-wrap: break-word;
        white-space: normal;
      }
    </style>
    <form>
      <textarea id="incoming"></textarea>
      <button type="submit">submit</button>
    </form>
    <pre id="outgoing"></pre>
    <script src="{{ asset('js/simplepeer.min.js') }}"></script>
    <script>
      const p = new SimplePeer({
        initiator: false,
        trickle: false,
        config: { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }, { urls: 'stun:global.stun.twilio.com:3478?transport=udp' }] },
        // offerOptions: {},
        // answerOptions: {},
        // // sdpTransform: function (sdp) { return sdp },
        // stream: false,
        // streams: [],
        // trickle: true,
        // allowHalfTrickle: false,
        // // wrtc: {}, // RTCPeerConnection/RTCSessionDescription/RTCIceCandidate
        // objectMode: false
      })

      p.on('error', err => console.log('error', err))

      p.on('signal', data => {
        console.log('SIGNAL', JSON.stringify(data))
        document.querySelector('#outgoing').textContent = JSON.stringify(data)
      })

      document.querySelector('form').addEventListener('submit', ev => {
        ev.preventDefault()
        p.signal(JSON.parse(document.querySelector('#incoming').value))
      })
      p.on('connect', () => {
        console.log('CONNECT')
        p.send('whatever' + Math.random())
      })

      p.on('data', data => {
        console.log('data: ' + data)
      })
    </script>
  </body>
</html>