export default async function run(page, ui) {
    // Login as User B (the qa test account with no pets)
    await page.goto('http://127.0.0.1:8000/login');
    await page.fill('#email', 'userb-1789621231073@example.com');
    await page.fill('#password', 'password123');
    await page.click('.auth-submit');
    await page.waitForLoadState('networkidle');

    // Try to open User A's pet (Rex, id 10) directly by URL
    const resp = await page.goto('http://127.0.0.1:8000/pets/10');
    const bodyText = await page.locator('body').innerText();
    return { status: resp.status(), blocked: resp.status() === 403, message: bodyText.slice(0, 200) };
}
