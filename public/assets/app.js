document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }

    const climateCanvas = document.getElementById('climateChart');
    if (climateCanvas && window.Chart) {
        const rows = JSON.parse(climateCanvas.dataset.climate || '[]');
        new Chart(climateCanvas, {
            type: 'line',
            data: {
                labels: rows.map(row => row.month),
                datasets: [
                    { label: 'Minimum °C', data: rows.map(row => Number(row.avg_min)), borderColor: '#0f766e', backgroundColor: '#0f766e22', tension: .35 },
                    { label: 'Maximum °C', data: rows.map(row => Number(row.avg_max)), borderColor: '#d97706', backgroundColor: '#d9770622', tension: .35 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    const timeCanvas = document.getElementById('timeChart');
    if (timeCanvas && window.Chart) {
        const rows = JSON.parse(timeCanvas.dataset.series || '[]');
        const order = ['06:00-15:00', '15:00-21:00', '21:00-24:00', '00:00-06:00'];
        const values = order.map(label => {
            const row = rows.find(item => item.bucket === label);
            return row ? Number(row.visits) : 0;
        });
        new Chart(timeCanvas, {
            type: 'bar',
            data: { labels: order, datasets: [{ label: 'Návštevy', data: values, backgroundColor: '#0f766e' }] },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }

    const prefCanvas = document.getElementById('prefChart');
    if (prefCanvas && window.Chart) {
        const rows = JSON.parse(prefCanvas.dataset.series || '[]');
        const labels = { hot: 'horúco', warm: 'teplo', mild: 'príjemne', any: 'jedno mi to' };
        new Chart(prefCanvas, {
            type: 'doughnut',
            data: {
                labels: rows.map(row => labels[row.temperature_pref] || row.temperature_pref),
                datasets: [{ data: rows.map(row => Number(row.cnt)), backgroundColor: ['#0f766e', '#d97706', '#2563eb', '#64748b'] }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    const typeCanvas = document.getElementById('typeChart');
    if (typeCanvas && window.Chart) {
        const rows = JSON.parse(typeCanvas.dataset.series || '{}');
        const labels = { beach: 'more', nature: 'príroda', history: 'história', city: 'mesto', activity: 'aktivity' };
        new Chart(typeCanvas, {
            type: 'bar',
            data: {
                labels: Object.keys(rows).map(key => labels[key] || key),
                datasets: [{ label: 'Počet', data: Object.values(rows).map(Number), backgroundColor: '#d97706' }]
            },
            options: { responsive: true, plugins: { legend: { display: false } } }
        });
    }

    document.querySelectorAll('[data-sortable]').forEach(table => {
        table.querySelectorAll('th').forEach((th, index) => {
            th.addEventListener('click', () => {
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                const asc = th.dataset.asc !== 'true';
                rows.sort((a, b) => {
                    const av = a.children[index].textContent.trim();
                    const bv = b.children[index].textContent.trim();
                    const an = Number(av.replace(',', '.'));
                    const bn = Number(bv.replace(',', '.'));
                    const result = Number.isNaN(an) || Number.isNaN(bn) ? av.localeCompare(bv, 'sk') : an - bn;
                    return asc ? result : -result;
                });
                table.querySelectorAll('th').forEach(header => delete header.dataset.asc);
                th.dataset.asc = String(asc);
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    });
});
