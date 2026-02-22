<?php
// qr_generator.php
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Offline QR Generator Pro (No API)</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
:root {
  --bg:#0f172a;
  --card:#1e293b;
  --text:#f1f5f9;
  --accent:#38bdf8;
  --btn:#3b82f6;
  --border:#334155;
}
[data-theme="light"]{
  --bg:#f8fafc;
  --card:#ffffff;
  --text:#1e293b;
  --accent:#0ea5e9;
  --btn:#2563eb;
  --border:#e2e8f0;
}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif}
body{background:var(--bg);color:var(--text);min-height:100vh;display:flex;flex-direction:column}
.header{background:var(--card);border-bottom:1px solid var(--border)}
.header-content{max-width:1200px;margin:auto;padding:16px 24px;display:flex;justify-content:space-between;align-items:center}
.logo{display:flex;align-items:center;gap:10px;font-size:22px;font-weight:700;color:var(--accent)}
.theme-toggle-btn{background:var(--btn);border:none;color:white;padding:10px 18px;border-radius:10px;cursor:pointer}
.main-content{flex:1;display:flex;justify-content:center;align-items:center;padding:40px 20px}
.container{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:40px;max-width:520px;width:100%;box-shadow:0 10px 30px rgba(0,0,0,.4)}
.hero-section h2{margin-bottom:8px}
.hero-section p{opacity:.8;margin-bottom:25px}
.input-section{display:flex;flex-direction:column;gap:12px}
.input-section input{padding:14px;border-radius:10px;border:1px solid var(--border);background:var(--bg);color:var(--text)}
.generate-btn{background:linear-gradient(135deg,var(--btn),var(--accent));color:white;border:none;padding:14px;border-radius:12px;cursor:pointer;font-weight:600}
.qr-display{margin-top:25px;display:none;flex-direction:column;align-items:center;gap:15px}
.qr-display.show{display:flex}
.qr-box{background:white;padding:16px;border-radius:16px}
.download-options{
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
    margin-top: 15px;
}
.download-main-btn{
    width: 100%;
    background: linear-gradient(135deg, var(--btn), var(--accent));
    border: none;
    color: white;
    padding: 14px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.download-main-btn:hover{
    opacity: 0.9;
}
.format-menu{
    width: 100%;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 10px;
    display: none;
    flex-direction: column;
    gap: 8px;
    margin-top: 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.format-menu.show{
    display: flex;
}
.format-option{
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
    background: var(--bg);
    color: var(--text);
    font-size: 14px;
    font-weight: 500;
}
.format-option:hover{
    background: var(--accent);
    color: white;
    transform: translateX(3px);
}
.format-icon{
    width: 20px;
    text-align: center;
}
.footer{text-align:center;padding:16px;border-top:1px solid var(--border);background:var(--card)}
</style>
</head>
<body>
<header class="header">
  <div class="header-content">
    <div class="logo"><i class="fas fa-qrcode"></i> QR Sir</div>
    <button class="theme-toggle-btn" id="themeToggle"><i class="fas fa-moon" id="themeIcon"></i></button>
  </div>
</header>

<main class="main-content">
  <div class="container">
    <div class="hero-section">
      <h2>Offline QR Generator</h2>
      <p>Generate QR codes without any API or internet</p>
    </div>

    <div class="input-section">
      <input type="text" id="qrtext" placeholder="Enter text or URL">
      <button class="generate-btn" onclick="GenerateQR()"><i class="fas fa-qrcode"></i> Generate QR</button>
    </div>

    <div class="qr-display" id="qrBox">
      <div class="qr-box"><div id="qrCanvas"></div></div>
      <div class="download-options">
        <button class="download-main-btn" onclick="toggleFormatMenu()">
          <i class="fas fa-download"></i> Download QR Code
          <i class="fas fa-chevron-down" style="margin-left: auto; font-size: 12px;"></i>
        </button>
        <div class="format-menu" id="formatMenu">
          <button class="format-option" onclick="downloadFormat('png')">
            <div class="format-icon"><i class="fas fa-file-image" style="color: #2563eb;"></i></div>
            <div>PNG Image</div>
          </button>
          <button class="format-option" onclick="downloadFormat('jpg')">
            <div class="format-icon"><i class="fas fa-file-image" style="color: #dc2626;"></i></div>
            <div>JPG Image</div>
          </button>
          <button class="format-option" onclick="downloadFormat('pdf')">
            <div class="format-icon"><i class="fas fa-file-pdf" style="color: #059669;"></i></div>
            <div>PDF Document</div>
          </button>
        </div>
      </div>
    </div>
  </div>
</main>

<footer class="footer">Made with ❤️ | Offline QR System</footer>

<script>
const qrBox = document.getElementById("qrBox");
const qrCanvas = document.getElementById("qrCanvas");
const qrtext = document.getElementById("qrtext");
const themeToggle = document.getElementById("themeToggle");
const themeIcon = document.getElementById("themeIcon");
const formatMenu = document.getElementById("formatMenu");
let qrInstance = null;
let menuOpen = false;

function toggleFormatMenu() {
    menuOpen = !menuOpen;
    if (menuOpen) {
        formatMenu.classList.add('show');
    } else {
        formatMenu.classList.remove('show');
    }
}

function downloadFormat(format) {
    const canvas = qrCanvas.querySelector("canvas");
    if(!canvas) {
        alert("Generate QR first");
        formatMenu.classList.remove('show');
        menuOpen = false;
        return;
    }
    
    formatMenu.classList.remove('show');
    menuOpen = false;
    
    switch(format){
        case 'png':
            downloadAsPNG();
            break;
        case 'jpg':
            downloadAsJPG();
            break;
        case 'pdf':
            downloadAsPDF();
            break;
    }
}

function GenerateQR(){
  const text = qrtext.value.trim();
  if(!text) return alert("Enter text or URL");
  qrCanvas.innerHTML = "";
  qrInstance = new QRCode(qrCanvas,{
    text:text,
    width:280,
    height:280,
    colorDark:"#000000",
    colorLight:"#ffffff",
    correctLevel:QRCode.CorrectLevel.H
  });
  qrBox.classList.add("show");
  if(menuOpen) {
    formatMenu.classList.remove('show');
    menuOpen = false;
  }
}

function downloadAsPNG(){
  const canvas = qrCanvas.querySelector("canvas");
  if(!canvas) return alert("Generate QR first");
  const link = document.createElement("a");
  link.href = canvas.toDataURL("image/png");
  link.download = "qr-code.png";
  link.click();
}

function downloadAsJPG(){
  const canvas = qrCanvas.querySelector("canvas");
  if(!canvas) return alert("Generate QR first");
  const link = document.createElement("a");
  link.href = canvas.toDataURL("image/jpeg",0.95);
  link.download = "qr-code.jpg";
  link.click();
}

function downloadAsPDF(){
  const canvas = qrCanvas.querySelector("canvas");
  if(!canvas) return alert("Generate QR first");
  const imgData = canvas.toDataURL("image/png");
  const { jsPDF } = window.jspdf;
  const pdf = new jsPDF();
  pdf.text("QR Code",105,20,{align:"center"});
  pdf.addImage(imgData,"PNG",55,30,100,100);
  pdf.save("qr-code.pdf");
}

function toggleTheme(){
  const theme = document.documentElement.getAttribute("data-theme");
  const newTheme = theme === "light" ? "dark" : "light";
  document.documentElement.setAttribute("data-theme",newTheme);
  localStorage.setItem("theme",newTheme);
  themeIcon.className = newTheme === "dark" ? "fas fa-moon" : "fas fa-sun";
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.download-options') && menuOpen) {
        formatMenu.classList.remove('show');
        menuOpen = false;
    }
});

themeToggle.onclick = toggleTheme;

(function init(){
  const saved = localStorage.getItem("theme") || "dark";
  document.documentElement.setAttribute("data-theme",saved);
  themeIcon.className = saved === "dark" ? "fas fa-moon" : "fas fa-sun";
})();
</script>
</body>
</html>