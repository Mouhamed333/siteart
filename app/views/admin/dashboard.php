<div class="stats-grid">
    <div class="stat-card"><i class="fas fa-shopping-bag"></i><div><span class="stat-value"><?= $stats['orders']['total'] ?? 0 ?></span><span class="stat-label">Commandes</span></div></div>
    <div class="stat-card"><i class="fas fa-money-bill"></i><div><span class="stat-value"><?= formatPrice((float)($stats['orders']['revenue'] ?? 0)) ?></span><span class="stat-label">Revenus</span></div></div>
    <div class="stat-card"><i class="fas fa-users"></i><div><span class="stat-value"><?= $stats['clients'] ?? 0 ?></span><span class="stat-label">Clients</span></div></div>
    <div class="stat-card"><i class="fas fa-box"></i><div><span class="stat-value"><?= $stats['products'] ?? 0 ?></span><span class="stat-label">Produits</span></div></div>
</div>

<div class="admin-card">
    <h2>Revenus (30 derniers jours)</h2>
    <canvas id="revenueChart" height="100"></canvas>
</div>

<script>
const daily = <?= json_encode($stats['daily'] ?? []) ?>;
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: daily.map(d => d.date),
        datasets: [{ label: 'Revenus (FCFA)', data: daily.map(d => d.revenue), borderColor: '#C9A227', tension: 0.3, fill: true, backgroundColor: 'rgba(201,162,39,0.1)' }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
