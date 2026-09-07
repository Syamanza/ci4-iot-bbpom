const puppeteer = require('puppeteer');
const fs = require('fs');

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    const htmlContent = fs.readFileSync('C:/laragon/www/ci4-spbe/report.html', 'utf8');
    await page.setContent(htmlContent, { waitUntil: 'networkidle0' });
    await page.pdf({ 
        path: 'C:/laragon/www/ci4-spbe/Progress_Project_SPBE.pdf', 
        format: 'A4',
        margin: { top: '20px', right: '20px', bottom: '20px', left: '20px' }
    });
    await browser.close();
    console.log('PDF Created');
})();
