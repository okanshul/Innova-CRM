document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('profileAvatarPreview');

    // Handle instant avatar image preview when a new file is chosen
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    avatarPreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Bind Profile Update Form AJAX Submit
    if (typeof bindFormSubmit === 'function') {
        bindFormSubmit({
            formId: 'profileUpdateForm',
            url: (form) => form.action,
            method: 'POST',
            onSuccess: (data) => {
                if (data.data && data.data.name) {
                    const nameDisplay = document.getElementById('profileDisplayName');
                    if (nameDisplay) nameDisplay.textContent = data.data.name;
                }
            }
        });

        // Bind Password Update Form AJAX Submit
        bindFormSubmit({
            formId: 'passwordUpdateForm',
            url: (form) => form.action,
            method: 'POST',
            onSuccess: () => {
                const form = document.getElementById('passwordUpdateForm');
                if (form) form.reset();
            }
        });
    }
});
