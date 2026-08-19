document.addEventListener('DOMContentLoaded', function () {
    const settingsForm = document.getElementById('settingsForm');
    const settingsAlert = document.getElementById('settingsAlert');

    if (settingsForm) {
        settingsForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = settingsForm.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...`;
            }

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
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                }

                if (res.success) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Settings Saved',
                            text: res.message || 'System settings updated successfully.',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }

                    if (settingsAlert) {
                        settingsAlert.innerHTML = `
                            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                                <i class="fa-solid fa-circle-check me-2"></i> ${res.message || 'Settings saved successfully.'}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            })
            .catch(err => {
                console.error('Error saving settings:', err);
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;
                }
            });
        });
    }

    // Logo & Favicon File Previews
    const setupImagePreview = (inputId, previewBoxId) => {
        const input = document.getElementById(inputId);
        const box = document.getElementById(previewBoxId);
        if (input && box) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        box.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="max-height: 40px;">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    };

    setupImagePreview('systemLogoInput', 'logoPreviewBox');
    setupImagePreview('faviconInput', 'faviconPreviewBox');
});

