@extends('layout.app')
@section('title', 'Home')
@section('description', 'Dashboard')

@section('content')
    <style>
        :root {
            /* Aesthetic Palette - Clean White Version */
            --primary-color: #6366f1;
            --primary-dark: #4338ca;
            --success-color: #10b981;
            --danger-color: #f43f5e;
            --info-color: #0ea5e9;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .social-dash-wrap {
            padding: 30px 0;
            background-color: #ffffff;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }

        /* Specific Header Spacing (Left and Right) */
        .header-container {
            padding-left: 45px;
            padding-right: 45px;
        }

        /* Modern Card Design - Clean White Version */
        .overview-card-modern {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--card-shadow);
            height: 100%;
        }

        .overview-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .card-label {
            font-size: 11px;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .card-value {
            font-size: 30px;
            font-weight: 800;
            color: var(--text-dark);
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

        .bg-primary-grad {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        }

        .bg-secondary-grad {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
        }

        .bg-success-grad {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-info-grad {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }

        .glass-panel {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            border: 1px solid var(--border-light);
        }

        .activity-item {
            padding: 14px 0;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cal-header {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 20px -5px rgba(67, 56, 202, 0.3);
        }

        .cal-day {
            font-size: 42px;
            font-weight: 900;
        }

        .stat-badge {
            font-weight: 700;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .text-success {
            color: #10b981 !important;
        }

        .text-danger {
            color: #f43f5e !important;
        }

        /* Summary Card Styles */
        .summary-stat-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .summary-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--card-shadow);
        }

        .summary-label {
            font-size: 13px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .summary-trend {
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 30px;
            background: white;
            margin-top: 10px;
        }

        .mini-progress {
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 15px 0 5px;
        }

        .mini-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #6366f1, #4338ca);
            border-radius: 10px;
        }

        .top-performer {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: white;
            border-radius: 12px;
            margin-top: 15px;
            border: 1px solid var(--border-light);
        }

        .performer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .performer-info {
            flex: 1;
        }

        .performer-name {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .performer-stats {
            font-size: 12px;
            color: var(--text-muted);
        }

        .achievement-ring {
            position: relative;
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .ring-value {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
        }
    </style>

    <div class="container-fluid d-none">
        <div class="social-dash-wrap">

            <!-- Header Section -->
            <div class="row mb-4 header-container">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center bg-white p-4 radius-xl shadow-sm border-0"
                        style="border-radius: 20px; border: 1px solid #e2e8f0;">
                        <div>
                            <h4 class="fw-bold mb-1 text-dark">Dashboard Overview</h4>
                            <p class="text-muted small mb-0">Managing Director: <strong>Engr. Tarikul Islam</strong></p>
                        </div>
                        <div class="d-flex gap-3">
                            <span class="badge p-2 px-3 radius-pill d-flex align-items-center"
                                style="background: #e0f2fe; color: #0284c7;">
                                <i class="uil uil-history me-1"></i> Real-time Analytics
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Row -->
            <div class="row px-4">
                <div class="col-xl-3 col-sm-6 mb-25">
                    <div class="overview-card-modern">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="card-label">Inventory</p>
                                <h1 class="card-value" id="total-products">0</h1>
                                <span class="stat-badge" id="sales-block"><i id="icon"></i> <strong
                                        id="current-month-products">0</strong></span>
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
                                <span class="stat-badge" id="Ordergrowth-block"><i id="icon"></i> <strong
                                        id="current-month-total-orders">0</strong></span>
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
                                <span class="stat-badge" id="salesTotal-block"><i id="icon"></i> <strong
                                        id="current-month-total-sales">0</strong></span>
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
                                <span class="stat-badge" id="customerTotal-block"><i id="icon"></i> <strong
                                        id="current-month-customer">0</strong></span>
                            </div>
                            <div class="icon-box bg-info-grad"><i class="uil uil-users-alt"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Row - Target Achievement Summary & Calendar -->
            <div class="row px-4">
                <!-- Target Achievement Summary Section -->
                <div class="col-lg-8 mb-25">
                    <div class="glass-panel h-100">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold m-0">Target Achievement Summary</h5>
                            <div class="btn-group shadow-sm radius-md">
                                <button class="btn btn-sm btn-white active border">This Month</button>
                                <button class="btn btn-sm btn-white border">Quarter</button>
                                <button class="btn btn-sm btn-white border">Year</button>
                            </div>
                        </div>

                        <!-- Summary Stats Row -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="summary-stat-card">
                                    <div class="summary-label">Overall Achievement</div>
                                    <div class="summary-value" id="overall-achievement">78%</div>
                                    <div class="mini-progress">
                                        <div class="mini-progress-bar" id="overall-progress" style="width: 78%"></div>
                                    </div>
                                    <span class="summary-trend text-success">
                                        <i class="uil uil-arrow-up"></i> +12% vs last month
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-stat-card">
                                    <div class="summary-label">Targets Met</div>
                                    <div class="summary-value" id="targets-met-summary">18/24</div>
                                    <div class="mini-progress">
                                        <div class="mini-progress-bar" id="targets-met-progress" style="width: 75%"></div>
                                    </div>
                                    <span class="summary-trend text-muted">
                                        75% success rate
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="summary-stat-card">
                                    <div class="summary-label">Total Deals</div>
                                    <div class="summary-value" id="total-deals-summary">156</div>
                                    <span class="summary-trend text-success">
                                        <i class="uil uil-arrow-up"></i> +23 vs last month
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Summary Info -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3" style="background: #f8fafc; border-radius: 12px;">
                                    <h6 class="fw-bold mb-3">Revenue Overview</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Target</span>
                                        <span class="fw-bold" id="total-target">৳ 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Achieved</span>
                                        <span class="fw-bold text-success" id="total-achieved">৳ 0</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Gap</span>
                                        <span class="fw-bold" id="total-gap">৳ 0</span>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="p-3" style="background: #f8fafc; border-radius: 12px;">
                                    <h6 class="fw-bold mb-3">Top Performer</h6>
                                    <div class="top-performer">
                                        <div class="performer-avatar">JD</div>
                                        <div class="performer-info">
                                            <div class="performer-name">Mr. X</div>
                                            <div class="performer-stats">156% achievement • 12 deals</div>
                                        </div>
                                        <span class="badge bg-success">#1</span>
                                    </div>
                                    <div class="top-performer mt-2">
                                        <div class="performer-avatar"
                                            style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">JS</div>
                                        <div class="performer-info">
                                            <div class="performer-name">Ms.</div>
                                            <div class="performer-stats">142% achievement • 10 deals</div>
                                        </div>
                                        <span class="badge bg-info">#2</span>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <!-- Calendar Section -->
                <div class="col-lg-4">
                    <div class="glass-panel h-100 text-center">
                        <h5 class="fw-bold mb-4">Calendar</h5>
                        <div class="cal-header mb-4">
                            <div class="cal-month" id="calMonth">...</div>
                            <div class="cal-day" id="calDay">..</div>
                            <div class="fw-bold opacity-75" id="calYear">....</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-wrapper p-4 text-center mt-auto" style="background: #ffffff; border-top: 1px solid #e2e8f0;">
        <p class="mb-0 text-muted small">© 2026 <strong>Global Medical Engineering Bid Limited</strong>. Dashboard v3.0</p>
    </footer>
@endsection

@section('page_scripts')
    <script>
        $(document).ready(async function () {
            // Live Calendar
            const now = new Date();
            const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            $('#calMonth').text(months[now.getMonth()]);
            $('#calDay').text(now.getDate());
            $('#calYear').text(now.getFullYear());

            // Fetch KPI Data
            const [countProduct, countSalesOrder, countTotalSales, countCustomer] = await Promise.all([
                $.get("{{ route('inv.products.count') }}"),
                $.get("{{ route('sales.sales-orders.count') }}"),
                $.get("{{ route('sales.total-sales.count') }}"),
                $.get("{{ route('crm.customer.count') }}"),
            ]);

            // Animate KPI Numbers
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

            // Update Growth Indicators
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

            // Set summary data (you can replace these with actual API calls)
            // For demo purposes, setting some sample data
            $('#overall-achievement').text('78%');
            $('#overall-progress').css('width', '78%');
            $('#targets-met-summary').text('18/24');
            $('#targets-met-progress').css('width', '75%');
            $('#total-deals-summary').text('156');
            $('#total-target').text('৳ ' + (12500000).toLocaleString());
            $('#total-achieved').text('৳ ' + (9750000).toLocaleString());
            $('#total-gap').text('৳ ' + (2750000).toLocaleString());

            // You can fetch real data from your target achievement route
            try {
                const employees = @json($employees);
                if (employees.length > 0) {
                    const targetData = await $.get("{{ route('sales_target.perfomence.achievement') }}", {
                        user_ref_id: employees[0].id
                    });

                    // Parse the HTML response to calculate summary data
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(targetData, 'text/html');
                    const rows = doc.querySelectorAll('table tbody tr');

                    let totalTarget = 0;
                    let totalAchieved = 0;
                    let totalDeals = 0;
                    let targetsMet = 0;
                    let totalRows = 0;

                    rows.forEach((row) => {
                        const cols = row.querySelectorAll('td');
                        if (cols.length >= 6) {
                            const target = parseFloat(cols[1].textContent.trim().replace('৳', '').replace(/,/g, '')) || 0;
                            const achieved = parseFloat(cols[2].textContent.trim().replace('৳', '').replace(/,/g, '')) || 0;
                            const deals = parseInt(cols[4].textContent.trim()) || 0;
                            const status = cols[5].querySelector('span')?.textContent.trim() || '';

                            totalTarget += target;
                            totalAchieved += achieved;
                            totalDeals += deals;
                            if (status === 'Met') targetsMet++;
                            totalRows++;
                        }
                    });

                    if (totalRows > 0) {
                        const avgAchievement = (totalAchieved / totalTarget) * 100;

                        $('#overall-achievement').text(avgAchievement.toFixed(0) + '%');
                        $('#overall-progress').css('width', Math.min(avgAchievement, 100) + '%');
                        $('#targets-met-summary').text(`${targetsMet}/${totalRows}`);
                        $('#targets-met-progress').css('width', (targetsMet / totalRows * 100) + '%');
                        $('#total-deals-summary').text(totalDeals);
                        $('#total-target').text('৳ ' + totalTarget.toLocaleString());
                        $('#total-achieved').text('৳ ' + totalAchieved.toLocaleString());
                        $('#total-gap').text('৳ ' + (totalTarget - totalAchieved).toLocaleString());
                    }
                }
            } catch (error) {
                console.error('Error fetching target data:', error);
            }
        });
    </script>
@endsection