const puppeteer = require('puppeteer-core');
const fs = require('fs');

(async () => {
  const edgePath = 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe';
  const chromePath = 'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe';
  let execPath = fs.existsSync(edgePath) ? edgePath : chromePath;

  const browser = await puppeteer.launch({ executablePath: execPath, headless: "new" });
  const page = await browser.newPage();
  await page.setViewport({ width: 1280, height: 800 });
  
  // Navigate and set session storage BEFORE page finishes loading if possible
  await page.goto('http://127.0.0.1:8000', { waitUntil: 'domcontentloaded' });
  await page.evaluate(() => sessionStorage.setItem('openingPlayed', 'true'));
  
  // Reload page to apply session storage
  await page.reload({ waitUntil: 'networkidle0' });
  
  // Scroll to bottom
  await page.evaluate(() => {
      window.scrollTo(0, document.body.scrollHeight);
  });
  await new Promise(r => setTimeout(r, 2000));
  
  await page.screenshot({ path: 'screenshot_kontak.png' });
  await browser.close();
})();
