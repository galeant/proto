<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PHP-WebRTC :: Peer Connection (Bob)</title>
  <!-- <link rel="stylesheet" href="{{ secure_asset('css/bootstrap.min.css') }}"> -->
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
    <div class="row" id="vC">
    </div>
  </div>
  
  <!-- <script src="{{ secure_asset('js/jquery-3.2.1.slim.min.js') }}"></script>
  <script src="{{ secure_asset('js/popper.min.js') }}" ></script>
  <script src="{{ secure_asset('js/bootstrap.min.js') }}" ></script>
  <script src="{{ secure_asset('js/simplepeer.min.js') }}"></script> -->

  <script src="{{ asset('js/jquery-3.2.1.slim.min.js') }}"></script>
  <script src="{{ asset('js/popper.min.js') }}" ></script>
  <script src="{{ asset('js/bootstrap.min.js') }}" ></script>
  <script src="{{ asset('js/simplepeer.min.js') }}"></script>

  <script>
     $(function() {
      var servers = null;
      var localStream = null;
      var localVideoTrack = null;
      var peerConnection = null;

      // var localVideo = document.querySelector('#local-video');
      // var remoteVideo = document.querySelector('#remote-video');
      var p = [];
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
                if (value.type == 'offer') {
                  peerInit(value);
                }
              });
            }
          }
        };
        xhr.open('GET', path, true);
        xhr.send();
      }

      function peerInit(signal){
        $("#vC").append('<div class="col-xs-6"><video id="'+signal.code+'" autoplay muted playsinline></video></div>');
        var sdp = JSON.parse(signal.message);
        p[signal.code] = new SimplePeer({
          initiator: false,
          iceTransportPolicy:'relay',
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
        p[signal.code].on('error', err => console.log('error', err));
        p[signal.code].on('signal', data => {
          saveMessage("from=bob&to=alice&code="+signal.code+"&type=answer&message=" + JSON.stringify(data));
        });
        p[signal.code].signal(sdp);
        p[signal.code].on('connect', function(peer){
          console.log('CONNECT')
          p[signal.code].send('whatever' + Math.random())
        });

        p[signal.code].on('data', data => {
          $("#asal").text(data);
        });
        
        p[signal.code].on('stream', stream => {
          var ele = "#"+signal.code;
          var localVideo = document.querySelector(ele);
          localVideo.srcObject = stream
          localVideo.play()
        });
        console.log(p)
      }
    })
  </script>
</body>

</html>
