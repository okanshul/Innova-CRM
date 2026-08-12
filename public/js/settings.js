document.addEventListener('DOMContentLoaded', function () {
    const settingsForm = document.getElementById('settingsForm');
    const settingsAlert = document.getElementById('settingsAlert');

    if (settingsForm) {
        settingsForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(settingsForm);

            fetch(settingsForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && settingsAlert) {
                    settingsAlert.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
                            <i class="fa-solid fa-circle-check me-1.5"></i> ${res.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `;
                }
            })
            .catch(err => console.error('Error saving settings:', err));
        });
    }
});
