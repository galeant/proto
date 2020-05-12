<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PHP-WebRTC :: Peer Connection (Bob)</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
        <video id="local-video" autoplay muted></video>
      </div>
      <div class="col-xs-6">
        <video id="remote-video" autoplay muted></video>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/peerjs@1.2.0/dist/peerjs.min.js"></script>
  <script>
     $(function() {
      var servers = null;
      var localStream = null;
      var localVideoTrack = null;
      var peerConnection = null;

      var localVideo = document.querySelector('#local-video');
      var remoteVideo = document.querySelector('#remote-video');

      navigator.mediaDevices.getUserMedia({ video: true })
      .then(function(stream) {
        localStream = stream;
        localVideo.srcObject = localStream;
      })
      .catch(function(error) {
        alert('Error: ', error);
      });

      var constraints = {
        offerToReceiveAudio: 1,
        offerToReceiveVideo: 1
      };

      // Call button clicked
      function accept(message) {
        localVideoTrack = localStream.getVideoTracks();

        // Gathering network info
        peerConnection = new RTCPeerConnection(servers);
        // Set callback
        peerConnection.onicecandidate = onIceCandidate;
        peerConnection.onaddstream = onRemoteStreamAdded;
        peerConnection.onremovestream = onRemoteStreamRemoved;
        // Set remote description
        peerConnection.setRemoteDescription(new RTCSessionDescription(message));
        // Attach video
        peerConnection.addStream(localStream);
        // Send offer
        peerConnection.createAnswer(setLocalAndSaveMessage, onCreateAnswerError, constraints);
      }

      // Got network info
      function onIceCandidate(event) {
        console.log('onIceCandidate event: ', event);
        if (event.candidate) {
          // Send candidate
          var candidate = {
            type: 'candidate',
            label: event.candidate.sdpMLineIndex,
            id: event.candidate.sdpMid,
            candidate: event.candidate.candidate
          };
          saveMessage("from=alice&to=bob&type=candidate&message=" + JSON.stringify(candidate));
          //peerConnection.addIceCandidate(new RTCIceCandidate(event.candidate));
        } else {
          console.log('End of candidates.');
        }
      }

      function onRemoteStreamAdded(event) {
        remoteVideo.srcObject = event.stream;
      }

      function onRemoteStreamRemoved(event) {
        //
      }

      function setLocalAndSaveMessage(sessionDescription) {
        console.log('Got session description: ' , sessionDescription);
        peerConnection.setLocalDescription(sessionDescription);
        saveMessage("from=bob&to=alice&type=answer&message=" + JSON.stringify(sessionDescription));
      }

      function onCreateAnswerError(event) {
        //
      }

      var looper;
      looper = setInterval(checkMessage, 3000);

      function saveMessage(message) {
        var csrf = $('meta[name="csrf-token"]').attr('content');
        var xhr = new XMLHttpRequest;
        // var host = location.protocol + '//' + location.host;
        // var path = host + '/saveMessage.php';
        var path = "{{ url('saveM') }}";

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
        // var host = location.protocol + '//' + location.host;
        // var path = host + '/checkMessage.php' + '?to=bob';
        var path = "{{ url('checkM') }}?to=bob";
        var response = null;

        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            response = JSON.parse(xhr.responseText);
            if (response.result) {
              $.each(response.data, function(i, value) {
                var sdp = JSON.parse(value.message);
                if (sdp.type == 'candidate') {
                  // Attach network info
                  var candidate = new RTCIceCandidate({
                    sdpMLineIndex: sdp.label,
                    candidate: sdp.candidate
                  });
                  //alert(JSON.stringify(candidate));
                  peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
                }
                if (sdp.type == 'offer') {
                  accept(sdp);
                }
              });
            }
          }
        };
        xhr.open('GET', path, true);
        xhr.send();
      }

    })
  </script>
</body>

</html>