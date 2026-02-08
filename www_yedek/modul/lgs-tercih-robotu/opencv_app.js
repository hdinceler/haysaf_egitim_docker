const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const ctx = canvas.getContext("2d");
const statusDiv = document.getElementById("status");

let streaming = false;
let src = null;
let gray = null;

let dictionary = null;
let parameters = null;
let corners = null;
let ids = null;

async function startCamera() {
    const stream = await navigator.mediaDevices.getUserMedia({
        video: {
            facingMode: { ideal: "environment" },
            width: { ideal: 640 },
            height: { ideal: 480 }
        },
        audio: false
    });
    video.srcObject = stream;
}

function resizeCanvas() {
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
}

function initMats() {
    src  = new cv.Mat(canvas.height, canvas.width, cv.CV_8UC4);
    gray = new cv.Mat(canvas.height, canvas.width, cv.CV_8UC1);

    corners = new cv.MatVector();
    ids = new cv.Mat();

    dictionary = new cv.aruco_Dictionary(cv.aruco.DICT_4X4_50);
    parameters = new cv.aruco_DetectorParameters();
}

function processFrame() {
    if (!streaming) return;

    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    src.data.set(ctx.getImageData(0, 0, canvas.width, canvas.height).data);

    cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);

    corners.delete();
    ids.delete();
    corners = new cv.MatVector();
    ids = new cv.Mat();

    cv.aruco.detectMarkers(gray, dictionary, corners, ids, parameters);

    // Geri görüntüyü bas
    cv.imshow(canvas, gray);

    // Marker bulunduysa çiz
    if (ids.rows > 0) {
        cv.aruco.drawDetectedMarkers(src, corners, ids);

        // Orijinal renkli görüntüyü çizdir
        cv.imshow(canvas, src);

        // ID'leri yaz
        for (let i = 0; i < ids.rows; i++) {
            let id = ids.intAt(i, 0);
            console.log("Marker ID:", id);
        }
    }

    requestAnimationFrame(processFrame);
}

// OpenCV hazır olunca çalışır
function onOpenCvReady() {
    statusDiv.innerText = "OpenCV + ArUco yüklendi";

    video.addEventListener("loadedmetadata", () => {
        resizeCanvas();
        initMats();
        streaming = true;
        processFrame();
    });

    startCamera();
}

cv.onRuntimeInitialized = onOpenCvReady;
