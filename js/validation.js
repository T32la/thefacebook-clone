// Validación de email universitario en el cliente
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;
            const agree = document.querySelector('input[name="agree"]').checked;

            // Lista de dominios universitarios permitidos
            const universityDomains = [
                'uvg.edu.gt',
                'usac.edu.gt',
                'url.edu.gt',
                'ufm.edu',
                'harvard.edu',
                'mit.edu',
                'stanford.edu',
                'yale.edu',
                'princeton.edu'
            ];

            // Dominios personales no permitidos
            const personalDomains = [
                'gmail.com',
                'hotmail.com',
                'yahoo.com',
                'outlook.com',
                'live.com',
                'icloud.com',
                'aol.com'
            ];

            const emailDomain = email.split('@')[1];

            // Verificar si es un dominio personal
            if (personalDomains.includes(emailDomain)) {
                e.preventDefault();
                alert('You must use a university email address. Personal emails like Gmail, Hotmail, Yahoo, etc. are not allowed.');
                return false;
            }

            // Verificar si el dominio contiene .edu
            if (!emailDomain.includes('.edu')) {
                e.preventDefault();
                alert('Please use a valid university email address (must contain .edu domain)');
                return false;
            }

            // Verificar contraseña
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
                return false;
            }

            // Verificar checkbox
            if (!agree) {
                e.preventDefault();
                alert('You must agree to the Terms of Use');
                return false;
            }
        });

        // Validación en tiempo real del email
        const emailInput = document.querySelector('input[name="email"]');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const email = this.value;
                const emailDomain = email.split('@')[1];

                const personalDomains = [
                    'gmail.com',
                    'hotmail.com',
                    'yahoo.com',
                    'outlook.com',
                    'live.com',
                    'icloud.com',
                    'aol.com'
                ];

                if (email && personalDomains.includes(emailDomain)) {
                    this.setCustomValidity('Must use university email');
                    this.classList.add('is-invalid');
                } else if (email && !emailDomain.includes('.edu')) {
                    this.setCustomValidity('Must use university email (.edu domain)');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                    if (email) {
                        this.classList.add('is-valid');
                    }
                }
            });
        }
    }
});
