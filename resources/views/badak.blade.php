<html>
<head>
  <style>
    body {
      margin: 0;
      padding: 0;
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    canvas {
      position: absolute;
    }
  </style>
</head>
<body>
    test video
  <video id="video" width="720" height="560" autoplay muted></video>
  <script defer src="{{ asset('js/face-api.min.js') }}"></script>
  <script defer src="{{ asset('js/script.js') }}"></script>
</body>
</html>