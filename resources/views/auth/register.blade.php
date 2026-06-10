<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                type="password"
                name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password_confirmation');

            // Helper function to create or grab an immediate error message container
            const createErrorPlaceholder = (inputEl, id) => {
                if (!document.getElementById(id)) {
                    const small = document.createElement('small');
                    small.id = id;
                    small.style.color = '#ef4444'; // Clean Tailwind red
                    small.style.display = 'block';
                    small.style.marginTop = '4px';
                    small.style.fontSize = '0.875rem';
                    inputEl.parentNode.appendChild(small);
                }
                return document.getElementById(id);
            };

            // 1. Immediate Name/Username Validation
            nameInput.addEventListener('input', function() {
                const errorEl = createErrorPlaceholder(nameInput, 'js-reg-name-error');
                const val = nameInput.value;
                const startsWithNumber = /^[0-9]/.test(val);
                const startsWithSpecial = /^[\W_]/.test(val);

                if (val.trim() === "") {
                    errorEl.textContent = "Name is required.";
                } else if (startsWithNumber) {
                    errorEl.textContent = "The username must not start with a numeric character.";
                } else if (startsWithSpecial) {
                    errorEl.textContent = "The username must not start with a special character.";
                } else {
                    errorEl.textContent = "";
                }
            });

            // 2. Immediate Email Validation
            emailInput.addEventListener('input', function() {
                const errorEl = createErrorPlaceholder(emailInput, 'js-reg-email-error');
                const val = emailInput.value;

                // This regex ensures:
                // 1. Starts with alphabets/periods/dashes: ^[a-zA-Z._-]+
                // 2. Followed strictly by at least one number: [0-9]+
                // 3. Followed by the @ symbol and domain: @[^\s@]+\.[^\s@]+$
                const formalEmailPattern = /^[a-zA-Z._-]+[0-9]+@[^\s@]+\.[^\s@]+$/;

                if (val.trim() === "") {
                    errorEl.textContent = "Email address is required.";
                } else if (!/[0-9].*@/.test(val)) {
                    // Clear, helpful hint for the user while typing
                    errorEl.textContent = "Email format invalid. Numbers must appear after your name and before the @ symbol.";
                } else if (!formalEmailPattern.test(val)) {
                    errorEl.textContent = "Please enter a valid formal email address (e.g., name123@domain.com).";
                } else {
                    errorEl.textContent = "";
                }
            });

            // 3. Immediate Strict Password Validation
            passwordInput.addEventListener('input', function() {
                const errorEl = createErrorPlaceholder(passwordInput, 'js-reg-password-error');
                const val = passwordInput.value;

                const hasLength = val.length >= 8;
                const hasUpper = /[A-Z]/.test(val);
                const hasLower = /[a-z]/.test(val);
                const hasNumber = /[0-9]/.test(val);
                const hasSpecial = /[\W_]/.test(val);

                if (val.trim() === "") {
                    errorEl.textContent = "Password is required.";
                } else if (!hasLength) {
                    errorEl.textContent = "Password must be at least 8 characters long.";
                } else if (!hasUpper || !hasLower) {
                    errorEl.textContent = "Password must contain both uppercase and lowercase letters.";
                } else if (!hasNumber) {
                    errorEl.textContent = "Password must contain at least one number.";
                } else if (!hasSpecial) {
                    errorEl.textContent = "Password must contain a special character (e.g., !, @, #, $, %).";
                } else {
                    errorEl.textContent = "";
                }

                // Trigger password match check immediately if confirmation already has text
                if (confirmInput.value.trim() !== "") {
                    confirmInput.dispatchEvent(new Event('input'));
                }
            });

            // 4. Immediate Password Confirmation Check
            confirmInput.addEventListener('input', function() {
                const errorEl = createErrorPlaceholder(confirmInput, 'js-reg-confirm-error');

                if (confirmInput.value !== passwordInput.value) {
                    errorEl.textContent = "The password confirmation does not match.";
                } else {
                    errorEl.textContent = "";
                }
            });
        });
    </script>
</x-guest-layout>