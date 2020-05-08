<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width">
        <title>Peer-to-Peer Cue System --- Reciever</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>

        <div class="row">
            <div class="col-md-2">
                <input type="text" id="addu1"/>
                <button id="contb">connec</button>
            </div>
            <div class="col-md-4">
                <video id="u1" autoplay></video>
            </div>
            <div class="col-md-4 col-offset-1">
                <video id="u2" autoplay></video>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/peerjs@1.2.0/dist/peerjs.min.js"></script>
        <script type="text/javascript">
            (function () {
                var iden = document.getElementById('iden')
                var status = document.getElementById('status')
                var u1 = document.getElementById('u1')
                var u2 = document.getElementById('u2')
                var addu1 = document.getElementById('addu1');
                var contb = document.getElementById('contb')
                var conn = null;
                var call = null;
                var mediaStream = null;
                contb.addEventListener('click', stream);

                navigator.mediaDevices.getUserMedia({video:true}).
                        then(function(stream){
                            mediastream = stream;
                            u1.srcObject = stream;
                            // u2.srcObject = stream;
                        }).
                        catch(function(er){
                            console.log(er)
                        })
                 
                peer = new Peer(null, {
                    debug: 2,
                    host: 'localhost',
                    port: 9000,
                    path: '/'
                });
                
                
                peer.on('open', function (id) {
                    iden.innerHTML = "ID: " + peer.id;
                    status.innerHTML = "Awaiting connection...";
                });

                peer.on('call', function(call){
                    console.log('wdwdwdwd');
                });
                // peer.on('error', function(err){
                //     alert(err.message);
                //     // Return to step 2 if error occurs
                //     step2();
                // });
                
                

                function stream(){
                    navigator.mediaDevices.getUserMedia({video:true}).
                        then(function(stream){
                            peer.call(addu1.value,stream);
                        }).
                        catch(function(er){
                            console.log(er)
                        })
                    
                }

                // function join() {
                //     // Close old connection
                //     if (conn) {
                //         conn.close();
                //     }

                //     // Create connection to destination peer specified in the input field
                //     conn = peer.connect(addu1, {
                //         reliable: true
                //     });

                //     conn.on('open', function () {
                //         status.innerHTML = "Connected to: " + conn.peer;
                //         console.log("Connected to: " + conn.peer);

                //         // Check URL params for comamnds that should be sent immediately
                //         var command = getUrlParam("command");
                //         if (command)
                //             conn.send(command);
                //     });
                // };

                // var lastPeerId = null;
                // var peer = null; // Own peer object
                // var peerId = null;
                // var conn = null;
                // var recvId = document.getElementById("receiver-id");
                // var status = document.getElementById("status");
                // var message = document.getElementById("message");
                // var standbyBox = document.getElementById("standby");
                // var goBox = document.getElementById("go");
                // var fadeBox = document.getElementById("fade");
                // var offBox = document.getElementById("off");
                // var sendMessageBox = document.getElementById("sendMessageBox");
                // var sendButton = document.getElementById("sendButton");
                // var clearMsgsButton = document.getElementById("clearMsgsButton");

                // /**
                //  * Create the Peer object for our end of the connection.
                //  *
                //  * Sets up callbacks that handle any events related to our
                //  * peer object.
                //  */
                // let ar = ['aaa','bbb'];
                // for(let i=0;i<ar.length;i++){
                //     initialize(ar[i]);
                // }
                //  function initialize(id) {
                //     // console.log(id);
                //     // Create own peer object with connection to shared PeerJS server
                //     peer = new Peer(id, {
                //         debug: 2,
                //         host: 'localhost',
                //         port: 9000,
                //         path: '/'
                //     });

                //     peer.on('open', function (id) {
                //         // Workaround for peer.reconnect deleting previous id
                //         if (peer.id === null) {
                //             console.log('Received null id from peer open');
                //             peer.id = lastPeerId;
                //         } else {
                //             lastPeerId = peer.id;
                //         }

                //         console.log('ID: ' + peer.id);
                //         recvId.innerHTML = "ID: " + peer.id;
                //         status.innerHTML = "Awaiting connection...";
                //     });
                //     peer.on('connection', function (c) {
                //         // Allow only a single connection
                //         // if (conn && conn.open) {
                //         //     c.on('open', function() {
                //         //         c.send("Already connected to another client");
                //         //         setTimeout(function() { c.close(); }, 500);
                //         //     });
                //         //     return;
                //         // }

                //         conn = c;
                //         console.log("Connected to: " + conn.peer);
                //         status.innerHTML = "Connected";
                //         ready();
                //     });
                //     peer.on('disconnected', function () {
                //         status.innerHTML = "Connection lost. Please reconnect";
                //         console.log('Connection lost. Please reconnect');

                //         // Workaround for peer.reconnect deleting previous id
                //         peer.id = lastPeerId;
                //         peer._lastServerId = lastPeerId;
                //         peer.reconnect();
                //     });
                //     peer.on('close', function() {
                //         conn = null;
                //         status.innerHTML = "Connection destroyed. Please refresh";
                //         console.log('Connection destroyed');
                //     });
                //     peer.on('error', function (err) {
                //         console.log(err);
                //         console.log('kadal')
                //     });
                // };

                // /**
                //  * Triggered once a connection has been achieved.
                //  * Defines callbacks to handle incoming data and connection events.
                //  */
                // function ready() {
                //     conn.on('data', function (data) {
                //         console.log("Data recieved");
                //         var cueString = "<span class=\"cueMsg\">Cue: </span>";
                //         switch (data) {
                //             case 'Go':
                //                 go();
                //                 addMessage(cueString + data);
                //                 break;
                //             case 'Fade':
                //                 fade();
                //                 addMessage(cueString + data);
                //                 break;
                //             case 'Off':
                //                 off();
                //                 addMessage(cueString + data);
                //                 break;
                //             case 'Reset':
                //                 reset();
                //                 addMessage(cueString + data);
                //                 break;
                //             default:
                //                 addMessage("<span class=\"peerMsg\">Peer: </span>" + data);
                //                 break;
                //         };
                //     });
                //     conn.on('close', function () {
                //         status.innerHTML = "Connection reset<br>Awaiting connection...";
                //         conn = null;
                //     });
                // }

                // function go() {
                //     standbyBox.className = "display-box hidden";
                //     goBox.className = "display-box go";
                //     fadeBox.className = "display-box hidden";
                //     offBox.className = "display-box hidden";
                //     return;
                // };

                // function fade() {
                //     standbyBox.className = "display-box hidden";
                //     goBox.className = "display-box hidden";
                //     fadeBox.className = "display-box fade";
                //     offBox.className = "display-box hidden";
                //     return;
                // };

                // function off() {
                //     standbyBox.className = "display-box hidden";
                //     goBox.className = "display-box hidden";
                //     fadeBox.className = "display-box hidden";
                //     offBox.className = "display-box off";
                //     return;
                // }

                // function reset() {
                //     standbyBox.className = "display-box standby";
                //     goBox.className = "display-box hidden";
                //     fadeBox.className = "display-box hidden";
                //     offBox.className = "display-box hidden";
                //     return;
                // };

                // function addMessage(msg) {
                //     var now = new Date();
                //     var h = now.getHours();
                //     var m = addZero(now.getMinutes());
                //     var s = addZero(now.getSeconds());

                //     if (h > 12)
                //         h -= 12;
                //     else if (h === 0)
                //         h = 12;

                //     function addZero(t) {
                //         if (t < 10)
                //             t = "0" + t;
                //         return t;
                //     };

                //     message.innerHTML = "<br><span class=\"msg-time\">" + h + ":" + m + ":" + s + "</span>  -  " + msg + message.innerHTML;
                // }

                // function clearMessages() {
                //     message.innerHTML = "";
                //     addMessage("Msgs cleared");
                // }

                // // Listen for enter in message box
                // sendMessageBox.addEventListener('keypress', function (e) {
                //     var event = e || window.event;
                //     var char = event.which || event.keyCode;
                //     if (char == '13')
                //         sendButton.click();
                // });
                // // Send message
                // sendButton.addEventListener('click', function () {
                //     if (conn && conn.open) {
                //         var msg = sendMessageBox.value;
                //         sendMessageBox.value = "";
                //         conn.send(msg);
                //         console.log("Sent: " + msg)
                //         addMessage("<span class=\"selfMsg\">Self: </span>" + msg);
                //     } else {
                //         console.log('Connection is closed');
                //     }
                // });

                // // Clear messages box
                // clearMsgsButton.addEventListener('click', clearMessages);

                // // initialize();
            })();
        </script>
    </body>
</html>
