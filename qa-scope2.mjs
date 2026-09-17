export default async function run(page, ui) {
    const results = {};

    // Log in as geo@gmail.com (owns "meow", id 9)
    await page.goto('http://127.0.0.1:8000/login');
    await page.fill('#email', 'geo@gmail.com');
    await page.fill('#password', 'password123'); // may fail if unknown; check below
    await page.click('.auth-submit');
    await page.waitForLoadState('networkidle');
    results.loginUrl = page.url();
    if (!page.url().includes('/dashboard')) {
        return { ...results, note: 'geo@gmail.com password unknown — cannot test as real owner. Skipping.' };
    }

    // Vaccinations: dropdown should show ONLY meow
    await page.goto('http://127.0.0.1:8000/vaccinations');
    await page.waitForLoadState('networkidle');
    const vacModal = page.locator('#addVaccination');
    results.vaccinations = {
        modalPresent: await vacModal.count() > 0,
        options: await vacModal.locator('select[name=pet_id] option').allInnerTexts(),
        tableMentionsRex: (await page.locator('.content-wrap').innerText()).includes('Rex'),
    };

    // Deworming: dropdown should show ONLY meow
    await page.goto('http://127.0.0.1:8000/deworming');
    await page.waitForLoadState('networkidle');
    const dwModal = page.locator('#addDeworming');
    results.deworming = {
        modalPresent: await dwModal.count() > 0,
        options: await dwModal.locator('select[name=pet_id] option').allInnerTexts(),
        tableMentionsRex: (await page.locator('.content-wrap').innerText()).includes('Rex'),
    };

    // Forged POST: try to save a record against Rex (id 10, owned by usera)
    const forged = await page.evaluate(() => {
        return fetch('/vaccinations', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                _token: document.querySelector('#addVaccination input[name=_token]').value,
                pet_id: '10',
                vaccine: 'FORGED',
                administered_at: '2026-01-01',
            }),
        }).then(r => r.status);
    });
    results.forgedPostStatus = forged; // expect 403

    // Legit save: record against own pet should succeed (302 -> redirect)
    const legit = await page.evaluate(() => {
        return fetch('/vaccinations', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                _token: document.querySelector('#addVaccination input[name=_token]').value,
                pet_id: '9',
                vaccine: 'Legit Test Vaccine',
                administered_at: '2026-01-01',
            }),
        }).then(r => r.status);
    });
    results.legitPostStatus = legit; // expect 302 (redirect on success)

    return results;
}
