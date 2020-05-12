<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>PHP-WebRTC :: Peer Connection (Alice)</title>
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
                <a class="navbar-brand" href="#">Peer Connection :: Alice</a>
            </div>
            </div>
        </nav>
        <div class="container">
            <h3>Peer Connnection Demo</h3>
            <div class="row">
            <div class="col-xs-6">
                <video id="local-video" autoplay muted></video>
                <p><button id="call-button" style="display: none;">Call Bob</button></p>
            </div>
            <div class="col-xs-6">
                <video id="remote-video" autoplay muted></video>
            </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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

                navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    localStream = stream;
                    localVideo.srcObject = localStream;
                    $('#call-button').show();
                    $('#call-button').attr('disabled', false);
                })
                .catch(function(error) {
                    alert('Error: ', error);
                });

                var constraints = {
                    offerToReceiveAudio: 1,
                    offerToReceiveVideo: 1
                };

                // Call button clicked
                $('#call-button').on('click', function() {
                    // console.log('wdwdwd');
                    peerInit();
                });

                
                var looper;

                function saveMessage(message) {
                    var csrf = $('meta[name="csrf-token"]').attr('content');
                    var xhr = new XMLHttpRequest;
                    // var host = '127.0.0.1:8000';
                    // var path = host + '/a';
                    var path = "{{ url('saveM') }}";
                    // var path = "https://meetle.herokuapp.com/saveM";
                    

                    xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        looper = setInterval(checkMessage, 3000);
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
                    // var host = '127.0.0.1:8000';
                    // var path = host + '/dor?to=alice';
                    var path = "{{ url('checkM') }}?to=alice";
                    // var path = "https://meetle.herokuapp.com/checkM?to=alice";
                    var response = null;

                    xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        response = JSON.parse(xhr.responseText);
                        if (response.result) {
                        $.each(response.data, function(i, value) {
                            var sdp = JSON.parse(value.message);
                            // if (sdp.type == 'candidate') {
                            // // Attach network info
                            // var candidate = new RTCIceCandidate({
                            //     sdpMLineIndex: sdp.label,
                            //     candidate: sdp.candidate
                            // });
                            // //alert(JSON.stringify(candidate));
                            // peerConnection.addIceCandidate(new RTCIceCandidate(candidate));
                            // }
                            if (sdp.type == 'answer') {
                                receive(sdp);
                                // peerConnection.setRemoteDescription(new RTCSessionDescription(sdp));
                            }
                        });
                        }
                    }
                    };
                    xhr.open('GET', path, true);
                    xhr.send();
                }
                function receive(add){
                    var d = new SimplePeer();
                    d.signal(add)
                    d.on('error', err => console.log('error', err));
                    d.on('signal', data => {
                        console.log('kirim balik');
                        // console.log('SIGNAL', JSON.stringify(data))    
                        // saveMessage("from=alice&to=bob&type=offer&message=" + JSON.stringify(data));
                    })
                    d.on('connect', () => {
                        console.log('CONNECT')
                        d.send('whatever' + Math.random())
                    })

                    d.on('data', data => {
                        console.log('data: ' + data)
                    });
                }
                function peerInit(add = null){
                    var p = new SimplePeer({
                        initiator: true,
                        trickle: false
                    });
                    if(add !== null){
                        p.signal(add);
                    }
                    // console.log(p)
                    p.on('error', err => console.log('error', err));
                    p.on('signal', data => {
                        console.log('wdwdwdwdw');
                        console.log('SIGNAL', JSON.stringify(data))    
                        saveMessage("from=alice&to=bob&type=offer&message=" + JSON.stringify(data));
                    })
                    p.on('connect', () => {
                    console.log('CONNECT')
                    p.send('whatever' + Math.random())
                    })

                    p.on('data', data => {
                    console.log('data: ' + data)
                    });
                }
            })
        </script>
    </body>
</html>