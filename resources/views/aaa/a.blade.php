<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PHP-WebRTC :: Peer Connection (Bob)</title>
  <link rel="stylesheet" href="{{ secure_asset('css/bootstrap.min.css') }}">
  <!-- <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}"> -->
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
    <p id="asal">asal</p>
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
        <video id="local-video" autoplay muted></video>
      </div>
      <div class="col-xs-6">
        <video id="remote-video" autoplay muted></video>
      </div>
    </div>
  </div>
  
  <script src="{{ secure_asset('js/jquery-3.2.1.slim.min.js') }}"></script>
  <script src="{{ secure_asset('js/popper.min.js') }}" ></script>
  <script src="{{ secure_asset('js/bootstrap.min.js') }}" ></script>
  <script src="{{ secure_asset('js/simplepeer.min.js') }}"></script>

  <!-- <script src="{{ asset('js/jquery-3.2.1.slim.min.js') }}"></script>
  <script src="{{ asset('js/popper.min.js') }}" ></script>
  <script src="{{ asset('js/bootstrap.min.js') }}" ></script>
  <script src="{{ asset('js/simplepeer.min.js') }}"></script> -->

  <script>
     $(function() {
      var servers = null;
      var localStream = null;
      var localVideoTrack = null;
      var peerConnection = null;

      var localVideo = document.querySelector('#local-video');
      var remoteVideo = document.querySelector('#remote-video');
      var p = null;
      // navigator.mediaDevices.getUserMedia({ video: true })
      // .then(function(stream) {
      //   localStream = stream;
      //   localVideo.srcObject = localStream;
      // })
      // .catch(function(error) {
      //   alert('Error: ', error);
      // });

      
      var looper;
      looper = setInterval(checkMessage, 3000);

      function saveMessage(message) {
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var xhr = new XMLHttpRequest;
        // var path = "{{ url('saveM') }}";
        var path = "https://meetle.herokuapp.com/saveM";

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
        // var path = "{{ url('checkM') }}?to=bob";
        var path = "https://meetle.herokuapp.com/checkM?to=bob";
        var response = null;

        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            response = JSON.parse(xhr.responseText);
            if (response.result) {
              $.each(response.data, function(i, value) {
                var sdp = JSON.parse(value.message);
                if (sdp.type == 'offer') {
                  peerInit(sdp);
                }
              });
            }
          }
        };
        xhr.open('GET', path, true);
        xhr.send();
      }

      function peerInit(sdp){
        console.log(JSON.stringify(sdp));
        p = new SimplePeer({
          initiator: false,
          trickle: false,
          config: { iceServers: [
            {
                urls: "stun:numb.viagenie.ca",
                username: "galeant12@gmail.com",
                credential: "admin123"
            },
            {
                urls: "turn:numb.viagenie.ca",
                username: "galeant12@gmail.com",
                credential: "admin123"
            }
          ]}
        });
        p.on('error', err => console.log('error', err));
        p.on('signal', data => {
          saveMessage("from=bob&to=alice&type=answer&message=" + JSON.stringify(data));
        });
        p.signal(sdp);
        p.on('connect', function(peer){
          console.log(peer);
          console.log('CONNECT')
          p.send('whatever' + Math.random())
        });

        p.on('data', data => {
          $("#asal").text(data);
          // console.log('data: ' + data)
        });
        
        p.on('stream', stream => {
          if ('srcObject' in localVideo) {
            localVideo.srcObject = stream
          } else {
            localVideo.src = window.URL.createObjectURL(stream) // for older browsers
          }
          localVideo.play()
        });
      }
    })
  </script>
</body>

</html>
