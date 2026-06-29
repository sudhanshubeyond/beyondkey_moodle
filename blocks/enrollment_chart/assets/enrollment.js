(function () {
    let chartInstance = null;

    function niceStep(maxVal) {
        if (!isFinite(maxVal) || maxVal <= 0) return 10;
        if (maxVal <= 10) return 1;
        const targetTicks = 5;
        const raw = maxVal / targetTicks;
        const exponent = Math.floor(Math.log10(raw));
        const fraction = raw / Math.pow(10, exponent);
        let niceFraction;
        if (fraction <= 1) niceFraction = 1;
        else if (fraction <= 2) niceFraction = 2;
        else if (fraction <= 5) niceFraction = 5;
        else niceFraction = 10;
        let step = niceFraction * Math.pow(10, exponent);
        if (maxVal > 10) step = Math.max(10, Math.round(step / 10) * 10);
        return step;
    }

    function computeScale(points) {
        const maxVal = Math.max(...points);
        if (!isFinite(maxVal) || maxVal <= 0) {
            return { min: 0, max: 10, step: 1 };
        }
        if (maxVal <= 10) {
            return { min: 1, max: 10, step: 1 };
        }
        const step = niceStep(maxVal);
        const max = Math.ceil(maxVal / step) * step;
        return { min: 0, max, step };
    }

    function render(canvas, labels, data) {
        if (chartInstance) chartInstance.destroy();
        const graphColor = window.MoodleSettings.graphColor || '#008196'; 
        console.log("Highlight color:", graphColor);

        const points = (Array.isArray(data) ? data : []).map(v => Number(v) || 0);
        while (points.length < 12) points.push(0);

        const maxVal = Math.max(...points);
        const safeMax = (isFinite(maxVal) && maxVal > 0) ? maxVal : 0;

        let step;
        if (safeMax <= 10) {
            step = 1;
        } else if (safeMax <= 30) {
            step = 5;
        } else {
            step = 10;
        }

        const yMin = 0;
        const yMax = Math.max(10, Math.ceil(safeMax / step) * step);

        const valueLabels = {
            id: 'valueLabels',
            afterDatasetsDraw(chart, args, pluginOptions) {
                const {ctx} = chart;
                ctx.save();
                ctx.font = '600 11px system-ui, -apple-system, Segoe UI, Roboto, Arial';
                ctx.fillStyle = 'rgba(33,37,41,0.9)';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';
                const meta = chart.getDatasetMeta(0);
                meta.data.forEach((pt, i) => {
                    const val = points[i];
                    if (val === 0 && !pluginOptions.showZero) return;
                    const {x, y} = pt.getProps(['x','y'], true);
                    ctx.fillText(String(val), x, y - 6);
                });
                ctx.restore();
            }
        };

        const lineColor = graphColor;
        const pointFill = graphColor;
        const pointBorder = '#ffffff';

        chartInstance = new window.Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Enrollments',
                    data: points,
                    borderColor: lineColor,
                    backgroundColor: lineColor,
                    fill: false,
                    tension: 0.25,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    pointBackgroundColor: pointFill,
                    pointBorderColor: pointBorder,
                    pointBorderWidth: 2,
                    spanGaps: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 600, easing: 'easeOutQuart' },
                scales: {
                    x: {
                        title: { display: true, text: 'Months' },
                        grid: { color: 'rgba(0,0,0,.08)' },
                    },
                    y: {
                        title: { display: true, text: 'Students' },
                        min: yMin,
                        max: yMax,
                        ticks: {
                            stepSize: step,
                            precision: 0,
                            autoSkip: false,
                            maxTicksLimit: Math.floor((yMax - yMin) / step) + 1,
                            callback: (v) => v
                        },
                        grid: { color: 'rgba(0,0,0,.08)' },
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(33,37,41,.95)',
                        padding: 10,
                        callbacks: {
                            label: (ctx) => ` ${ctx.parsed.y} student${ctx.parsed.y === 1 ? '' : 's'}`
                        }
                    }
                }
            },
            plugins: [valueLabels]
        });
    }

    function fetchData(ajaxUrl, courseId, year, sesskey) {
        const u = new URL(ajaxUrl, window.location.origin);
        u.searchParams.set('courseid', courseId || 0);
        u.searchParams.set('year', year);
        u.searchParams.set('sesskey', sesskey);
        return fetch(u.toString(), { credentials: 'same-origin' })
            .then(r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            });
    }

    window.EnrollmentChartInit = function (opts) {
        const canvas    = document.getElementById(opts.canvasId);
        const yearSel   = document.getElementById(opts.yearSelectId);
        const courseSel = document.getElementById(opts.courseSelectId);
        if (!canvas || !yearSel || !courseSel) return;

        function refresh() {
            const y = parseInt(yearSel.value, 10);
            const c = parseInt(courseSel.value || 0, 10);
            fetchData(opts.ajaxUrl, c, y, opts.sesskey)
                .then(payload => render(canvas, payload.labels, payload.counts))
                .catch(err => console.warn('EnrollmentChart fetch/render error:', err));
        }

        refresh();
        yearSel.addEventListener('change', refresh);
        courseSel.addEventListener('change', refresh);
    };
})();
