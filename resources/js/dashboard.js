import Chart from 'chart.js/auto';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', () => {

    let revenueChart, leadsChart;

    // Handle Chart Dark mode
    function updateChartsTheme(theme) {
        const gridColor = theme === 'dark' ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
        const textColor = theme === 'dark' ? '#94a3b8' : '#64748b';

        if (revenueChart && revenueChart.options && revenueChart.options.scales) {
            revenueChart.options.scales.y.grid.color = gridColor;
            revenueChart.options.scales.y.ticks.color = textColor;
            revenueChart.options.scales.x.ticks.color = textColor;
            revenueChart.update();
        }
    }

    // Sidebar Toggle Logic
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarToggle = document.getElementById('sidebarToggle') || document.getElementById('sidebar-toggle');

    let tooltipInstances = [];

    function initTooltips() {
        document.querySelectorAll('.tooltip.sidebar-tooltip').forEach(el => el.remove());
        const tooltipTriggerList = document.querySelectorAll('#sidebar [data-bs-toggle="tooltip"]');
        tooltipInstances = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el, {
            trigger: 'hover',
            placement: 'right',
            container: 'body',
            customClass: 'sidebar-tooltip',
            boundary: 'viewport'
        }));
    }

    function updateTooltipsState(isCollapsed) {
        const isDesktop = window.innerWidth >= 992;
        tooltipInstances.forEach(instance => {
            if (isCollapsed && isDesktop) {
                instance.enable();
            } else {
                instance.hide();
                instance.disable();
            }
        });
        if (!isCollapsed || !isDesktop) {
            document.querySelectorAll('.tooltip.sidebar-tooltip').forEach(el => el.remove());
        }
    }

    function toggleSidebarState(forceState = null) {
        const isCurrentCollapsed = document.documentElement.classList.contains('sidebar-collapsed') || document.body.classList.contains('sidebar-collapsed');
        const shouldBeCollapsed = forceState !== null ? forceState : !isCurrentCollapsed;

        if (shouldBeCollapsed) {
            document.documentElement.classList.add('sidebar-collapsed');
            document.body.classList.add('sidebar-collapsed');
            if (sidebar) sidebar.classList.add('sidebar-collapsed');
            if (mainContent) mainContent.classList.add('content-expanded');
        } else {
            document.documentElement.classList.remove('sidebar-collapsed');
            document.body.classList.remove('sidebar-collapsed');
            if (sidebar) sidebar.classList.remove('sidebar-collapsed');
            if (mainContent) mainContent.classList.remove('content-expanded');
        }

        localStorage.setItem('sidebarCollapsed', shouldBeCollapsed ? 'true' : 'false');
        updateTooltipsState(shouldBeCollapsed);
    }

    // Initialize tooltips first so instances array is populated
    initTooltips();

    // Check stored state and apply on desktop
    const isStoredCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (window.innerWidth >= 992) {
        toggleSidebarState(isStoredCollapsed);
    } else {
        document.documentElement.classList.remove('sidebar-collapsed');
        document.body.classList.remove('sidebar-collapsed');
        updateTooltipsState(false);
    }

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
                toggleSidebarState();
            }
        });
    }

    // Auto-close offcanvas & hide tooltips on link clicks
    if (sidebar) {
        sidebar.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                tooltipInstances.forEach(instance => {
                    instance.hide();
                    instance.disable();
                });
                document.querySelectorAll('.tooltip.sidebar-tooltip').forEach(el => el.remove());

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

    // Theme Management Logic (Single Button Cycle: Light -> Dark -> Auto)
    const htmlElement = document.documentElement;
    const themeBtn = document.getElementById('theme-toggle-btn');

    const getStoredTheme = () => localStorage.getItem('theme') || 'auto';
    const setStoredTheme = theme => localStorage.setItem('theme', theme);

    const getAppliedTheme = (theme) => {
        if (theme === 'auto') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        return theme;
    };

    const setTheme = theme => {
        const appliedTheme = getAppliedTheme(theme);
        htmlElement.setAttribute('data-bs-theme', appliedTheme);
        updateChartsTheme(appliedTheme);
    };

    const updateThemeUI = (theme) => {
        const themeIcon = document.querySelector('.theme-toggle-icon') || (themeBtn ? themeBtn.querySelector('i') : null);

        let iconClass = 'fa-circle-half-stroke';
        let iconColor = 'text-secondary';
        let nextThemeName = 'Light';

        if (theme === 'light') {
            iconClass = 'fa-sun';
            iconColor = 'text-secondary';
            nextThemeName = 'Dark';
        } else if (theme === 'dark') {
            iconClass = 'fa-moon';
            iconColor = 'text-secondary';
            nextThemeName = 'Auto';
        } else if (theme === 'auto') {
            iconClass = 'fa-circle-half-stroke';
            iconColor = 'text-secondary';
            nextThemeName = 'Light';
        }

        if (themeIcon) {
            themeIcon.className = `theme-toggle-icon fa-solid ${iconClass} ${iconColor} fs-6`;
        }

        if (themeBtn) {
            const systemState = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const currentLabel = theme === 'auto' ? `Auto (${systemState})` : theme.charAt(0).toUpperCase() + theme.slice(1);
            const tooltipText = `Theme: ${currentLabel} (Click for ${nextThemeName})`;
            themeBtn.setAttribute('title', tooltipText);
            themeBtn.setAttribute('aria-label', tooltipText);
        }
    };

    // Initialize Theme State
    const initialTheme = getStoredTheme();
    setTheme(initialTheme);
    updateThemeUI(initialTheme);

    // Listen for system color scheme changes (when on 'auto')
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const storedTheme = getStoredTheme();
        if (storedTheme === 'auto') {
            setTheme('auto');
            updateThemeUI('auto');
        }
    });

    // Single Button Click to Cycle: light -> dark -> auto -> light
    if (themeBtn) {
        themeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const currentTheme = getStoredTheme();
            let nextTheme = 'light';
            if (currentTheme === 'light') {
                nextTheme = 'dark';
            } else if (currentTheme === 'dark') {
                nextTheme = 'auto';
            } else if (currentTheme === 'auto') {
                nextTheme = 'light';
            }

            setStoredTheme(nextTheme);
            setTheme(nextTheme);
            updateThemeUI(nextTheme);
        });
    }

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

        const revData = (window.dashboardChartData && window.dashboardChartData.monthlyRevenue)
            ? window.dashboardChartData.monthlyRevenue
            : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: revData,
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
                                return '$' + value;
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
        const leadsItems = (window.dashboardChartData && window.dashboardChartData.leadsBySource)
            ? window.dashboardChartData.leadsBySource
            : [];

        const leadsLabels = leadsItems.length ? leadsItems.map(item => item.source) : ['Website', 'Referral', 'Social Media', 'Email Campaign', 'Other'];
        const leadsData = leadsItems.length ? leadsItems.map(item => item.count) : [0, 0, 0, 0, 0];
        const leadsColors = leadsItems.length ? leadsItems.map(item => item.hex) : ['#6366F1', '#3b82f6', '#06b6d4', '#f59e0b', '#9ca3af'];

        leadsChart = new Chart(leadsCtx, {
            type: 'doughnut',
            data: {
                labels: leadsLabels,
                datasets: [{
                    data: leadsData,
                    backgroundColor: leadsColors,
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
                                return context.label + ': ' + context.parsed + ' leads';
                            }
                        }
                    }
                }
            }
        });
    }
});
