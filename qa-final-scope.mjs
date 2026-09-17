export default async function run(page, ui) {
    const email = 'scope-1789627178445@example.com';
    const password = 'Password123!';
    const results = {};

    // Login as the seeded scope test user
    await page.goto('http://127.0.0.1:8000/login');
    await page.fill('input[name="email"]', email);
    await page.fill('input[name="password"]', password);
    await page.click('button[type=submit]');
    await page.waitForLoadState('networkidle');
    results.login = { url: page.url(), ok: !page.url().includes('/login') };

    if (!results.login.ok) {
        results.error = 'Login failed — password unknown for seeded user';
        return results;
    }

    // Vaccinations page: pet dropdown should contain only Bella
    await page.goto('http://127.0.0.1:8000/vaccinations');
    await page.waitForLoadState('networkidle');
    const vacOptions = await page.evaluate(() =>
        Array.from(document.querySelectorAll('select[name="pet_id"] option'))
            .map(o => ({ value: o.value, text: o.textContent.trim() }))
            .filter(o => o.value !== '')
    );
    results.vaccinationsDropdown = vacOptions;
    results.vaccinationsOk = vacOptions.length === 1 && vacOptions[0].text.includes('Bella');

    // Deworming page: pet dropdown should contain only Bella
    await page.goto('http://127.0.0.1:8000/deworming');
    await page.waitForLoadState('networkidle');
    const dewOptions = await page.evaluate(() =>
        Array.from(document.querySelectorAll('select[name="pet_id"] option'))
            .map(o => ({ value: o.value, text: o.textContent.trim() }))
            .filter(o => o.value !== '')
    );
    results.dewormingDropdown = dewOptions;
    results.dewormingOk = dewOptions.length === 1 && dewOptions[0].text.includes('Bella');

    return results;
}
