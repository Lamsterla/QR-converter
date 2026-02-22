<?php
// Start output buffering to prevent headers being sent before HTML
ob_start();
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Code Generator Pro</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
:root {
  --bg: #0f172a;
  --card: #1e293b;
  --text: #f1f5f9;
  --accent: #38bdf8;
  --btn: #3b82f6;
  --border: #334155;
  --shadow: rgba(0, 0, 0, 0.3);
}

[data-theme="light"] {
  --bg: #f8fafc;
  --card: #ffffff;
  --text: #1e293b;
  --accent: #0ea5e9;
  --btn: #2563eb;
  --border: #e2e8f0;
  --shadow: rgba(0, 0, 0, 0.1);
}

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: 'Segoe UI', system-ui, sans-serif;
}

body {
  background: var(--bg);
  color: var(--text);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  transition: all 0.3s ease;
  line-height: 1.6;
}

.header {
  background: var(--card);
  border-bottom: 1px solid var(--border);
  box-shadow: 0 2px 10px var(--shadow);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.375rem;
  font-weight: 700;
  color: var(--accent);
}

.logo i {
  font-size: 1.75rem;
}

.theme-switcher {
  position: relative;
}

.theme-toggle-btn {
  background: var(--btn);
  border: none;
  color: white;
  padding: 0.625rem 1.25rem;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  box-shadow: 0 4px 6px var(--shadow);
  white-space: nowrap;
}

.theme-toggle-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 8px var(--shadow);
}

.theme-toggle-btn:active {
  transform: translateY(0);
}

.main-content {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2.5rem 1.25rem;
}

.container {
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 2.5rem;
  max-width: 550px;
  width: 100%;
  box-shadow: 0 10px 30px var(--shadow);
  transition: all 0.3s ease;
}

.hero-section h2 {
  font-size: 1.75rem;
  margin-bottom: 0.625rem;
  color: var(--text);
  line-height: 1.3;
}

.hero-section p {
  color: var(--text);
  opacity: 0.8;
  margin-bottom: 2rem;
  font-size: 1rem;
}

.input-section {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.input-section input {
  padding: 1rem;
  border-radius: 12px;
  border: 2px solid var(--border);
  background: var(--bg);
  color: var(--text);
  font-size: 1rem;
  transition: all 0.3s ease;
  width: 100%;
}

.input-section input:focus {
  outline: none;
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.1);
}

.input-section input::placeholder {
  color: var(--text);
  opacity: 0.6;
}

.generate-btn {
  background: linear-gradient(135deg, var(--btn), var(--accent));
  color: white;
  border: none;
  padding: 1rem 2rem;
  border-radius: 12px;
  cursor: pointer;
  font-weight: 600;
  font-size: 1rem;
  transition: all 0.3s ease;
  width: 100%;
  box-shadow: 0 4px 6px var(--shadow);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.generate-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px var(--shadow);
}

.generate-btn:active {
  transform: translateY(0);
}

.qr-display {
  margin-top: 2rem;
  display: none;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
  text-align: center;
}

.qr-display.show-img {
  display: flex;
  animation: fadeIn 0.5s ease;
}

.qr-container {
  background: white;
  padding: 1.25rem;
  border-radius: 16px;
  box-shadow: 0 8px 25px var(--shadow);
  display: inline-block;
  max-width: 100%;
}

.qr-display img {
  display: block;
  width: 100%;
  height: auto;
  max-width: 250px;
  max-height: 250px;
  border-radius: 8px;
}

.download-options {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
  justify-content: center;
  width: 100%;
}

.download-btn {
  background: var(--btn);
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.875rem;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 6px var(--shadow);
  cursor: pointer;
  flex: 1;
  min-width: 110px;
  justify-content: center;
}

.download-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px var(--shadow);
  background: var(--accent);
}

.download-btn:active {
  transform: translateY(0);
}

