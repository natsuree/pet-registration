export default async function run(page, ui) {
    const results = {};

    // 1. Valid submission with today's date -> should create account and redirect to dashboard
    await page.goto('http://127.0.0.1:8000/register');
    await page.fill('#full_name', 'QA Test User');
    await page.fill('#register_email', 'qa-dob-test-' + Date.now() + '@example.com');
    await page.fill('#contact_number', '0917 000 0000');
    await page.fill('#date_of_birth', new Date().toISOString().slice(0, 10));
    await page.fill('#register_password', 'password123');
    await page.fill('#confirm_password', 'password123');
    await page.click('button[type=submit]');
    await page.waitForLoadState('networkidle');
    results.validSubmission = { url: page.url(), ok: page.url().includes('/dashboard') };

    // Log out to reset session
    await page.evaluate(() => fetch('/logout', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }, body: '_token=' + (document.body.innerHTML.match(/name="_token" value="([^"]+)"/)?.[1] || '') })).catch(() => { });

    // 2. Future date -> should be rejected server-side and bounce back to /register with errors
    await page.goto('http://127.0.0.1:8000/register');
    await page.fill('#full_name', 'QA Future');
    await page.fill('#register_email', 'qa-future-' + Date.now() + '@example.com');
    await page.fill('#contact_number', '0917 000 0001');
    await page.fill('#date_of_birth', '2030-01-01');
    await page.fill('#register_password', 'password123');
    await page.fill('#confirm_password', 'password123');
    // Force the browser to allow the future date (as a crafted request would)
    await page.evaluate(() => { document.getElementById('date_of_birth').max = ''; });
    await page.click('button[type=submit]');
    await page.waitForLoadState('networkidle');
    const errText = await page.locator('.alert-danger').first().innerText().catch(() => null);
    results.futureDateRejected = { url: page.url(), error: errText, rejected: page.url().includes('/register') && !!errText };

    return results;
}
