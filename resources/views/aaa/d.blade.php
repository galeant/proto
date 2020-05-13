<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PHP-WebRTC :: Peer Connection (Bob)</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <style>
    body {
      padding-top: 50px;
    }
    #local-video, #remote-video {
      width: 100%;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">Peer Connection :: Bob</a>
      </div>
    </div>
  </nav>
  <div class="container">
    <h3>Peer Connnection Demo</h3>
    <div class="row">
      <div class="col-xs-6">
        <video id="local-video" autoplay muted allow="microphone; camera"></video>
      </div>
      <div class="col-xs-6">
        <video id="remote-video" autoplay muted allow="microphone; camera"></video>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/jquery-3.2.1.slim.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}" ></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" ></script>
        <!-- <script src="https://cdn.jsdelivr.net/npm/peerjs@1.2.0/dist/peerjs.min.js"></script> -->
        <script src="{{ asset('js/simplepeer.min.js') }}"></script>
  <script>
     $(function() {
      var servers = null;
      var localStream = null;
      var localVideoTrack = null;
      var peerConnection = null;

      var localVideo = document.querySelector('#local-video');
      var remoteVideo = document.querySelector('#remote-video');

    //   navigator.mediaDevices.getUserMedia({ video: true })
    //   .then(function(stream) {
    //     localStream = stream;
    //     localVideo.srcObject = localStream;
    //   })
    //   .catch(function(error) {
    //     alert('Error: ', error);
    //   });

      
      var looper;
      looper = setInterval(checkMessage, 3000);

      function saveMessage(message) {
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var xhr = new XMLHttpRequest;
        var path = "{{ url('saveM') }}";
        // var path = "https://meetle.herokuapp.com/saveM";

        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            //
          }
        };
        xhr.open('POST', path, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("X-CSRF-TOKEN", csrf);
        xhr.setRequestHeader("Content-length", message.length);
        xhr.setRequestHeader("Connection", "close");
        xhr.send(message);
      }

      function checkMessage() {
        var xhr = new XMLHttpRequest;
        var path = "{{ url('checkM') }}?to=bob";
        // var path = "https://meetle.herokuapp.com/checkM?to=bob";
        var response = null;

        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            response = JSON.parse(xhr.responseText);
            if (response.result) {
              $.each(response.data, function(i, value) {
                var sdp = JSON.parse(value.message);
                // if (sdp.type == 'candidate') {
                //   // Attach network info
                //   var candidate = new RTCIceCandidate({
                //     sdpMLineIndex: sdp.label,
                //     candidate: sdp.candidate
                //   });
                //   //alert(JSON.stringify(candidate));
                //   peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
                // }
                if (sdp.type == 'offer') {
                  // console.log('masuk offer')
                  peerInit(sdp);
                }
              });
            }
          }
        };
        xhr.open('GET', path, true);
        xhr.send();
      }

      function peerInit(initiator = false){
        const p = new SimplePeer({
          initiator: initiator,
          trickle: false,
          config: { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }, { urls: 'stun:global.stun.twilio.com:3478?transport=udp' }] }
        })
        
        p.on('error', err => console.log('error', err));
        p.on('signal', data => {
          console.log('SIGNAL', JSON.stringify(data));
          saveMessage("from=bob&to=alice&type=answer&message=" + JSON.stringify(data))
          p.signal(add)
        })
        p.on('connect', () => {
        console.log('CONNECT')
          p.send('whatever' + Math.random())
        })

        p.on('data', data => {
          console.log('data: ' + data)
        });
        // peerConnection.on('signal', data => {
        //     console.log(data);
        //     // peer2.signal(data)
        //     // saveMessage("from=alice&to=bob&type=candidate&message=" + JSON.stringify(candidate));
        // });
      }
    })
  </script>
</body>

</html>
