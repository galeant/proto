<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>PHP-WebRTC :: Peer Connection (Alice)</title>
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
    <body name="{{ $name }}">
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
                <p><button id="call-button" >Call Bob</button></p>
            </div>
            <div class="col-xs-6">
                <video id="remote-video" autoplay muted></video>
            </div>
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
                var localVideo = document.querySelector('#local-video');
                var remoteVideo = document.querySelector('#remote-video');
                var name = $('body').attr('name');
                console.log(name);
                var p = [];
                navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    localStream = stream;
                    localVideo.srcObject = localStream;
                })
                .catch(function(error) {
                    alert('Error: ', error);
                });

                // Call button clicked
                $('#call-button').on('click', function() {
                    console.log('di klik');
                    peerInit(localStream);
                });

                
                var looper;

                function saveMessage(message) {
                    var csrf = $('meta[name="csrf-token"]').attr('content');
                    var xhr = new XMLHttpRequest;
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
                    var path = "{{ url('checkM') }}?to=alice";
                    // var path = "https://meetle.herokuapp.com/checkM?to=alice";
                    var response = null;

                    xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        response = JSON.parse(xhr.responseText);
                        if (response.result) {
                            $.each(response.data, function(i, value) {
                                var sdp = JSON.parse(value.message);
                                if (sdp.type == 'answer') {
                                    p[name].signal(sdp);
                                }
                            });
                        }
                    }
                    };
                    xhr.open('GET', path, true);
                    xhr.send();
                }
                
                function peerInit(stream){
                    p[name] = new SimplePeer({
                        initiator: true,
                        trickle: false,
                        iceTransportPolicy:'relay',
                        stream: stream,
                        config: { 
                            iceServers: [
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
                            ]
                        }
                    });
                    p[name].on('error', err => console.log('error', err));
                    p[name].on('data', data => {
                        console.log('data: ' + data)
                    });
                    p[name].on('signal', data => {
                        console.log('SIGNAL', JSON.stringify(data))    
                        saveMessage("from=alice&to=bob&code="+name+"&type=offer&message=" + JSON.stringify(data));
                    });
                    p[name].on('connect', function(){
                        p[name].send(name);
                    });
                }
            })
        </script>
    </body>
</html>