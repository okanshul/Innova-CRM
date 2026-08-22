document.addEventListener('DOMContentLoaded', function () {
    const settingsForm = document.getElementById('settingsForm');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Clear Validation Errors Helper
    const clearValidationErrors = (form) => {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback.dynamic-error').forEach(el => el.remove());
    };

    // Render Inline Validation Errors Helper
    const renderValidationErrors = (form, errors) => {
        clearValidationErrors(form);
        Object.keys(errors).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.classList.add('is-invalid');
                const feedbackBox = document.createElement('div');
                feedbackBox.className = 'invalid-feedback d-block ps-2 dynamic-error';
                feedbackBox.textContent = errors[key][0];
                input.parentNode.appendChild(feedbackBox);
            }
        });
    };

    // 1. Settings Form Submit Handler
    if (settingsForm) {
        settingsForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitBtn = settingsForm.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn ? submitBtn.innerHTML : '';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...`;
            }

            clearValidationErrors(settingsForm);
            const formData = new FormData(settingsForm);

            fetch(settingsForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        if (res.status === 422 && data.errors) {
                            renderValidationErrors(settingsForm, data.errors);
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: data.message || 'Please check the highlighted fields.',
                                    toast: true,
                                    position: 'top-end',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        } else {
                            throw new Error(data.message || 'Server error occurred.');
                        }
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Settings Saved',
                                text: data.message || 'System settings updated successfully.',
                                timer: 1500,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                    }
                })
                .catch(err => {
                    console.error('Error saving settings:', err);
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Save Failed',
                            text: err.message || 'An unexpected error occurred while saving.',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHtml;
                    }
                });
        });
    }

    // 2. Send Test Email Button Handler
    const btnTestEmail = document.getElementById('btnTestEmail');
    if (btnTestEmail) {
        btnTestEmail.addEventListener('click', function () {
            const originalHtml = btnTestEmail.innerHTML;
            btnTestEmail.disabled = true;
            btnTestEmail.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Sending...`;

            const smtpData = {
                smtp_driver: settingsForm.querySelector('[name="smtp_driver"]')?.value,
                smtp_host: settingsForm.querySelector('[name="smtp_host"]')?.value,
                smtp_port: settingsForm.querySelector('[name="smtp_port"]')?.value,
                smtp_encryption: settingsForm.querySelector('[name="smtp_encryption"]')?.value,
                smtp_username: settingsForm.querySelector('[name="smtp_username"]')?.value,
                smtp_password: settingsForm.querySelector('[name="smtp_password"]')?.value,
            };

            fetch('/api/settings/test-email', {
                method: 'POST',
                body: JSON.stringify(smtpData),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Test email failed.');
                    Swal.fire({
                        icon: 'success',
                        title: 'Test Email Sent',
                        text: data.message,
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Email Failed',
                        text: err.message,
                        toast: true,
                        position: 'top-end',
                        timer: 4000,
                        showConfirmButton: false
                    });
                })
                .finally(() => {
                    btnTestEmail.disabled = false;
                    btnTestEmail.innerHTML = originalHtml;
                });
        });
    }

    // 3. Logo & Favicon File Previews
    const setupImagePreview = (inputId, previewBoxId) => {
        const input = document.getElementById(inputId);
        const box = document.getElementById(previewBoxId);
        if (input && box) {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        box.innerHTML = `<img src="${e.target.result}" alt="Preview" class="img-fluid" style="max-height: 30px;">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    };

    setupImagePreview('appearanceLogoInput', 'appearanceLogoPreviewBox');
    setupImagePreview('appearanceFaviconInput', 'appearanceFaviconPreviewBox');

    // Remove Logo Handler
    const btnRemoveLogo = document.getElementById('btnRemoveLogo');
    if (btnRemoveLogo) {
        btnRemoveLogo.addEventListener('click', function () {
            const removeInput = document.getElementById('removeSystemLogoInput');
            if (removeInput) removeInput.value = '1';
            const box = document.getElementById('appearanceLogoPreviewBox');
            const appName = document.querySelector('input[name="app_name"]')?.value || 'InnovaCRM';
            if (box) {
                box.innerHTML = `<div class="d-flex align-items-center gap-2">
                    <div class="brand-icon rounded-3 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 44px; height: 44px; background: #5030FF;">
                        <i class="fa-solid fa-layer-group fs-5"></i>
                    </div>
                    <span class="fw-bold fs-4 text-body-emphasis tracking-tight">${appName}</span>
                </div>`;
            }
            btnRemoveLogo.style.display = 'none';
        });
    }

    // Remove Favicon Handler
    const btnRemoveFavicon = document.getElementById('btnRemoveFavicon');
    if (btnRemoveFavicon) {
        btnRemoveFavicon.addEventListener('click', function () {
            const removeInput = document.getElementById('removeFaviconInput');
            if (removeInput) removeInput.value = '1';
            btnRemoveFavicon.style.display = 'none';
        });
    }

    // 4. Color Picker Sync
    const setupColorPicker = (textInputId, pickerId, swatchId) => {
        const textInput = document.getElementById(textInputId);
        const pickerInput = document.getElementById(pickerId);
        const swatch = document.getElementById(swatchId);

        if (textInput && pickerInput && swatch) {
            pickerInput.addEventListener('input', function (e) {
                const hex = e.target.value.toUpperCase();
                textInput.value = hex;
                swatch.style.backgroundColor = hex;
            });

            textInput.addEventListener('input', function (e) {
                let hex = e.target.value.trim();
                if (hex && !hex.startsWith('#')) {
                    hex = '#' + hex;
                }
                if (/^#([0-9A-F]{3}){1,2}$/i.test(hex)) {
                    pickerInput.value = hex;
                    swatch.style.backgroundColor = hex;
                }
            });
        }
    };

    setupColorPicker('primaryColorText', 'primaryColorPicker', 'primaryColorSwatch');
    setupColorPicker('secondaryColorText', 'secondaryColorPicker', 'secondaryColorSwatch');
    setupColorPicker('sidebarBgColorText', 'sidebarBgColorPicker', 'sidebarBgColorSwatch');

    // Reset Settings Handler
    const btnResetSettings = document.getElementById('btnResetSettings');
    if (btnResetSettings) {
        btnResetSettings.addEventListener('click', function () {
            const doReset = () => {
                btnResetSettings.disabled = true;
                btnResetSettings.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-1"></i> Resetting...`;

                fetch('/api/settings/reset', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) {
                            throw new Error(data.message || 'Failed to reset settings.');
                        }
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Settings Reset',
                                text: data.message || 'System settings have been reset to default values.',
                                timer: 1500,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                        setTimeout(() => {
                            window.location.reload();
                        }, 1200);
                    })
                    .catch(err => {
                        console.error('Error resetting settings:', err);
                        btnResetSettings.disabled = false;
                        btnResetSettings.innerHTML = `<i class="fa-solid fa-rotate-left me-1"></i> Reset Settings`;
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Reset Failed',
                                text: err.message || 'An error occurred while resetting settings.',
                                toast: true,
                                position: 'top-end',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            alert('Reset Failed: ' + err.message);
                        }
                    });
            };

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Reset All Settings?',
                    text: 'Are you sure you want to reset all system settings to default values? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Reset Settings',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        doReset();
                    }
                });
            } else if (confirm('Are you sure you want to reset all system settings to default values?')) {
                doReset();
            }
        });
    }


    // 6. Backup & Restore Handler
    const backupHistoryTableBody = document.getElementById('backupHistoryTableBody');
    const btnCreateBackup = document.getElementById('btnCreateBackup');

    const loadBackups = () => {
        if (!backupHistoryTableBody) return;
        backupHistoryTableBody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-secondary"><i class="fa-solid fa-spinner fa-spin me-2"></i> Loading backups...</td></tr>`;

        fetch('/api/backups', { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    if (res.data.length === 0) {
                        backupHistoryTableBody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-muted">No backups found.</td></tr>`;
                        return;
                    }
                    backupHistoryTableBody.innerHTML = res.data.map(b => {
                        const sizeFormatted = formatFileSize(b.size);
                        const createdAt = new Date(b.created_at).toLocaleString();

                        return `
                            <tr>
                                <td class="ps-3 py-2 fw-semibold fs-7"><i class="fa-solid fa-file-zipper me-2 text-primary"></i>${b.filename}</td>
                                <td class="py-2 fs-7 text-secondary">${sizeFormatted}</td>
                                <td class="py-2 fs-7 text-secondary">${createdAt}</td>
                                <td class="pe-3 py-2 text-end">
                                    <a href="/api/backups/${b.id}/download" class="btn btn-sm btn-icon btn-ghost-primary rounded-circle" title="Download"><i class="fa-solid fa-download"></i></a>
                                    <button type="button" class="btn btn-sm btn-icon btn-ghost-danger rounded-circle btn-delete-backup" data-id="${b.id}" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                                </td>
                            </tr>
                        `;
                    }).join('');

                    backupHistoryTableBody.querySelectorAll('.btn-delete-backup').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const id = this.dataset.id;
                            Swal.fire({
                                title: 'Delete Backup?',
                                text: "Are you sure you want to delete this backup zip?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'Yes, Delete'
                            }).then(result => {
                                if (result.isConfirmed) {
                                    fetch(`/api/backups/${id}`, {
                                        method: 'DELETE',
                                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                                    })
                                        .then(res => res.json())
                                        .then(res => {
                                            if (res.success) {
                                                Swal.fire({ icon: 'success', title: 'Deleted', text: res.message, toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                                                loadBackups();
                                            }
                                        });
                                }
                            });
                        });
                    });
                }
            });
    };

    if (btnCreateBackup) {
        btnCreateBackup.addEventListener('click', function () {
            const orig = btnCreateBackup.innerHTML;
            btnCreateBackup.disabled = true;
            btnCreateBackup.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Backing up...`;

            fetch('/api/backups', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Backup Created', text: res.message, toast: true, position: 'top-end', timer: 2500, showConfirmButton: false });
                        loadBackups();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Backup Failed', text: res.message });
                    }
                })
                .finally(() => {
                    btnCreateBackup.disabled = false;
                    btnCreateBackup.innerHTML = orig;
                });
        });
    }

    const restoreFileInput = document.getElementById('restoreFileInput');
    const restoreDropzone = document.getElementById('restoreDropzone');

    const handleRestoreFile = (file) => {
        if (!file || !file.name.endsWith('.zip')) {
            Swal.fire({ icon: 'error', title: 'Invalid File', text: 'Please select a valid .zip backup file.' });
            return;
        }

        Swal.fire({
            title: 'Restore System Backup?',
            text: 'This will overwrite system settings and uploads. Continue?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Restore'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('backup_file', file);

                Swal.fire({ title: 'Restoring System...', text: 'Please wait while backup is processed.', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                fetch('/api/backups/restore', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Swal.fire({ icon: 'success', title: 'Restored', text: res.message }).then(() => window.location.reload());
                        } else {
                            Swal.fire({ icon: 'error', title: 'Restore Failed', text: res.message });
                        }
                    });
            }
        });
    };

    if (restoreFileInput) {
        restoreFileInput.addEventListener('change', function () {
            if (this.files[0]) handleRestoreFile(this.files[0]);
        });
    }

    if (restoreDropzone) {
        restoreDropzone.addEventListener('dragover', e => { e.preventDefault(); restoreDropzone.classList.add('border-primary'); });
        restoreDropzone.addEventListener('dragleave', e => { e.preventDefault(); restoreDropzone.classList.remove('border-primary'); });
        restoreDropzone.addEventListener('drop', e => {
            e.preventDefault();
            restoreDropzone.classList.remove('border-primary');
            if (e.dataTransfer.files[0]) handleRestoreFile(e.dataTransfer.files[0]);
        });
    }

    // 7. System Info Handler
    const loadSystemInfo = () => {
        fetch('/api/system-info', { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const d = res.data;
                    document.getElementById('infoAppVersion').textContent = d.app_version;
                    document.getElementById('infoPhpVersion').textContent = d.php_version;
                    document.getElementById('infoLaravelVersion').textContent = d.laravel_version;
                    document.getElementById('infoEnv').textContent = d.environment;
                    document.getElementById('infoDebugMode').textContent = d.debug_mode;
                    document.getElementById('infoDbDriver').textContent = d.db_driver;
                    document.getElementById('infoServerSoftware').textContent = d.server_software;
                    document.getElementById('infoOs').textContent = d.operating_system;
                    document.getElementById('infoServerTime').textContent = d.server_time;
                    document.getElementById('infoTimezone').textContent = d.timezone;
                    document.getElementById('infoMemoryUsage').textContent = d.memory_usage;
                    document.getElementById('infoDiskUsage').textContent = d.disk_usage;
                }
            });
    };

    // 8. Audit Log Handler
    const auditLogTableBody = document.getElementById('auditLogTableBody');
    const auditActionFilter = document.getElementById('auditActionFilter');

    const loadAuditLogs = (action = 'all') => {
        if (!auditLogTableBody) return;
        auditLogTableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-secondary"><i class="fa-solid fa-spinner fa-spin me-2"></i> Loading audit log...</td></tr>`;

        fetch(`/api/audit-logs?action=${action}`, { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    if (res.data.length === 0) {
                        auditLogTableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">No audit logs recorded.</td></tr>`;
                        return;
                    }
                    auditLogTableBody.innerHTML = res.data.map(log => {
                        const timeStr = new Date(log.created_at).toLocaleString();
                        const userName = log.user ? log.user.name : 'System / Guest';
                        let badgeClass = 'bg-info-subtle text-info';
                        if (log.action === 'Created') badgeClass = 'bg-success-subtle text-success';
                        if (log.action === 'Deleted') badgeClass = 'bg-danger-subtle text-danger';
                        if (log.action === 'Login') badgeClass = 'bg-primary-subtle text-primary';

                        return `
                            <tr>
                                <td class="ps-4 py-3 fs-8 text-secondary">${timeStr}</td>
                                <td class="fw-semibold fs-7">${userName}</td>
                                <td><span class="badge ${badgeClass} rounded-pill px-3 py-1">${log.action}</span></td>
                                <td class="fs-7">${log.module}</td>
                                <td class="pe-4 text-end fs-8 text-secondary">${log.ip_address || '127.0.0.1'}</td>
                            </tr>
                        `;
                    }).join('');
                }
            });
    };

    if (auditActionFilter) {
        auditActionFilter.addEventListener('change', function () {
            loadAuditLogs(this.value);
        });
    }

    // 9. Lazy Load Tabs & URL Hash Handling (localStorage persistence removed)
    localStorage.removeItem('settingsActiveTab');

    const tabNavButtons = document.querySelectorAll('.settings-vnav .nav-link, #settings-tab button, [data-bs-toggle="pill"]');

    const triggerTabLoad = (targetId) => {
        if (targetId === '#v-pills-backup') loadBackups();
        if (targetId === '#v-pills-system') loadSystemInfo();
        if (targetId === '#v-pills-audit') loadAuditLogs();
    };

    tabNavButtons.forEach(btn => {
        const handler = () => {
            const targetId = btn.getAttribute('data-bs-target') || btn.getAttribute('href');
            if (targetId) {
                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, null, targetId);
                }
                triggerTabLoad(targetId);
            }
        };
        btn.addEventListener('shown.bs.tab', handler);
        btn.addEventListener('click', handler);
    });

    // Check URL hash for active tab on page load
    const savedTab = window.location.hash;
    let tabActivated = false;

    if (savedTab && savedTab.startsWith('#v-pills-')) {
        const targetBtn = document.querySelector(`.settings-vnav [data-bs-target="${savedTab}"], #settings-tab [data-bs-target="${savedTab}"]`);
        if (targetBtn) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(targetBtn);
            tabInstance.show();
            triggerTabLoad(savedTab);
            tabActivated = true;
        }
    }

    if (!tabActivated) {
        const activeTab = document.querySelector('.settings-vnav .nav-link.active, #settings-tab button.active, [data-bs-toggle="pill"].active');
        if (activeTab) {
            const targetId = activeTab.getAttribute('data-bs-target') || activeTab.getAttribute('href');
            if (targetId) triggerTabLoad(targetId);
        }
    }
});