.download-btn.png {
  background: linear-gradient(135deg, #3b82f6, #1d4ed8);
}

.download-btn.jpg {
  background: linear-gradient(135deg, #ef4444, #dc2626);
}

.download-btn.pdf {
  background: linear-gradient(135deg, #10b981, #059669);
}

.footer {
  text-align: center;
  padding: 1.5rem;
  background: var(--card);
  border-top: 1px solid var(--border);
  color: var(--text);
  opacity: 0.9;
  margin-top: auto;
}

.footer p {
  margin-bottom: 0.5rem;
}

.footer a {
  color: var(--accent);
  text-decoration: none;
  font-size: 1.25rem;
  transition: color 0.3s ease;
  display: inline-block;
  margin: 0 0.25rem;
}

.footer a:hover {
  color: var(--btn);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.loading {
  display: none;
  text-align: center;
  padding: 1.25rem;
  color: var(--accent);
}

.loading.show {
  display: block;
}

.loading i {
  font-size: 1.5rem;
  margin-bottom: 0.625rem;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.qr-info {
  margin-top: 1rem;
  color: var(--text);
  opacity: 0.8;
  font-size: 0.875rem;
  padding: 0 0.5rem;
  word-break: break-word;
  max-width: 100%;
}

.download-success {
  position: fixed;
  top: 20px;
  right: 20px;
  background: #10b981;
  color: white;
  padding: 0.75rem 1.25rem;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  display: none;
  z-index: 1000;
  animation: slideIn 0.3s ease;
  max-width: 300px;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

/* Responsive Breakpoints */

/* Tablets and smaller laptops */
@media (max-width: 992px) {
  .container {
    padding: 2rem;
  }
  
  .hero-section h2 {
    font-size: 1.625rem;
  }
}

/* Tablets */
@media (max-width: 768px) {
  .header-content {
    padding: 0.875rem 1rem;
  }
  
  .logo {
    font-size: 1.25rem;
  }
  
  .logo i {
    font-size: 1.5rem;
  }
  
  .theme-toggle-btn {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
  }
  
  .theme-toggle-btn span {
    display: none;
  }
  
  .theme-toggle-btn i {
    margin: 0;
  }
  
  .main-content {
    padding: 2rem 1rem;
  }
  
  .container {
    padding: 1.75rem 1.5rem;
    border-radius: 18px;
  }
  
  .hero-section h2 {
    font-size: 1.5rem;
  }
  
  .hero-section p {
    font-size: 0.9375rem;
  }
  
  .qr-display img {
    max-width: 220px;
    max-height: 220px;
  }
  
  .download-options {
    flex-direction: row;
  }
  
  .download-btn {
    min-width: 100px;
    padding: 0.75rem 1rem;
    font-size: 0.8125rem;
  }
  
  .download-success {
    top: 15px;
    right: 15px;
    max-width: 280px;
  }
}

/* Mobile Phones */
@media (max-width: 576px) {
  .header-content {
    padding: 0.75rem 0.875rem;
  }
  
  .logo {
    font-size: 1.125rem;
  }
  
  .logo i {
    font-size: 1.375rem;
  }
  
  .main-content {
    padding: 1.5rem 0.875rem;
  }
  
  .container {
    padding: 1.5rem 1.25rem;
    border-radius: 16px;
  }
  
  .hero-section h2 {
    font-size: 1.375rem;
  }
  
  .hero-section p {
    font-size: 0.875rem;
    margin-bottom: 1.75rem;
  }
  
  .input-section {
    gap: 0.875rem;
    margin-bottom: 1.25rem;
  }
  
  .input-section input {
    padding: 0.875rem;
    font-size: 0.9375rem;
  }
  
  .generate-btn {
    padding: 0.875rem 1.5rem;
    font-size: 0.9375rem;
  }
  
  .qr-display {
    margin-top: 1.75rem;
    gap: 1.25rem;
  }
  
  .qr-display img {
    max-width: 200px;
    max-height: 200px;
  }
  
  .download-options {
    flex-direction: column;
    gap: 0.625rem;
  }
  
  .download-btn {
    width: 100%;
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
  }
  
  .qr-info {
    font-size: 0.8125rem;
  }
  
  .footer {
    padding: 1.25rem;
    font-size: 0.875rem;
  }
  
  .footer a {
    font-size: 1.125rem;
  }
  
  .download-success {
    top: 10px;
    right: 10px;
    left: 10px;
    max-width: none;
    padding: 0.75rem 1rem;
    text-align: center;
  }
}

/* Small Mobile Phones */
@media (max-width: 400px) {
  .logo {
    font-size: 1rem;
  }
  
  .logo i {
    font-size: 1.25rem;
  }
  
  .container {
    padding: 1.25rem 1rem;
    border-radius: 14px;
  }
  
  .hero-section h2 {
    font-size: 1.25rem;
  }
  
  .input-section input {
    padding: 0.75rem;
    font-size: 0.875rem;
  }
  
  .generate-btn {
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
  }
  
  .qr-display img {
    max-width: 180px;
    max-height: 180px;
  }
  
  .loading i {
    font-size: 1.25rem;
  }
  
  .loading p {
    font-size: 0.875rem;
  }
}

/* Landscape orientation for mobile */
@media (max-height: 500px) and (orientation: landscape) {
  .main-content {
    padding: 1rem;
  }
  
  .container {
    padding: 1.5rem;
    max-width: 90%;
  }
  
  .qr-display {
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .qr-container {
    flex: 0 0 auto;
  }
  
  .download-options {
    flex-direction: row;
    justify-content: center;
  }
}

/* High DPI screens */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
  .generate-btn,
  .download-btn,
  .theme-toggle-btn {
    border-width: 1px;
  }
}

/* Print styles */
@media print {
  .header,
  .footer,
  .theme-switcher,
  .generate-btn,
  .download-options {
    display: none !important;
  }
  
  body {
    background: white !important;
    color: black !important;
  }
  
  .container {
    box-shadow: none !important;
    border: 1px solid #ddd !important;
  }
  
  .qr-display {
    display: flex !important;
  }
}
</style>
</head>
<body>

<header class="header">
  <div class="header-content">
    <div class="logo">
      <i class="fas fa-qrcode"></i>
      <h1>QR Sir</h1>
    </div>
    <div class="theme-switcher">
      <button class="theme-toggle-btn" id="themeToggle">
        <i class="fas fa-moon" id="themeIcon"></i>
        <span id="themeLabel">Dark Mode</span>
      </button>
    </div>
  </div>
</header>

<main class="main-content">
  <div class="container">
    <div class="hero-section">
      <h2>Generate QR Codes Instantly</h2>
      <p>Convert any text or URL into a beautiful, scannable QR code</p>
    </div>

    <div class="input-section">
      <input type="text" id="qrtext" placeholder="Enter text or URL (e.g., https://example.com)">
      <button class="generate-btn" onclick="GenerateQR()">
        <i class="fas fa-qrcode"></i> Generate QR Code
      </button>
    </div>

    <div class="loading" id="loading">
      <i class="fas fa-spinner"></i>
      <p>Generating QR Code...</p>
    </div>

    <div class="qr-display" id="imgbox">
      <div class="qr-container">
        <img id="qrimage" src="" alt="QR Code">
      </div>
      <div class="qr-info" id="qrInfo"></div>
      <div class="download-options">
        <button id="downloadPng" class="download-btn png">
          <i class="fas fa-file-image"></i> PNG
        </button>
        <button id="downloadJpg" class="download-btn jpg">
          <i class="fas fa-file-image"></i> JPG
        </button>
        <button id="downloadPdf" class="download-btn pdf">
          <i class="fas fa-file-pdf"></i> PDF
        </button>
      </div>
    </div>
  </div>
</main>

<footer class="footer">
  <p>Made under the guidance of Root | Powered by Arun</p>
  <a href="https://www.linkedin.com" target="_blank">
    <i class="fab fa-linkedin"></i>
  </a>
</footer>

<!-- Download success notification -->
<div class="download-success" id="downloadSuccess">
  <i class="fas fa-check-circle"></i> <span id="successMessage">Download started!</span>
</div>

<script>
// Global variables
const imgbox = document.getElementById("imgbox");
const qrimage = document.getElementById("qrimage");
const qrtext = document.getElementById("qrtext");
const loading = document.getElementById("loading");
const themeToggle = document.getElementById("themeToggle");
const themeIcon = document.getElementById("themeIcon");
const themeLabel = document.getElementById("themeLabel");
const downloadPng = document.getElementById("downloadPng");
const downloadJpg = document.getElementById("downloadJpg");
const downloadPdf = document.getElementById("downloadPdf");
const qrInfo = document.getElementById("qrInfo");
const downloadSuccess = document.getElementById("downloadSuccess");
const successMessage = document.getElementById("successMessage");

let qrData = "";
let qrGenerated = false;
let currentQRUrl = "";

// Generate QR Code function
async function GenerateQR() {
  const inputText = qrtext.value.trim();
  
  if (!inputText) {
    alert("Please enter text or URL to generate QR code");
    qrtext.focus();
    return;
  }
  
  qrData = inputText;
  
  // Show loading, hide previous QR
  loading.classList.add("show");
  imgbox.classList.remove("show-img");
  qrGenerated = false;
  
  try {
    // Generate QR code using an API
    const apiUrl = "https://api.qrserver.com/v1/create-qr-code/";
    const size = "300x300";
    const data = encodeURIComponent(inputText);
    currentQRUrl = `${apiUrl}?size=${size}&data=${data}&format=png&color=0-0-0&bgcolor=255-255-255&qzone=1`;
    
    // Create a promise to handle image loading
    await new Promise((resolve, reject) => {
      qrimage.onload = resolve;
      qrimage.onerror = reject;
      qrimage.src = currentQRUrl;
    });
    
    // Wait a bit to ensure image is fully loaded
    await new Promise(resolve => setTimeout(resolve, 500));
    
    // Hide loading, show QR
    loading.classList.remove("show");
    imgbox.classList.add("show-img");
    qrGenerated = true;
    
    // Show QR info
    const textPreview = inputText.length > 50 ? inputText.substring(0, 50) + "..." : inputText;
    qrInfo.innerHTML = `QR Code for: <strong>"${textPreview}"</strong>`;
    
  } catch (error) {
    loading.classList.remove("show");
    alert("Failed to generate QR code. Please try again.");
    console.error("QR Generation Error:", error);
  }
}

// Show download success notification
function showDownloadNotification(format) {
  successMessage.textContent = `QR Code downloaded as ${format}!`;
  downloadSuccess.style.display = 'flex';
  downloadSuccess.style.alignItems = 'center';
  downloadSuccess.style.gap = '8px';
  
  setTimeout(() => {
    downloadSuccess.style.display = 'none';
  }, 3000);
}

// Create and download image
function downloadImage(format, quality = 1.0) {
  if (!qrGenerated || !qrimage.src) {
    alert("Please generate a QR code first");
    return;
  }
  
  try {
    // Create a canvas element
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Set canvas dimensions (add padding for better quality)
    canvas.width = 350;
    canvas.height = 350;
    
    // Fill with white background
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Draw the QR code in the center
    const qrSize = 300;
    const offset = (canvas.width - qrSize) / 2;
    
    // Create a temporary image to ensure it's loaded
    const tempImg = new Image();
    tempImg.crossOrigin = 'Anonymous'; // Important for CORS
    
    tempImg.onload = function() {
      // Draw the QR code
      ctx.drawImage(tempImg, offset, offset, qrSize, qrSize);
      
      // Add a border
      ctx.strokeStyle = '#333';
      ctx.lineWidth = 2;
      ctx.strokeRect(offset - 2, offset - 2, qrSize + 4, qrSize + 4);
      
      // Create download link
      const link = document.createElement('a');
      const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
      const filename = `qr-code-${timestamp}.${format}`;
      
      if (format === 'png') {
        link.href = canvas.toDataURL('image/png');
      } else if (format === 'jpg' || format === 'jpeg') {
        link.href = canvas.toDataURL('image/jpeg', quality);
      }
      
      link.download = filename;
      
      // Trigger download
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      
      // Show success notification
      showDownloadNotification(format.toUpperCase());
    };
    
    tempImg.onerror = function() {
      alert("Error loading QR image. Please try generating again.");
    };
    
    // Load the image (add cache busting to avoid CORS issues)
    tempImg.src = currentQRUrl + '&t=' + Date.now();
    
  } catch (error) {
    console.error("Download error:", error);
    alert(`Failed to download ${format}. Please try again.`);
  }
}

// Download as PNG
function downloadAsPNG() {
  downloadImage('png', 1.0);
}

// Download as JPG
function downloadAsJPG() {
  downloadImage('jpg', 0.95);
}

// Download as PDF
async function downloadAsPDF() {
  if (!qrGenerated) {
    alert("Please generate a QR code first");
    return;
  }
  
  try {
    // Create a temporary div for PDF content
    const pdfContent = document.createElement('div');
    pdfContent.style.cssText = `
      position: fixed;
      left: -9999px;
      top: -9999px;
      width: 210mm;
      padding: 20mm;
      background: white;
      color: black;
      font-family: Arial, sans-serif;
      text-align: center;
    `;
    
    const date = new Date().toLocaleDateString();
    const time = new Date().toLocaleTimeString();
    
    pdfContent.innerHTML = `
      <h1 style="color: #333; margin-bottom: 10px; font-size: 24px;">QR Code</h1>
      <p style="color: #666; margin-bottom: 5px;">Generated on: ${date}</p>
      <p style="color: #666; margin-bottom: 20px;">Time: ${time}</p>
      <div style="margin: 20px 0; display: flex; justify-content: center;">
        <img src="${currentQRUrl}" width="200" height="200" style="border: 1px solid #ddd;" />
      </div>
      <p style="color: #444; margin-top: 20px; font-size: 14px;"><strong>Encoded Data:</strong></p>
      <p style="color: #333; background: #f5f5f5; padding: 10px; border-radius: 5px; word-break: break-all; font-size: 12px;">
        ${qrData}
      </p>
      <p style="color: #777; font-size: 12px; margin-top: 30px;">
        QR Code generated by QR Code Generator Pro
      </p>
    `;
    
    document.body.appendChild(pdfContent);
    
    // Use html2canvas to capture the content
    const canvas = await html2canvas(pdfContent, {
      scale: 2,
      useCORS: true,
      backgroundColor: '#ffffff',
      logging: false
    });
    
    // Create PDF
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p', 'mm', 'a4');
    
    // Get image data from canvas
    const imgData = canvas.toDataURL('image/png');
    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = pdf.internal.pageSize.getHeight();
    
    // Add image to PDF (full page)
    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
    
    // Download PDF
    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
    const filename = `qr-code-${timestamp}.pdf`;
    pdf.save(filename);
    
    // Clean up
    document.body.removeChild(pdfContent);
    
    // Show success notification
    showDownloadNotification("PDF");
    
  } catch (error) {
    console.error("PDF Generation Error:", error);
    alert("Failed to generate PDF. Please try downloading as PNG or JPG instead.");
  }
}

// Theme toggle functionality
function toggleTheme() {
  const currentTheme = document.documentElement.getAttribute("data-theme");
  const newTheme = currentTheme === "light" ? "dark" : "light";
  
  document.documentElement.setAttribute("data-theme", newTheme);
  localStorage.setItem("theme", newTheme);
  
  // Update button icon and text
  if (newTheme === "dark") {
    themeIcon.className = "fas fa-moon";
    themeLabel.textContent = "Dark Mode";
  } else {
    themeIcon.className = "fas fa-sun";
    themeLabel.textContent = "Light Mode";
  }
}

// Initialize from localStorage
function initializeTheme() {
  const savedTheme = localStorage.getItem("theme") || "dark";
  
  document.documentElement.setAttribute("data-theme", savedTheme);
  
  if (savedTheme === "dark") {
    themeIcon.className = "fas fa-moon";
    themeLabel.textContent = "Dark Mode";
  } else {
    themeIcon.className = "fas fa-sun";
    themeLabel.textContent = "Light Mode";
  }
}

// Event Listeners
themeToggle.addEventListener("click", toggleTheme);

qrtext.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    GenerateQR();
  }
});

// Add download button event listeners
downloadPng.addEventListener("click", downloadAsPNG);
downloadJpg.addEventListener("click", downloadAsJPG);
downloadPdf.addEventListener("click", downloadAsPDF);

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
  initializeTheme();
  
  // Add tooltips to download buttons
  downloadPng.title = "Download as PNG (Best quality, transparent background)";
  downloadJpg.title = "Download as JPG (Smaller file size, good for web)";
  downloadPdf.title = "Download as PDF (Professional format with metadata)";
  
  // Focus on input field
  qrtext.focus();
});
</script>
</body>
</html>
<?php
// End output buffering and send content
ob_end_flush();
?>