<!DOCTYPE html>
<html>
<body>
<pre id="log"></pre>

<script>
const source = new EventSource("sse.php");

source.onmessage = function (e) {
    document.getElementById("log").textContent += e.data + "\n";
};
</script>
</body>
</html>