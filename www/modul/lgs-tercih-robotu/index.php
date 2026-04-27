<script  src="public/js/opencv.js"></script>

    <style>
        body {
            margin: 0;
            background: #000;
            color: #0f0;
            font-family: monospace;
            text-align: center;
        }
        video, canvas {
            width: 100vw;
            height: auto;
        }
        canvas {
            position: absolute;
            top: 0;
            left: 0;
        }
        #status {
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(0,0,0,0.7);
            color: #0f0;
            padding: 5px 10px;
            z-index: 10;
        }
    </style>

<div id="status">OpenCV yükleniyor...</div>

<video id="video" autoplay playsinline></video>
<canvas id="canvas"></canvas>

<!-- OpenCV.js -->
<script src="/modul/lgs-tercih-robotu/opencv_app.js"/>