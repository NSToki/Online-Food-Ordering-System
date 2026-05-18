document.addEventListener('DOMContentLoaded', () => {

    // ─── REAL-TIME CLOCK ────────────────────────────────────
    const timeEl = document.getElementById('realtimeTime');
    const dateEl = document.getElementById('realtimeDate');

    const days   = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    function updateClock() {
        const now  = new Date();
        const h    = String(now.getHours()).padStart(2, '0');
        const m    = String(now.getMinutes()).padStart(2, '0');
        const s    = String(now.getSeconds()).padStart(2, '0');
        const day  = days[now.getDay()];
        const date = now.getDate();
        const mon  = months[now.getMonth()];
        const year = now.getFullYear();

        if (timeEl) timeEl.innerHTML = `${h}:${m}:<span class="seconds">${s}</span>`;
        if (dateEl) dateEl.textContent = `${day}, ${date} ${mon} ${year}`;
    }

    updateClock();
    setInterval(updateClock, 1000);

    // ─── DASHBOARD STATS AUTO-REFRESH (AJAX) ────────────────
    const statMap = {
        activeRestaurants: document.getElementById('stat-activeRestaurants'),
        ordersToday:       document.getElementById('stat-ordersToday'),
        totalUsers:        document.getElementById('stat-totalUsers'),
        activeAgents:      document.getElementById('stat-activeAgents'),
        totalRevenue:      document.getElementById('stat-totalRevenue'),
    };

    // Only run on dashboard page (checks if any stat element exists)
    const isDashboard = Object.values(statMap).some(el => el !== null);

    function animatePulse(el) {
        el.style.transition = 'transform 0.2s, opacity 0.2s';
        el.style.transform  = 'scale(1.15)';
        el.style.opacity    = '0.6';
        setTimeout(() => {
            el.style.transform = 'scale(1)';
            el.style.opacity   = '1';
        }, 220);
    }

    function refreshDashboardStats() {
        fetch('/Food-Delevary-Site/views/api/admin/dashboard_stats.php', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            const updates = {
                activeRestaurants: data.activeRestaurants,
                ordersToday:       data.ordersToday,
                totalUsers:        data.totalUsers,
                activeAgents:      data.activeAgents,
                totalRevenue:      '$' + data.totalRevenue,
            };
            Object.entries(updates).forEach(([key, val]) => {
                const el = statMap[key];
                if (!el) return;
                if (el.textContent.trim() !== String(val)) {
                    el.textContent = val;
                    animatePulse(el);
                }
            });
        })
        .catch(() => {}); // Silently ignore on non-dashboard pages
    }

    if (isDashboard) {
        // Refresh every 30 seconds
        setInterval(refreshDashboardStats, 30000);
    }

    // ─── TOAST SYSTEM ───────────────────────────────────────
    const toastContainer = document.getElementById('toast-container');

    const toastIcons = {
        success: 'fa-circle-check',
        error:   'fa-circle-xmark',
        warning: 'fa-triangle-exclamation',
    };

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <i class="fa-solid ${toastIcons[type] || toastIcons.success} toast-icon"></i>
            <span>${message}</span>
        `;
        toastContainer.appendChild(toast);

        // Auto-remove after 3.5s with exit animation
        setTimeout(() => {
            toast.classList.add('toast-exit');
            toast.addEventListener('animationend', () => toast.remove(), { once: true });
        }, 3500);
    }

    // ─── SIDEBAR TOGGLE (mobile) ────────────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', (e) => {
            if (
                sidebar.classList.contains('open') &&
                !sidebar.contains(e.target) &&
                e.target !== sidebarToggle
            ) {
                sidebar.classList.remove('open');
            }
        });
    }

    // ─── AJAX ACTION BUTTONS ────────────────────────────────
    const buttons = document.querySelectorAll('.ajax-action-btn');

    buttons.forEach(btn => {

        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const action     = this.dataset.action;
            const id         = this.dataset.id;
            const field      = this.dataset.idField;
            const confirmMsg = this.dataset.confirm;

            // Confirm dialog
            if (confirmMsg && !confirm(confirmMsg)) return;

            // Disable & show loader
            const originalHTML = this.innerHTML;
            this.disabled  = true;
            this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Working...';

            // Build request body
            const data = new URLSearchParams();
            data.append('form_action', action);
            data.append(field, id);

            fetch(window.location.href, {
                method:  'POST',
                headers: {
                    'Content-Type':     'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body:    data.toString()
            })
            .then(res => {
                // Guard against non-JSON responses
                const contentType = res.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server returned a non-JSON response.');
                }
                return res.json();
            })
            .then(result => {
                if (result.status === 'success') {
                    showToast(result.message, 'success');

                    // Remove the table row for approve/reject/resolve actions
                    const removalActions = ['approve', 'reject', 'resolve'];
                    if (removalActions.includes(action)) {
                        const row = this.closest('tr');
                        if (row) {
                            row.style.transition = 'opacity 0.3s, transform 0.3s';
                            row.style.opacity    = '0';
                            row.style.transform  = 'translateX(20px)';
                            setTimeout(() => row.remove(), 300);
                        }
                    } else {
                        // Reload to reflect status change
                        setTimeout(() => location.reload(), 800);
                    }
                } else {
                    showToast(result.message || 'Something went wrong.', 'error');
                    this.disabled  = false;
                    this.innerHTML = originalHTML;
                }
            })
            .catch(err => {
                console.error('[AJAX Error]', err);
                showToast('Network error — please try again.', 'error');
                this.disabled  = false;
                this.innerHTML = originalHTML;
            });

        });

    });

});