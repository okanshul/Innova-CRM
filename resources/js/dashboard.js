import Chart from 'chart.js/auto';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {

    // Sidebar Toggle Logic
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarToggle = document.getElementById('sidebarToggle') || document.getElementById('sidebar-toggle');

    let tooltipInstances = [];

    function initTooltips() {
        const tooltipTriggerList = document.querySelectorAll('#sidebar [data-bs-toggle="tooltip"]');
        tooltipInstances = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el, {
            trigger: 'hover',
            placement: 'right',
            boundary: 'window'
        }));
    }

    function updateTooltipsState(isCollapsed) {
        const isDesktop = window.innerWidth >= 992;
        tooltipInstances.forEach(instance => {
            if (isCollapsed && isDesktop) {
                instance.enable();
            } else {
                instance.disable();
                instance.hide();
            }
        });
    }

    // Check stored state and apply on desktop
    const isStoredCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isStoredCollapsed && window.innerWidth >= 992) {
        document.body.classList.add('sidebar-collapsed');
        if (sidebar) sidebar.classList.add('sidebar-collapsed');
        if (mainContent) mainContent.classList.add('content-expanded');
    }

    initTooltips();
    updateTooltipsState(isStoredCollapsed);

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', (e) => {
            e.preventDefault();

            if (window.innerWidth < 992) {
                // Mobile/tablet offcanvas drawer
                if (sidebar) {
                    const bsOffcanvas = bootstrap.Offcanvas.getOrCreateInstance(sidebar);
                    bsOffcanvas.toggle();
                }
            } else {
                // Desktop mini-sidebar toggle
                document.body.classList.toggle('sidebar-collapsed');
                if (sidebar) sidebar.classList.toggle('sidebar-collapsed');
                if (mainContent) mainContent.classList.toggle('content-expanded');

                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                updateTooltipsState(isCollapsed);
            }
        });
    }

    // Auto-close offcanvas on mobile link clicks
    if (sidebar) {
        sidebar.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    const bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebar);
                    if (bsOffcanvas) {
                        bsOffcanvas.hide();
                    }
                }
            });
        });
    }

    window.addEventListener('resize', () => {
        const isCollapsed = document.body.classList.contains('sidebar-collapsed');
        updateTooltipsState(isCollapsed);
    });

    // Theme Toggle Logic
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;
    const themeKnob = document.getElementById('theme-knob');
    const themeKnobIcon = document.getElementById('theme-knob-icon');

    // Check localStorage for saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-bs-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', (e) => {
            e.preventDefault();
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);

            // Optional: Re-render charts for dark mode colors
            updateChartsTheme(newTheme);
        });
    }

    function updateThemeIcon(theme) {
        if (!themeKnob) return;
        if (theme === 'dark') {
            themeKnob.style.transform = 'translateX(20px)';
            if (themeKnobIcon) {
                themeKnobIcon.classList.remove('fa-sun');
                themeKnobIcon.classList.add('fa-moon');
            }
        } else {
            themeKnob.style.transform = 'translateX(0)';
            if (themeKnobIcon) {
                themeKnobIcon.classList.remove('fa-moon');
                themeKnobIcon.classList.add('fa-sun');
            }
        }
    }

    let revenueChart, leadsChart;

    // Chart.js Default Font
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#9ca3af';

    // Revenue Area Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const canvas2d = revenueCtx.getContext('2d');
        const gradientFill = canvas2d.createLinearGradient(0, 0, 0, 200);
        gradientFill.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
        gradientFill.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

        revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: [15, 22, 18, 28, 35, 32, 40, 38, 45, 58, 52, 65],
                    borderColor: '#6366F1',
                    backgroundColor: gradientFill,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366F1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return '$' + value + 'K';
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false,
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                }
            }
        });
    }

    // Leads by Source Donut Chart
    const leadsCtx = document.getElementById('leadsChart');
    if (leadsCtx) {
        leadsChart = new Chart(leadsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Website', 'Referral', 'Social Media', 'Email Campaign', 'Other'],
                datasets: [{
                    data: [35, 25, 20, 10, 10],
                    backgroundColor: [
                        '#6366F1', // Purple
                        '#3b82f6', // Blue
                        '#06b6d4', // Cyan
                        '#f59e0b', // Yellow/Orange
                        '#9ca3af'  // Gray
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false // Using custom legend in HTML
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // Handle Chart Dark mode
    function updateChartsTheme(theme) {
        const gridColor = theme === 'dark' ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
        const textColor = theme === 'dark' ? '#94a3b8' : '#64748b';

        if (revenueChart) {
            revenueChart.options.scales.y.grid.color = gridColor;
            revenueChart.options.scales.y.ticks.color = textColor;
            revenueChart.options.scales.x.ticks.color = textColor;
            revenueChart.update();
        }
    }

    // Initial call just in case it starts in dark mode
    if (htmlElement.getAttribute('data-bs-theme') === 'dark') {
        updateChartsTheme('dark');
    }
});
