import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        const input = document.querySelector(button.dataset.passwordToggle);
        const icon = button.querySelector('i');

        if (!input) {
            return;
        }

        button.addEventListener('click', () => {
            const revealed = input.type === 'text';
            input.type = revealed ? 'password' : 'text';

            if (icon) {
                icon.classList.toggle('bi-eye', revealed);
                icon.classList.toggle('bi-eye-slash', !revealed);
            }

            button.setAttribute('aria-label', revealed ? 'Show password' : 'Hide password');
            input.focus({ preventScroll: true });
        });
    });
});
