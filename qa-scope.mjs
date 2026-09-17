export default async function run(page, ui) {
    const results = {};
    const ownerEmail = 'scope-1789627178445@example.com';

    // Log in as the existing scope test owner (owns Bella, id 12)
    await page.goto('http://127.0.0.1:8000/login');
    await page.fill('#email', ownerEmail);
    await page.fill('#password', 'password123');
    await page.click('.auth-submit');
    await page.waitForLoadState('networkidle');
    results.loginUrl = page.url();

    // --- Vaccinations page ---
    await page.goto('http://127.0.0.1:8000/vaccinations');
    await page.waitForLoadState('networkidle');
    const vacModal = page.locator('#addVaccination');
    results.vaccinations = {
        modalPresent: await vacModal.count() > 0,
        options: await vacModal.locator('select[name=pet_id] option').allInnerTexts(),
        tableMentionsRex: (await page.locator('.content-wrap').innerText()).includes('Rex'),
    };

    // --- Deworming page ---
    await page.goto('http://127.0.0.1:8000/deworming');
    await page.waitForLoadState('networkidle');
    const dwModal = page.locator('#addDeworming');
    results.deworming = {
        modalPresent: await dwModal.count() > 0,
        options: await dwModal.locator('select[name=pet_id] option').allInnerTexts(),
        tableMentionsRex: (await page.locator('.content-wrap').innerText()).includes('Rex'),
    };

    // --- Legit save: vaccination against own pet (Bella, id 12) ---
    const csrf = await page.evaluate(() => document.querySelector('#addVaccination input[name=_token]').value);
    results.legitPostStatus = await page.evaluate((csrf) => fetch('/vaccinations', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            _token: csrf,
            pet_id: '12',
            vaccine: 'Scope Test Vaccine',
            administered_at: '2026-01-01',
        }),
    }).then(r => r.status), csrf); // expect 302

    // --- Forged save: vaccination against Rex (id 10, another user's pet) ---
    results.forgedVaccStatus = await page.evaluate((csrf) => fetch('/vaccinations', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            _token: csrf,
            pet_id: '10',
            vaccine: 'FORGED',
            administered_at: '2026-01-01',
        }),
    }).then(r => r.status), csrf); // expect 403

    // --- Forged save: deworming against Rex ---
    results.forgedDewormingStatus = await page.evaluate((csrf) => fetch('/deworming', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            _token: csrf,
            pet_id: '10',
            product: 'FORGED',
            administered_at: '2026-01-01',
        }),
    }).then(r => r.status), csrf); // expect 403

    // Verify: legit record exists, forged record does not
    await page.goto('http://127.0.0.1:8000/vaccinations');
    await page.waitForLoadState('networkidle');
    const vacText = await page.locator('.content-wrap').innerText();
    results.vaccTableHasForged = vacText.includes('FORGED');
    results.vaccTableHasScopeTest = vacText.includes('Scope Test Vaccine');

    return results;
}
