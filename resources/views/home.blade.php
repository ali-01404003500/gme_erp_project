@extends('layout.app')
@section('title', 'Home')
@section('description', 'Dashboard')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            /* Aesthetic Palette */
            --glass-bg: rgba(255, 255, 255, 0.92);
            --primary-grad: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            --success-grad: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --info-grad: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            --danger-grad: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            --panel-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        .social-dash-wrap {
            padding: 30px 0;
            /* Aesthetic Mesh Gradient Background */
            background-color: #3ebbc6;
            background-image: 
                radial-gradient(at 0% 0%, hsla(186,66%,45%,1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(199,76%,59%,1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(180,60%,40%,1) 0, transparent 50%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        /* Specific Header Spacing (Left and Right) */
        .header-container {
            padding-left: 45px;
            padding-right: 45px;
        }

        /* Modern Card Design */
        .overview-card-modern {
            background: var(--glass-bg);
            backdrop-filter: blur(10px); /* Frosted glass effect */
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--panel-shadow);
            height: 100%;
        }

        .overview-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 30px -10px rgba(0, 0, 0, 0.15);
        }

        .card-label {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .card-value {
            font-size: 30px;
            font-weight: 800;
            color: #0f172a;
            margin: 10px 0;
        }

        .icon-box {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .bg-primary-grad { background: var(--primary-grad); }
        .bg-secondary-grad { background: var(--danger-grad); }
        .bg-success-grad { background: var(--success-grad); }
        .bg-info-grad { background: var(--info-grad); }

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(8px);
            border-radius: 24px;
            padding: 28px;
            box-shadow: var(--panel-shadow);
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .activity-item {
            padding: 14px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cal-header {
            background: var(--primary-grad);
            color: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 20px -5px rgba(67, 56, 202, 0.4);
        }

        .cal-day { font-size: 48px; font-weight: 900; }
        .stat-badge { font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 4px; }
        
        .text-success { color: #10b981 !important; }
        .text-danger { color: #f43f5e !important; }
    </style>

    <div class="container-fluid">
        <div class="social-dash-wrap">

            <div class="row mb-4 header-container">
                <div class="col-12">
                    <div class="breadcrumb-main d-flex justify-content-between align-items-center bg-white p-4 radius-xl shadow-sm border-0" style="border-radius: 20px;">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">Dashboard Overview</h4>
                            <p class="text-muted small mb-0">Managing Director: <strong>Engr. Tarikul Islam</strong></p>
                        </div>
                        <div class="d-flex gap-3">
                            <span class="badge bg-soft-info p-2 px-3 radius-pill text-info d-flex align-items-center" style="background: #e0f2fe;">
                                <i class="uil uil-history me-1"></i> Real-time Analytics
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row px-4">
                <div class="col-xl-3 col-sm-6 mb-25">
                    <div class="overview-card-modern">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-label">Inventory</p>
                                <h1 class="card-value" id="total-products">0</h1>
                                <span class="stat-badge" id="sales-block"><i id="icon"></i> <strong id="current-month-products">0</strong></span>
                            </div>
                            <div class="icon-box bg-primary-grad"><i class="uil uil-layers"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-25">
                    <div class="overview-card-modern">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-label">Orders</p>
                                <h1 class="card-value" id="total-orders">0</h1>
                                <span class="stat-badge" id="Ordergrowth-block"><i id="icon"></i> <strong id="current-month-total-orders">0</strong></span>
                            </div>
                            <div class="icon-box bg-secondary-grad"><i class="uil uil-shopping-cart-alt"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-25">
                    <div class="overview-card-modern">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-label">Net Revenue</p>
                                <h1 class="card-value" id="total-sales">0</h1>
                                <span class="stat-badge" id="salesTotal-block"><i id="icon"></i> <strong id="current-month-total-sales">0</strong></span>
                            </div>
                            <div class="icon-box bg-success-grad"><i class="uil uil-money-bill"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-25">
                    <div class="overview-card-modern">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-label">Active Clients</p>
                                <h1 class="card-value" id="total-customers">0</h1>
                                <span class="stat-badge" id="customerTotal-block"><i id="icon"></i> <strong id="current-month-customer">0</strong></span>
                            </div>
                            <div class="icon-box bg-info-grad"><i class="uil uil-users-alt"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 mb-25">
                    <div class="glass-panel h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold m-0">Revenue Trajectory</h5>
                            <div class="btn-group shadow-sm radius-md">
                                <button class="btn btn-sm btn-white active border">Weekly</button>
                                <button class="btn btn-sm btn-white border">Monthly</button>
                            </div>
                        </div>
                        <div style="height: 320px;">
                            <canvas id="revenueFlowChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-25">
                    <div class="glass-panel h-100 text-center">
                        <h5 class="fw-bold mb-4">Operations Calendar</h5>
                        <div class="cal-header mb-4">
                            <div class="cal-month" id="calMonth">...</div>
                            <div class="cal-day" id="calDay">..</div>
                            <div class="fw-bold opacity-75" id="calYear">....</div>
                        </div>
                        <div class="text-start mt-4">
                            <p class="fw-bold small text-muted text-uppercase mb-3">Today's Priority</p>
                            <div class="activity-item">
                                <div style="width: 4px; height: 35px; background: #6366f1; border-radius: 10px;"></div>
                                <div>
                                    <div class="small fw-bold">Equipment Inspection</div>
                                    <div class="extra-small text-muted">09:00 AM - Central Warehouse</div>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div style="width: 4px; height: 35px; background: #10b981; border-radius: 10px;"></div>
                                <div>
                                    <div class="small fw-bold">Client Support Meeting</div>
                                    <div class="extra-small text-muted">02:30 PM - Virtual</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-wrapper p-4 text-center mt-auto" style="background: rgba(255,255,255,0.8); backdrop-filter: blur(5px);">
        <p class="mb-0 text-muted small">© 2026 <strong>Global Medical Engineering Bid Limited</strong>. Dashboard v3.0</p>
    </footer>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(async function () {
            // 1. Live Calendar
            const now = new Date();
            const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $('#calMonth').text(months[now.getMonth()]);
            $('#calDay').text(now.getDate());
            $('#calYear').text(now.getFullYear());

            // 2. Premium Revenue Chart
            const flowCtx = document.getElementById('revenueFlowChart').getContext('2d');
            new Chart(flowCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        data: [42000, 38000, 55000, 48000, 70000, 65000, 82000],
                        borderColor: '#6366f1',
                        borderWidth: 4,
                        fill: true,
                        backgroundColor: 'rgba(99, 102, 241, 0.05)',
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#6366f1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: 'rgba(0,0,0,0.03)' }, ticks: { color: '#94a3b8' } },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                    }
                }
            });

            // 3. API Data Fetching
            const [countProduct, countSalesOrder, countTotalSales, countCustomer] = await Promise.all([
                $.get("{{ route('inv.products.count') }}"),
                $.get("{{ route('sales.sales-orders.count') }}"),
                $.get("{{ route('sales.total-sales.count') }}"),
                $.get("{{ route('crm.customer.count') }}"),
            ]);

            const animateCount = (id, target) => {
                $({ Counter: 0 }).animate({ Counter: target }, {
                    duration: 1500,
                    easing: 'swing',
                    step: function () { $(id).text(Math.ceil(this.Counter).toLocaleString()); }
                });
            };

            animateCount("#total-products", countProduct.count);
            animateCount("#total-orders", countSalesOrder.count);
            animateCount("#total-sales", countTotalSales.count);
            animateCount("#total-customers", countCustomer.count);

            const updateGrowthUI = (blockSelector, textSelector, current, previous) => {
                let growth = previous == 0 ? (current > 0 ? 100 : 0) : (((current - previous) / previous) * 100);
                const isNeg = growth < 0;
                $(textSelector).text(Math.abs(Math.ceil(growth)) + '%');
                $(blockSelector).addClass(isNeg ? 'text-danger' : 'text-success');
                $(blockSelector + ' #icon').addClass(isNeg ? 'las la-arrow-down' : 'las la-arrow-up');
            };

            updateGrowthUI('#sales-block', '#current-month-products', countProduct.current_month, countProduct.previous_month);
            updateGrowthUI('#Ordergrowth-block', '#current-month-total-orders', countSalesOrder.current_month, countSalesOrder.previous_month);
            updateGrowthUI('#salesTotal-block', '#current-month-total-sales', countTotalSales.current_month, countTotalSales.previous_month);
            updateGrowthUI('#customerTotal-block', '#current-month-customer', countCustomer.current_month, countCustomer.previous_month);
        });
    </script>
@endsection