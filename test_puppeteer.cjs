const puppeteer = require('puppeteer');
(async () => {
    try {
        const browser = await puppeteer.launch({ headless: 'new' });
        const page = await browser.newPage();
        
        page.on('console', msg => console.log('BROWSER_LOG:', msg.text()));
        page.on('pageerror', error => console.log('BROWSER_ERROR:', error.message));
        page.on('requestfailed', request => console.log('BROWSER_REQ_FAILED:', request.url(), request.failure().errorText));

        await page.goto('http://127.0.0.1:8081/', { waitUntil: 'networkidle0' });
        
        // Let's scroll a bit to trigger Lenis/ScrollTrigger
        await page.evaluate(() => window.scrollBy(0, 500));
        await new Promise(r => setTimeout(r, 1000));
        
        await browser.close();
    } catch (e) {
        console.error("Puppeteer Script Error:", e);
    }
})();
