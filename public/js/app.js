$(document).ready(function () {

    const user = Users.current();
    $('#adminName').text(user.name.split(' ')[0]);
    $('#welcomeName').text(user.name.split(' ')[0]);

    /* ── Greeting ── */
    const hour = new Date().getHours();
    $('#greeting').text(hour < 12 ? 'Morning' : hour < 17 ? 'Afternoon' : 'Evening');
    $('#topbarDate').text(new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' }));

    /* ── Sidebar Toggle ── */
    $('#sidebarToggle').on('click', function () {
        $('#adminSidebar').toggleClass('open');
        $('#sidebarOverlay').toggleClass('show');
    });
    $('#sidebarOverlay').on('click', function () {
        $('#adminSidebar').removeClass('open');
        $(this).removeClass('show');
    });

    /* ── Logout ── */
    $('#logoutLink, #logoutLink2').on('click', function (e) {
        e.preventDefault();
        Users.logout();
        window.location.href = '../login.html';
    });

    /* ── Load Stats ── */
    function loadStats() {
        const stats = Orders.stats();
        const products = Products.all();
        const customers = Users.all().filter(u => u.role === 'customer');

        $('#statRevenue').text('$' + stats.revenue.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        $('#statOrders').text(stats.total);
        $('#statCustomers').text(customers.length);
        $('#statProducts').text(products.length);
        $('#pendingBadge').text(stats.pending || '');
    }

    /* ── Revenue Chart ── */
    let revenueChart;
    function buildRevenueChart(days = 30) {
        const labels = [], data = [], orders = Orders.all();
        const now = new Date();
        for (let i = days - 1; i >= 0; i--) {
            const d = new Date(now); d.setDate(d.getDate() - i);
            const label = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            const dayStr = d.toISOString().split('T')[0];
            const rev = orders
                .filter(o => o.status !== 'cancelled' && o.createdAt && o.createdAt.startsWith(dayStr))
                .reduce((s, o) => s + (o.total || 0), 0);
            labels.push(label); data.push(rev);
        }
        const ctx = document.getElementById('revenueChart').getContext('2d');
        if (revenueChart) revenueChart.destroy();
        revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data,
                    borderColor: 'rgba(108,99,255,1)',
                    backgroundColor: 'rgba(108,99,255,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: 'rgba(108,99,255,1)',
                    pointRadius: 3,
                    tension: 0.4,
                    fill: true,
                }],
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { maxTicksLimit: 8, font: { size: 10 } } },
                    y: { grid: { color: '#f0f0f0' }, ticks: { font: { size: 10 }, callback: v => '$' + v } },
                },
            },
        });
    }

    $('#revenueRange').on('change', function () { buildRevenueChart(parseInt($(this).val())); });

    /* ── Order Status Donut ── */
    function buildDonut() {
        const s = Orders.stats();
        const data = [s.pending, s.shipped, s.delivered, s.cancelled, s.total - s.pending - s.shipped - s.delivered - s.cancelled];
        const labels = ['Pending', 'Shipped', 'Delivered', 'Cancelled', 'Processing'];
        const colors = ['#f59e0b', '#6c63ff', '#10b981', '#ef4444', '#3b82f6'];
        const ctx = document.getElementById('orderDonut').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }] },
            options: {
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } },
                },
            },
        });
        let legend = '';
        labels.forEach((l, i) => {
            if (data[i] === 0) return;
            legend += `<div class="d-flex align-items-center justify-content-between mb-1">
        <div class="d-flex align-items-center gap-2">
          <div style="width:10px;height:10px;border-radius:50%;background:${colors[i]}"></div>
          <span style="font-size:.8rem">${l}</span>
        </div>
        <span class="fw-700 small">${data[i]}</span>
      </div>`;
        });
        $('#donutLegend').html(legend);
    }

    /* ── Recent Orders Table ── */
    function loadRecentOrders() {
        const orders = Orders.all().slice(0, 6);
        if (!orders.length) { $('#recentOrdersBody').html('<tr><td colspan="5" class="text-center text-muted py-3">No orders yet.</td></tr>'); return; }
        let rows = '';
        orders.forEach(o => {
            rows += `<tr>
        <td class="fw-700 text-primary"><a href="orders.html" style="color:inherit">${o.id}</a></td>
        <td>${o.name || o.email}</td>
        <td class="fw-700">${formatCurrency(o.total || 0)}</td>
        <td>${statusBadge(o.status)}</td>
        <td class="text-muted small">${formatDate(o.createdAt)}</td>
      </tr>`;
        });
        $('#recentOrdersBody').html(rows);
    }

    /* ── Top Products ── */
    function loadTopProducts() {
        const products = Products.all().slice(0, 5);
        let html = '';
        products.forEach((p, i) => {
            const pct = Math.max(20, 100 - i * 18);
            html += `
      <div class="d-flex align-items-center gap-2 mb-2">
        <img src="${p.image || 'https://placehold.co/36x36'}" style="width:36px;height:36px;object-fit:cover;border-radius:8px">
        <div class="flex-fill" style="min-width:0">
          <div class="small fw-700 text-truncate">${p.name}</div>
          <div class="progress mt-1" style="height:4px">
            <div class="progress-bar" style="width:${pct}%;background:var(--primary)"></div>
          </div>
        </div>
        <div class="small fw-700 text-primary">${formatCurrency(p.salePrice || p.price)}</div>
      </div>`;
        });
        $('#topProductsList').html(html || '<p class="text-muted small">No products.</p>');
    }

    /* ── Activity Feed ── */
    function loadActivity() {
        const orders = Orders.all().slice(0, 4);
        const customers = Users.all().filter(u => u.role === 'customer').slice(0, 2);
        let items = [];
        orders.forEach(o => items.push({
            icon: 'ri-shopping-bag-line', iconBg: '#ede9ff', iconColor: 'var(--primary)',
            text: `New order <strong>${o.id}</strong> by ${o.name}`,
            time: o.createdAt,
        }));
        customers.forEach(c => items.push({
            icon: 'ri-user-add-line', iconBg: '#d1fae5', iconColor: '#059669',
            text: `New customer: <strong>${c.name}</strong>`,
            time: c.createdAt,
        }));
        items.sort((a, b) => new Date(b.time) - new Date(a.time));
        items = items.slice(0, 5);

        let html = '';
        items.forEach(item => {
            html += `
      <div class="activity-item">
        <div class="activity-icon" style="background:${item.iconBg}">
          <i class="${item.icon}" style="color:${item.iconColor}"></i>
        </div>
        <div>
          <div style="font-size:.84rem">${item.text}</div>
          <div class="text-muted" style="font-size:.75rem">${formatDateTime(item.time)}</div>
        </div>
      </div>`;
        });
        $('#activityFeed').html(html || '<p class="text-muted small">No recent activity.</p>');
    }

    /* ── Low Stock Alert ── */
    function checkLowStock() {
        const low = Products.all().filter(p => p.stock <= 5 && p.stock > 0);
        if (low.length) {
            const alert = $(`
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4 py-2" role="alert">
          <i class="ri-error-warning-line fs-5"></i>
          <div><strong>${low.length} product(s)</strong> are low on stock.
            <a href="products.html" class="alert-link ms-1">Review inventory →</a>
          </div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>`);
            $('.admin-content').prepend(alert);
        }
    }

    /* ── Init ── */
    loadStats();
    buildRevenueChart(30);
    buildDonut();
    loadRecentOrders();
    loadTopProducts();
    loadActivity();
    checkLowStock();
});