<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
            .max{
                max-width:100%
            }
        </style>
    </head>
    <body identifier='{{ $identifier }}' selector="{{ $select }}">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <video class="max" id="rec-vid" autoplay></video>
                </div>
                <div class="col-md-6">
                    <p id="iden"></p>
                    <p id="status"></p>            
                </div>
            </div>
            <div class="row" id="video-container">

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/peerjs@1.2.0/dist/peerjs.min.js"></script>
        <script src="https://www.WebRTC-Experiment.com/RecordRTC.js"></script>

        <script defer src="{{ asset('js/face-api.min.js') }}"></script>
        <script type="text/javascript">
            // console.log(faceapi.nets)
                // Promise.all([
                //     faceapi.nets.tinyFaceDetector.loadFromUri("{{ asset('models') }}"),
                //     faceapi.nets.faceLandmark68Net.loadFromUri("{{ asset('models') }}"),
                //     faceapi.nets.faceRecognitionNet.loadFromUri("{{ asset('models') }}"),
                //     faceapi.nets.faceExpressionNet.loadFromUri("{{ asset('models') }}")
                // ]).then(receiver)
            (function () {
                var iden = document.getElementById('iden');
                var status = document.getElementById('status');
                var recVid = document.getElementById('rec-vid');
                var videoContainer = document.getElementById('video-container');
                var body = document.querySelector('body');
                const selector = body.getAttribute('selector');
                const identifier = body.getAttribute('identifier');
                switch(selector){
                    case 'receiver':
                        var ar = [
                            'uuuuussssrrra1',
                            'uuuuussssrrra2'
                        ];
                        for(let i=0;i< ar.length;i++){
                            receiver(ar[i]);
                        }
                        
                    break;
                    case 'caller1':
                        caller(identifier);
                    break;
                    case 'caller2':
                        caller(identifier);
                    break;
                }
                

                function receiver(id){
                    var peerR = new Peer(id,{
                        host: 'https://meetle.herokuapp.com',
                        port: 9000,
                        path: '/',
                        debug:2,
                        config: {'iceServers': [
                            { url: 'stun:stun.l.google.com:19302' }
                        ]}
                    });
                    peerR.on('open', function (id) {
                        navigator.mediaDevices.getUserMedia({video:true}).
                            then(function(stream){
                                recVid.srcObject = stream;
                                iden.innerHTML = "ID: " + peerR.id;
                                status.innerHTML = "Awaiting connection...";
                                // let recorder = RecordRTC(stream, {
                                //     type: 'video'
                                // });
                                // recorder.startRecording();
                                // recorder.stopRecording(function() {
                                //     let blob = recorder.getBlob();
                                //     invokeSaveAsDialog(blob);
                                // });
                            }).
                            catch(function(er){
                                console.log(er)
                            });
                    });
                    peerR.on('error', function(err){
                        console(err.message);
                    });
                    peerR.on('call', function(call) {
                        console.log(call.peer)
                        var div = document.createElement("div")
                        div.setAttribute('class','col-md-3');
                        var video = document.createElement('video');
                        video.setAttribute('autoplay','');
                        video.setAttribute('class','max');
                        video.setAttribute('id',call.peer);

                        div.appendChild(video)
                        videoContainer.appendChild(div)

                        call.answer(window.localStream);
                        // console.log('getCaller');
                        call.on('stream', function(stream) {
                            video.srcObject = stream;
                        });
                    });
                }

                function caller(addr){
                    var peerC = new Peer(null, {
                        host: 'localhost',
                        port: 9000,
                        path: '/',
                        debug: 2
                    });
                    peerC.on('open', function (id) {
                        navigator.mediaDevices.getUserMedia({video:true}).
                            then(function(stream){
                                recVid.srcObject = stream;
                                iden.innerHTML = "Connec to: " + addr;
                                peerC.call(addr,stream);
                            }).
                            catch(function(er){
                                console.log(er)
                            })
                    });
                }
                

                // recVid.addEventListener('play', () => {
                //     const canvas = faceapi.createCanvasFromMedia(recVid)
                //     document.body.append(canvas)
                //     const displaySize = { width: video.width, height: video.height }
                //     faceapi.matchDimensions(canvas, displaySize)
                //     setInterval(async () => {
                //         const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions()
                //         const resizedDetections = faceapi.resizeResults(detections, displaySize)
                //         canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
                //         faceapi.draw.drawDetections(canvas, resizedDetections)
                //     // faceapi.draw.drawFaceLandmarks(canvas, resizedDetections)
                //     // faceapi.draw.drawFaceExpressions(canvas, resizedDetections)
                //     }, 100)
                // })
            })();
        </script>
    </body>
</html>
