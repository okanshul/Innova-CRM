let waitingWorker = null;

export function notifyUpdateAvailable(worker) {
    waitingWorker = worker;
    const updateToast = document.getElementById('pwaUpdateToast');
    const updateBtn = document.getElementById('pwaUpdateBtn');

    if (updateToast) {
        updateToast.classList.remove('d-none');
    }

    if (updateBtn) {
        updateBtn.onclick = () => {
            if (waitingWorker) {
                waitingWorker.postMessage({ type: 'SKIP_WAITING' });
            }
        };
    }
}

export function handleControllerChange() {
    let refreshing = false;
    navigator.serviceWorker.addEventListener('controllerchange', () => {
        if (!refreshing) {
            refreshing = true;
            console.log('[PWA] Controller changed, reloading application...');
            window.location.reload();
        }
    });
}
