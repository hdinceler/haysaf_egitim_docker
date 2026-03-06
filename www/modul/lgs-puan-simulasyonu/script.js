<script>
function hesapla() {
  const soru  = Number(document.getElementById("soru").value);
  const dogru = Number(document.getElementById("dogru").value);
  const yanlis = Number(document.getElementById("yanlis").value);

  if (soru <= 0) {
    alert("Soru sayısı 0 olamaz");
    return;
  }

  const net = dogru - (yanlis / 4);
  const yuzde = (net / soru) * 100;

  document.getElementById("net").innerText =
    "Net: " + net.toFixed(2);

  document.getElementById("yuzde").innerText =
    "Başarı %: " + yuzde.toFixed(1);
}
</script>
