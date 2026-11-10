@extends(request()->boolean('embed')? 'layout.embed': 'layout.app')
@section('title', 'Home')
@section('description', 'Dashboard')


@section('content')

<style>
    :root {
        --gme-blue: #0d6efd;
        --gme-dark-blue: #073b82;
        --gme-green: #08a65a;
        --gme-orange: #ff8a00;
        --gme-purple: #5b35d5;
        --gme-teal: #079c98;
        --gme-red: #ef4444;
        --gme-text: #183153;
        --gme-muted: #718096;
        --gme-border: #e5edf6;
        --gme-bg: #f5f9fd;
    }

    .gme-dashboard {
        background: var(--gme-bg);
        min-height: calc(100vh - 70px);
        padding: 18px;
        font-family: Inter, "Segoe UI", sans-serif;
        color: var(--gme-text);
    }

    /* =====================================================
       TOP HEADER
    ===================================================== */

    .gme-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        gap: 15px;
    }

    .gme-page-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .gme-page-title-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1685ff, #0758d8);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(13, 110, 253, .20);
        font-size: 19px;
    }

    .gme-page-title h3 {
        margin: 0;
        font-size: 21px;
        font-weight: 800;
        color: #15355d;
    }

    .gme-page-title p {
        margin: 2px 0 0;
        font-size: 11px;
        color: #7890ab;
    }

    .gme-period {
        background: #fff;
        border: 1px solid #dce8f4;
        border-radius: 8px;
        padding: 8px 13px;
        font-size: 12px;
        color: #3b5573;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 7px rgba(30, 70, 110, .04);
    }

    /* =====================================================
       KPI CARDS
    ===================================================== */

    .gme-kpi-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }

    .gme-kpi {
        min-height: 96px;
        border-radius: 9px;
        color: #fff;
        padding: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(40, 80, 120, .12);
    }

    .gme-kpi::after {
        content: "";
        position: absolute;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        right: -30px;
        bottom: -40px;
        background: rgba(255,255,255,.08);
    }

    .gme-kpi.blue {
        background: linear-gradient(135deg, #087cf1, #0963d8);
    }

    .gme-kpi.green {
        background: linear-gradient(135deg, #06ad62, #008d50);
    }

    .gme-kpi.orange {
        background: linear-gradient(135deg, #ff9617, #f47700);
    }

    .gme-kpi.purple {
        background: linear-gradient(135deg, #6847df, #5032c6);
    }

    .gme-kpi.teal {
        background: linear-gradient(135deg, #08a6a2, #058d8b);
    }

    .gme-kpi.navy {
        background: linear-gradient(135deg, #155db6, #063d88);
    }

    .gme-kpi-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .gme-kpi-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .gme-kpi-label {
        font-size: 11px;
        font-weight: 600;
        opacity: .92;
        margin-bottom: 4px;
    }

    .gme-kpi-value {
        font-size: 18px;
        font-weight: 800;
        letter-spacing: .2px;
    }

    .gme-kpi-trend {
        font-size: 9px;
        margin-top: 6px;
        opacity: .95;
    }

    /* =====================================================
       MINI STATS
    ===================================================== */

    .gme-mini-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
        margin-bottom: 12px;
    }

    .gme-mini {
        background: #fff;
        border: 1px solid var(--gme-border);
        border-radius: 8px;
        padding: 11px 14px;
        min-height: 67px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(40, 80, 120, .035);
    }

    .gme-mini-icon {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #edf5ff;
        color: #1268ce;
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 38px;
        font-size: 17px;
    }

    .gme-mini-label {
        color: #7488a1;
        font-size: 10px;
        margin-bottom: 3px;
    }

    .gme-mini-value {
        font-size: 14px;
        font-weight: 800;
        color: #163a68;
    }

    .gme-mini-alert {
        color: #ef4444;
        font-size: 9px;
        margin-top: 2px;
    }

    /* =====================================================
       PANEL
    ===================================================== */

    .gme-panel {
        background: #fff;
        border: 1px solid var(--gme-border);
        border-radius: 9px;
        box-shadow: 0 2px 9px rgba(40, 80, 120, .035);
        overflow: hidden;
        height: 100%;
    }

    .gme-panel-header {
        height: 46px;
        padding: 0 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #edf2f7;
    }

    .gme-panel-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 800;
        color: #153b6b;
    }

    .gme-panel-title i {
        color: #0d6efd;
        font-size: 15px;
    }

    .gme-view-all {
        font-size: 9px;
        color: #0877df;
        text-decoration: none;
    }

    .gme-panel-body {
        padding: 12px 14px;
    }

    /* =====================================================
       MAIN GRID
    ===================================================== */

    .gme-main-grid {
        display: grid;
        grid-template-columns: 1.55fr 1.05fr 1.05fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    /* =====================================================
       SALES CHART
    ===================================================== */

    .gme-chart-wrap {
        height: 235px;
        position: relative;
    }

    .gme-chart-side {
        background: #f5faff;
        border: 1px solid #e1edf9;
        border-radius: 7px;
        padding: 12px;
        height: 100%;
    }

    .gme-side-label {
        font-size: 9px;
        color: #6f839d;
    }

    .gme-side-value {
        color: #0d4f9c;
        font-size: 17px;
        font-weight: 800;
        margin: 4px 0 14px;
    }

    .gme-achievement {
        width: 78px;
        height: 78px;
        border-radius: 50%;
        background: conic-gradient(#079c98 0 76%, #dcecef 76% 100%);
        margin: 10px auto;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gme-achievement::before {
        content: "";
        position: absolute;
        width: 59px;
        height: 59px;
        border-radius: 50%;
        background: #fff;
    }

    .gme-achievement span {
        position: relative;
        z-index: 2;
        font-size: 16px;
        font-weight: 800;
        color: #174b70;
    }

    /* =====================================================
       RECEIVABLE
    ===================================================== */

    .gme-receivable {
        display: grid;
        grid-template-columns: 125px 1fr;
        gap: 12px;
        align-items: center;
    }

    .gme-donut {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background:
            conic-gradient(
                #f43f3f 0 39%,
                #f5a623 39% 61%,
                #8d4de2 61% 76%,
                #2878df 76% 100%
            );
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .gme-donut::before {
        content: "";
        position: absolute;
        width: 74px;
        height: 74px;
        border-radius: 50%;
        background: #fff;
    }

    .gme-donut-text {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .gme-donut-text strong {
        display: block;
        font-size: 14px;
        color: #193e6c;
    }

    .gme-donut-text small {
        font-size: 8px;
        color: #8294a9;
    }

    .gme-legend-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 9px;
        margin: 7px 0;
        color: #526b88;
    }

    .gme-legend-left {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .gme-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .gme-customer-title {
        font-size: 10px;
        font-weight: 800;
        color: #24486f;
        margin: 13px 0 7px;
    }

    .gme-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8px;
    }

    .gme-table th {
        background: #f4f8fc;
        color: #6c8199;
        font-weight: 700;
        padding: 6px;
        text-align: left;
    }

    .gme-table td {
        border-bottom: 1px solid #edf2f7;
        padding: 6px;
        color: #45617d;
    }

    .gme-red {
        color: #ef4444 !important;
    }

    /* =====================================================
       SALES PERFORMANCE
    ===================================================== */

    .gme-performance {
        padding-top: 4px;
    }

    .gme-performance-row {
        display: grid;
        grid-template-columns: 80px 1fr 65px 35px;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
    }

    .gme-performance-name {
        font-size: 9px;
        color: #45617d;
    }

    .gme-progress {
        height: 14px;
        background: #edf3fa;
        border-radius: 2px;
        overflow: hidden;
    }

    .gme-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #167de9, #0785e8);
        border-radius: 2px;
    }

    .gme-performance-value {
        font-size: 9px;
        color: #385674;
        text-align: right;
    }

    .gme-performance-percent {
        font-size: 9px;
        color: #56718d;
        text-align: right;
    }

    /* =====================================================
       LOWER PANELS
    ===================================================== */

    .gme-lower-grid {
        display: grid;
        grid-template-columns: 1.35fr 1.05fr 1.05fr;
        gap: 12px;
        margin-bottom: 12px;
    }

    /* Pipeline */

    .gme-pipeline {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 3px;
        padding: 18px 3px;
    }

    .gme-pipeline-step {
        text-align: center;
        flex: 1;
        position: relative;
    }

    .gme-pipeline-step:not(:last-child)::after {
        content: "→";
        position: absolute;
        right: -8px;
        top: 12px;
        color: #7c91aa;
        font-weight: 800;
    }

    .gme-pipeline-circle {
        width: 38px;
        height: 38px;
        margin: auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        box-shadow: 0 3px 8px rgba(0,0,0,.08);
    }

    .gme-pipeline-label {
        margin-top: 8px;
        font-size: 8px;
        color: #5e7691;
    }

    .p-blue { background:#2980dc; }
    .p-purple { background:#7751d9; }
    .p-orange { background:#f18b25; }
    .p-pink { background:#8d55d9; }
    .p-teal { background:#149d9b; }
    .p-green { background:#159a63; }

    /* Inventory */

    .gme-inventory-grid {
        display: grid;
        grid-template-columns: 1fr 135px;
        gap: 12px;
    }

    .gme-inventory-stat {
        margin-bottom: 11px;
    }

    .gme-inventory-stat label {
        display: block;
        font-size: 8px;
        color: #768ba2;
    }

    .gme-inventory-stat strong {
        font-size: 13px;
        color: #1e4772;
    }

    .gme-stock-ring {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        margin: auto;
        background: conic-gradient(
            #0a9b68 0 62%,
            #357ad8 62% 77%,
            #f29a28 77% 89%,
            #91a4ba 89% 100%
        );
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .gme-stock-ring::before {
        content: "";
        width: 66px;
        height: 66px;
        border-radius: 50%;
        background: #fff;
        position: absolute;
    }

    .gme-stock-center {
        position: relative;
        text-align: center;
        z-index: 2;
    }

    .gme-stock-center strong {
        display: block;
        font-size: 15px;
    }

    .gme-stock-center small {
        font-size: 8px;
        color: #788ca3;
    }

    /* Purchase */

    .gme-purchase-stat {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 12px;
    }

    .gme-purchase-box {
        background: #f5f9fd;
        border: 1px solid #e5edf5;
        border-radius: 6px;
        padding: 9px;
    }

    .gme-purchase-box span {
        display: block;
        color: #748aa2;
        font-size: 8px;
    }

    .gme-purchase-box strong {
        display: block;
        margin-top: 4px;
        color: #1b456f;
        font-size: 12px;
    }

    /* =====================================================
       BOTTOM GRID
    ===================================================== */

    .gme-bottom-grid {
        display: grid;
        grid-template-columns: 1.25fr 1fr 1fr 1.25fr;
        gap: 12px;
    }

    .gme-service-counts {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 6px;
        margin-bottom: 10px;
    }

    .gme-service-count {
        background: #f5f9fd;
        border: 1px solid #e4edf6;
        border-radius: 6px;
        padding: 8px 4px;
        text-align: center;
    }

    .gme-service-count span {
        display: block;
        font-size: 7px;
        color: #7388a0;
    }

    .gme-service-count strong {
        display: block;
        font-size: 13px;
        color: #214b76;
        margin-top: 3px;
    }

    .gme-position-row,
    .gme-profit-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #edf2f7;
        font-size: 9px;
    }

    .gme-position-row span,
    .gme-profit-row span {
        color: #6f849d;
    }

    .gme-position-row strong,
    .gme-profit-row strong {
        color: #1d4773;
    }

    .gme-net-box {
        margin-top: 9px;
        background: #eaf8f2;
        border: 1px solid #cceee0;
        border-radius: 6px;
        padding: 9px;
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        color: #17744f;
        font-weight: 800;
    }

    /* Alerts */

    .gme-alert {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 0;
        border-bottom: 1px solid #edf2f7;
        font-size: 9px;
    }

    .gme-alert:last-child {
        border-bottom: 0;
    }

    .gme-alert-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex: 0 0 7px;
    }

    .gme-alert-text {
        flex: 1;
        color: #526c88;
    }

    .gme-alert-link {
        color: #1672d2;
        font-size: 8px;
    }

    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media(max-width: 1400px) {
        .gme-kpi-grid,
        .gme-mini-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .gme-main-grid {
            grid-template-columns: 1fr 1fr;
        }

        .gme-main-grid > :last-child {
            grid-column: span 2;
        }

        .gme-bottom-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 992px) {
        .gme-main-grid,
        .gme-lower-grid,
        .gme-bottom-grid {
            grid-template-columns: 1fr;
        }

        .gme-main-grid > :last-child {
            grid-column: auto;
        }

        .gme-kpi-grid,
        .gme-mini-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 576px) {
        .gme-dashboard {
            padding: 10px;
        }

        .gme-page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .gme-kpi-grid,
        .gme-mini-grid {
            grid-template-columns: 1fr;
        }

        .gme-pipeline {
            overflow-x: auto;
            min-width: 650px;
        }

        .gme-pipeline-wrapper {
            overflow-x: auto;
        }

        .gme-receivable {
            grid-template-columns: 1fr;
        }

        .gme-purchase-stat {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>


<div class="gme-dashboard">

    <!-- =====================================================
         HEADER
    ====================================================== -->

    <div class="gme-page-header">

        <div class="gme-page-title">
            <div class="gme-page-title-icon">
                <i class="uil uil-chart-line"></i>
            </div>

            <div>
                <h3>Management Dashboard</h3>
                <p>Overall business performance at a glance</p>
            </div>
        </div>

        <div class="gme-period">
            <i class="uil uil-calendar-alt"></i>
            <div>
                <strong>This Month</strong><br>
                <small>
                    {{ now()->startOfMonth()->format('d M Y') }}
                    – 
                    {{ now()->format('d M Y') }}
                </small>
            </div>
            <i class="uil uil-angle-down"></i>
        </div>

    </div>


    @if(hasPermission('dashboard.admin'))

    <!-- =====================================================
         MAIN Daily KPI
    ====================================================== -->

    <div class="gme-kpi-grid">

        
        <div class="gme-kpi purple">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Todays Sales</div>
                    <div class="gme-kpi-value" id="today-sales">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-today-sales">
                        ↑ 0% vs. last day
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-chart-bar"></i>
                </div>
            </div>
        </div>
 
        <div class="gme-kpi teal">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Todays Due Sales</div>
                    <div class="gme-kpi-value" id="today-due-sales">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-today-due-sales">
                        ↑ 0% vs. last day
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-box"></i>
                </div>
            </div>
        </div>

        <div class="gme-kpi navy">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Todays Paid Sales</div>
                    <div class="gme-kpi-value" id="today-paid-sales">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-today-paid-sales">
                        ↑ 0% vs. last day
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-chart-growth"></i>
                </div>
            </div>
        </div>

        <div class="gme-kpi green">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Todays Collection</div>
                    <div class="gme-kpi-value" id="today-collection">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-today-collection">
                        ↑ 0% vs. last day
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-coins"></i>
                </div>
            </div>
        </div>

        <div class="gme-kpi orange">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Todays Purchase</div>
                    <div class="gme-kpi-value" id="today-purchase">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-today-purchase">
                        ↑ 0% vs. last day
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-users-alt"></i>
                </div>
            </div>
        </div>

        <div class="gme-kpi blue">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Todays Payment</div>
                    <div class="gme-kpi-value" id="today-payment">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-today-payment">
                        ↑ 0% vs. last day
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-credit-card"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- =====================================================
         MINI Yearly KPI 
    ====================================================== -->
    <div class="gme-kpi-grid">

        <div class="gme-kpi blue">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Total Sales</div>
                    <div class="gme-kpi-value" id="total-sales">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-total-sales">
                        ↑ 0% vs. last year
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-chart-bar"></i>
                </div>
            </div>
        </div>

        <div class="gme-kpi green">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Total Collection</div>
                    <div class="gme-kpi-value" id="total-collections">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-total-collections">
                        ↑ 0% vs. last year
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-coins"></i>
                </div>
            </div>
        </div>

        <div class="gme-kpi navy">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Total Purchase</div>
                    <div class="gme-kpi-value" id="total-purchases">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-total-purchases">
                        ↑ 0% vs. last year
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-users-alt"></i>
                </div>
            </div>
        </div>


        <div class="gme-kpi orange">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Total Receivable</div>
                    <div class="gme-kpi-value" id="total-receivables">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-total-receivables">
                        ↑ 0% vs. last year
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-users-alt"></i>
                </div>
            </div>
        </div>

        <div class="gme-kpi purple">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Total Payable</div>
                    <div class="gme-kpi-value" id="total-payables">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-total-payables">
                        ↑ 0% vs. last year
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-credit-card"></i>
                </div>
            </div>
        </div>

        <div class="gme-kpi teal">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Total Payments</div>
                    <div class="gme-kpi-value" id="total-payments">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-total-payments">
                        ↑ 0% vs. last year
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-box"></i>
                </div>
            </div>
        </div>


        <div class="gme-kpi teal d-none">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Inventory Value</div>
                    <div class="gme-kpi-value" id="inventory-values">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-inventory-values">
                        ↑ 0% vs. last year
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-box"></i>
                </div>
            </div>
        </div>
 

        <div class="gme-kpi navy d-none">
            <div class="gme-kpi-top">
                <div>
                    <div class="gme-kpi-label">Net Profit</div>
                    <div class="gme-kpi-value" id="net-profit">৳ 0</div>
                    <div class="gme-kpi-trend" id="pre-net-profit">
                        ↑ 0% vs. last year
                    </div>
                </div>
                <div class="gme-kpi-icon">
                    <i class="uil uil-chart-growth"></i>
                </div>
            </div>
        </div>

    </div>


    <!-- =====================================================
         MINI CARDS
    ====================================================== -->

    <div class="gme-mini-grid">

        <div class="gme-mini">
            <div class="gme-mini-icon">
                <i class="uil uil-truck"></i>
            </div>
            <div>
                <div class="gme-mini-label">Pending Orders</div>
                <div class="gme-mini-value">12</div>
                <div class="gme-mini-alert">3 delayed</div>
            </div>
        </div>

        <div class="gme-mini">
            <div class="gme-mini-icon">
                <i class="uil uil-wrench"></i>
            </div>
            <div>
                <div class="gme-mini-label">Service Pending</div>
                <div class="gme-mini-value">8</div>
                <div class="gme-mini-alert">4 &gt; 15 days</div>
            </div>
        </div>

        <div class="gme-mini">
            <div class="gme-mini-icon">
                <i class="uil uil-money-bill"></i>
            </div>
            <div>
                <div class="gme-mini-label">Cash in Hand</div>
                <div class="gme-mini-value">৳ 8,50,000</div>
            </div>
        </div>

        <div class="gme-mini">
            <div class="gme-mini-icon">
                <i class="uil uil-university"></i>
            </div>
            <div>
                <div class="gme-mini-label">Bank Balance</div>
                <div class="gme-mini-value">৳ 42,70,000</div>
            </div>
        </div>

        <div class="gme-mini">
            <div class="gme-mini-icon">
                <i class="uil uil-import"></i>
            </div>
            <div>
                <div class="gme-mini-label">Today's Collection</div>
                <div class="gme-mini-value">৳ 6,20,000</div>
            </div>
        </div>

        <div class="gme-mini">
            <div class="gme-mini-icon">
                <i class="uil uil-export"></i>
            </div>
            <div>
                <div class="gme-mini-label">Today's Payment</div>
                <div class="gme-mini-value">৳ 3,80,000</div>
            </div>
        </div>

    </div>


    <!-- =====================================================
         MAIN 3 PANELS
    ====================================================== -->

    <div class="gme-main-grid">

        <!-- Sales vs Collection -->

        <div class="gme-panel">

            <div class="gme-panel-header">
                <div class="gme-panel-title">
                    <i class="uil uil-chart"></i>
                    Sales vs Collection
                </div>

                <select class="form-select form-select-sm"
                        style="width:90px;font-size:9px;">
                    <option>This Year</option>
                    <option>Last Year</option>
                </select>
            </div>

            <div class="gme-panel-body">

                <div class="row">

                    <div class="col-md-8">

                        <div class="gme-chart-wrap">
                            <canvas id="salesCollectionChart"></canvas>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="gme-chart-side">

                            <div class="gme-side-label">
                                Sales Target (YTD)
                            </div>

                            <div class="gme-side-value">
                                ৳ 6,50,00,000
                            </div>

                            <div class="gme-side-label">
                                Achieved
                            </div>

                            <div class="gme-side-value">
                                ৳ 4,95,00,000
                            </div>

                            <div class="gme-achievement">
                                <span>76%</span>
                            </div>

                            <div style="
                                text-align:center;
                                font-size:8px;
                                color:#71849a;">
                                Achievement
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Receivable -->

        <div class="gme-panel">

            <div class="gme-panel-header">
                <div class="gme-panel-title">
                    <i class="uil uil-users-alt"></i>
                    Receivable / Customer Due
                </div>
            </div>

            <div class="gme-panel-body">

                <div class="gme-receivable">

                    <div class="gme-donut">

                        <div class="gme-donut-text">
                            <strong>৳ 1.85 Cr</strong>
                            <small>Total Due</small>
                        </div>

                    </div>

                    <div>

                        <div class="gme-legend-item">
                            <div class="gme-legend-left">
                                <span class="gme-dot"
                                      style="background:#f43f3f"></span>
                                Overdue
                            </div>
                            <strong>৳ 72,00,000</strong>
                        </div>

                        <div class="gme-legend-item">
                            <div class="gme-legend-left">
                                <span class="gme-dot"
                                      style="background:#f5a623"></span>
                                Due This Month
                            </div>
                            <strong>৳ 41,00,000</strong>
                        </div>

                        <div class="gme-legend-item">
                            <div class="gme-legend-left">
                                <span class="gme-dot"
                                      style="background:#8d4de2"></span>
                                Due &gt; 90 Days
                            </div>
                            <strong>৳ 28,00,000</strong>
                        </div>

                        <div class="gme-legend-item">
                            <div class="gme-legend-left">
                                <span class="gme-dot"
                                      style="background:#2878df"></span>
                                Others
                            </div>
                            <strong>৳ 44,40,000</strong>
                        </div>

                    </div>

                </div>


                <div class="gme-customer-title">
                    Top Due Customers
                </div>

                <table class="gme-table">

                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Total Due</th>
                        <th>Overdue</th>
                        <th>Last Payment</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <td>ABC Diagnostic Center</td>
                        <td>12,50,000</td>
                        <td class="gme-red">5,20,000</td>
                        <td>10 Sep 2026</td>
                    </tr>

                    <tr>
                        <td>XYZ Hospital</td>
                        <td>8,40,000</td>
                        <td class="gme-red">2,10,000</td>
                        <td>18 Sep 2026</td>
                    </tr>

                    <tr>
                        <td>Green Life Hospital</td>
                        <td>6,75,000</td>
                        <td class="gme-red">1,80,000</td>
                        <td>15 Sep 2026</td>
                    </tr>

                    <tr>
                        <td>City Diagnostic</td>
                        <td>5,20,000</td>
                        <td class="gme-red">1,25,000</td>
                        <td>12 Sep 2026</td>
                    </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Sales Performance -->

        <div class="gme-panel">

            <div class="gme-panel-header">

                <div class="gme-panel-title">
                    <i class="uil uil-chart-bar"></i>
                    Sales Performance
                </div>

                <select class="form-select form-select-sm"
                        style="width:90px;font-size:9px;">
                    <option>By Category</option>
                    <option>By Product</option>
                </select>

            </div>

            <div class="gme-panel-body">

                <div class="gme-performance">

                    @php
                        $performance = [
                            ['Ultrasound','42,00,000','28%',100],
                            ['X-Ray','31,00,000','21%',74],
                            ['Lab Equipment','25,00,000','17%',60],
                            ['Patient Monitor','14,00,000','9%',35],
                            ['ECG','9,00,000','6%',23],
                            ['Spare Parts','7,00,000','5%',18],
                            ['Others','6,42,000','4%',15],
                        ];
                    @endphp

                    @foreach($performance as $row)

                        <div class="gme-performance-row">

                            <div class="gme-performance-name">
                                {{ $row[0] }}
                            </div>

                            <div class="gme-progress">
                                <div class="gme-progress-bar"
                                     style="width:{{ $row[3] }}%">
                                </div>
                            </div>

                            <div class="gme-performance-value">
                                {{ $row[1] }}
                            </div>

                            <div class="gme-performance-percent">
                                {{ $row[2] }}
                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         SECOND ROW
    ====================================================== -->

    <div class="gme-lower-grid">

        <!-- Pipeline -->

        <div class="gme-panel">

            <div class="gme-panel-header">

                <div class="gme-panel-title">
                    <i class="uil uil-truck"></i>
                    Order / Delivery Pipeline
                </div>

                <a href="#" class="gme-view-all">
                    View Details →
                </a>

            </div>

            <div class="gme-panel-body gme-pipeline-wrapper">

                <div class="gme-pipeline">

                    <div class="gme-pipeline-step">
                        <div class="gme-pipeline-circle p-blue">18</div>
                        <div class="gme-pipeline-label">Quotation</div>
                    </div>

                    <div class="gme-pipeline-step">
                        <div class="gme-pipeline-circle p-blue">12</div>
                        <div class="gme-pipeline-label">Confirmed PO</div>
                    </div>

                    <div class="gme-pipeline-step">
                        <div class="gme-pipeline-circle p-orange">7</div>
                        <div class="gme-pipeline-label">Purchase Pending</div>
                    </div>

                    <div class="gme-pipeline-step">
                        <div class="gme-pipeline-circle p-purple">5</div>
                        <div class="gme-pipeline-label">In Transit</div>
                    </div>

                    <div class="gme-pipeline-step">
                        <div class="gme-pipeline-circle p-teal">3</div>
                        <div class="gme-pipeline-label">Customs</div>
                    </div>

                    <div class="gme-pipeline-step">
                        <div class="gme-pipeline-circle p-green">8</div>
                        <div class="gme-pipeline-label">Ready Delivery</div>
                    </div>

                    <div class="gme-pipeline-step">
                        <div class="gme-pipeline-circle p-green">24</div>
                        <div class="gme-pipeline-label">Delivered</div>
                    </div>

                </div>

            </div>

        </div>


        <!-- Inventory -->

        <div class="gme-panel">

            <div class="gme-panel-header">

                <div class="gme-panel-title">
                    <i class="uil uil-box"></i>
                    Inventory Status
                </div>

                <a href="#" class="gme-view-all">
                    View All →
                </a>

            </div>

            <div class="gme-panel-body">

                <div class="gme-inventory-grid">

                    <div>

                        <div class="gme-inventory-stat">
                            <label>Total Stock Value</label>
                            <strong>৳ 2,48,75,000</strong>
                        </div>

                        <div class="gme-inventory-stat">
                            <label>Available Machines</label>
                            <strong>48</strong>
                        </div>

                        <div class="gme-inventory-stat">
                            <label>Low Stock Items</label>
                            <strong class="gme-red">8</strong>
                        </div>

                        <div class="gme-inventory-stat">
                            <label>Out of Stock</label>
                            <strong class="gme-red">3</strong>
                        </div>

                    </div>

                    <div>

                        <div class="gme-stock-ring">

                            <div class="gme-stock-center">
                                <strong>156</strong>
                                <small>Total Items</small>
                            </div>

                        </div>

                        <div style="font-size:8px;text-align:center;color:#71859b;margin-top:6px;">
                            Low Stock Alert
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- Purchase -->

        <div class="gme-panel">

            <div class="gme-panel-header">

                <div class="gme-panel-title">
                    <i class="uil uil-shopping-cart"></i>
                    Purchase & Supplier
                </div>

                <a href="#" class="gme-view-all">
                    View Details →
                </a>

            </div>

            <div class="gme-panel-body">

                <div class="gme-purchase-stat">

                    <div class="gme-purchase-box">
                        <span>Purchase This Month</span>
                        <strong>৳ 35,00,000</strong>
                    </div>

                    <div class="gme-purchase-box">
                        <span>Purchase Pending</span>
                        <strong>৳ 18,00,000</strong>
                    </div>

                    <div class="gme-purchase-box">
                        <span>Supplier Payable</span>
                        <strong>৳ 62,00,000</strong>
                    </div>

                    <div class="gme-purchase-box">
                        <span>Pending PO</span>
                        <strong>14</strong>
                    </div>

                </div>

                <div class="gme-customer-title">
                    Supplier Wise Payable
                </div>

                <table class="gme-table">

                    <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Payable</th>
                        <th>Overdue</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <td>VINNO</td>
                        <td>৳ 92,474</td>
                        <td class="gme-red">৳ 26,000</td>
                    </tr>

                    <tr>
                        <td>Supplier B</td>
                        <td>৳ 35,000</td>
                        <td class="gme-red">৳ 8,000</td>
                    </tr>

                    <tr>
                        <td>Supplier C</td>
                        <td>৳ 21,000</td>
                        <td class="gme-red">৳ 3,000</td>
                    </tr>

                    <tr>
                        <td>Others</td>
                        <td>৳ 12,500</td>
                        <td class="gme-red">৳ 2,500</td>
                    </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- =====================================================
         BOTTOM 4 PANELS
    ====================================================== -->

    <div class="gme-bottom-grid">

        <!-- Service -->

        <div class="gme-panel">

            <div class="gme-panel-header">

                <div class="gme-panel-title">
                    <i class="uil uil-wrench"></i>
                    Service Dashboard
                </div>

                <a href="#" class="gme-view-all">
                    View All →
                </a>

            </div>

            <div class="gme-panel-body">

                <div class="gme-service-counts">

                    <div class="gme-service-count">
                        <span>New Complaints</span>
                        <strong>12</strong>
                    </div>

                    <div class="gme-service-count">
                        <span>Pending</span>
                        <strong>8</strong>
                    </div>

                    <div class="gme-service-count">
                        <span>Engineer Assigned</span>
                        <strong>6</strong>
                    </div>

                    <div class="gme-service-count">
                        <span>Waiting Spare</span>
                        <strong>4</strong>
                    </div>

                    <div class="gme-service-count">
                        <span>Completed</span>
                        <strong>35</strong>
                    </div>

                </div>

                <table class="gme-table">

                    <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Machine</th>
                        <th>Problem</th>
                        <th>Days</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <td>ABC Diagnostic</td>
                        <td>X-Ray</td>
                        <td>Detector</td>
                        <td class="gme-red">18</td>
                    </tr>

                    <tr>
                        <td>XYZ Hospital</td>
                        <td>USG</td>
                        <td>Probe</td>
                        <td class="gme-red">12</td>
                    </tr>

                    <tr>
                        <td>DEF Clinic</td>
                        <td>ECG</td>
                        <td>Software</td>
                        <td>7</td>
                    </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Cash Bank -->

        <div class="gme-panel">

            <div class="gme-panel-header">
                <div class="gme-panel-title">
                    <i class="uil uil-university"></i>
                    Cash & Bank Position
                </div>

                <a href="javascript:void(0)" class="gme-view-all" id="bankToggle">
                    View Details →
                </a>
            </div>

            <div class="gme-panel-body">

                <!-- Total Bank Balance -->

                <div style="background:linear-gradient(135deg,#0759b5,#0d78df);border-radius:8px;padding:10px 14px;color:#fff;margin-bottom:8px;">

                    <div style="font-size:8px;opacity:.9;margin-bottom:3px;">
                        Total Bank Balance
                    </div>

                    <div style="font-size:18px;font-weight:800;line-height:1.2;">
                        ৳ 42,70,000
                    </div>

                    <div style="font-size:7px;opacity:.85;margin-top:3px;">
                        Across 4 Bank Accounts
                    </div>

                </div>


                <!-- Bank List -->

                <div id="bankList" style="display:flex;flex-direction:column;gap:6px;">

                    <!-- City Bank -->

                    <div class="bank-item" style="display:flex;align-items:center;justify-content:space-between;padding:7px 9px;background:#f7faff;border:1px solid #e5edf6;border-radius:7px;">

                        <div style="display:flex;align-items:center;gap:8px;">

                            <div style="width:30px;height:30px;min-width:30px;border-radius:7px;background:#e9f2ff;color:#1268ce;display:flex;align-items:center;justify-content:center;">
                                <i class="uil uil-university"></i>
                            </div>

                            <div>
                                <div style="font-size:9px;font-weight:800;color:#21476f;">
                                    City Bank
                                </div>

                                <div style="font-size:7px;color:#8294a8;">
                                    **** 4521
                                </div>
                            </div>

                        </div>

                        <div style="text-align:right;">
                            <div style="font-size:10px;font-weight:800;color:#174b77;">
                                ৳ 18,50,000
                            </div>

                            <div style="font-size:6px;color:#16a06a;">
                                Available
                            </div>
                        </div>

                    </div>


                    <!-- Dutch-Bangla Bank -->

                    <div class="bank-item" style="display:flex;align-items:center;justify-content:space-between;padding:7px 9px;background:#f7faff;border:1px solid #e5edf6;border-radius:7px;">

                        <div style="display:flex;align-items:center;gap:8px;">

                            <div style="width:30px;height:30px;min-width:30px;border-radius:7px;background:#eaf8f0;color:#09985f;display:flex;align-items:center;justify-content:center;">
                                <i class="uil uil-university"></i>
                            </div>

                            <div>
                                <div style="font-size:9px;font-weight:800;color:#21476f;">
                                    Dutch-Bangla Bank
                                </div>

                                <div style="font-size:7px;color:#8294a8;">
                                    **** 7834
                                </div>
                            </div>

                        </div>

                        <div style="text-align:right;">
                            <div style="font-size:10px;font-weight:800;color:#174b77;">
                                ৳ 12,75,000
                            </div>

                            <div style="font-size:6px;color:#16a06a;">
                                Available
                            </div>
                        </div>

                    </div>


                    <!-- BRAC Bank -->

                    <div class="bank-item" style="display:flex;align-items:center;justify-content:space-between;padding:7px 9px;background:#f7faff;border:1px solid #e5edf6;border-radius:7px;">

                        <div style="display:flex;align-items:center;gap:8px;">

                            <div style="width:30px;height:30px;min-width:30px;border-radius:7px;background:#fff3e8;color:#ef8518;display:flex;align-items:center;justify-content:center;">
                                <i class="uil uil-university"></i>
                            </div>

                            <div>
                                <div style="font-size:9px;font-weight:800;color:#21476f;">
                                    BRAC Bank
                                </div>

                                <div style="font-size:7px;color:#8294a8;">
                                    **** 2145
                                </div>
                            </div>

                        </div>

                        <div style="text-align:right;">
                            <div style="font-size:10px;font-weight:800;color:#174b77;">
                                ৳ 8,40,000
                            </div>

                            <div style="font-size:6px;color:#16a06a;">
                                Available
                            </div>
                        </div>

                    </div>


                    <!-- Other Bank -->

                    <div class="bank-item bank-hidden" style="display:none;align-items:center;justify-content:space-between;padding:7px 9px;background:#f7faff;border:1px solid #e5edf6;border-radius:7px;">

                        <div style="display:flex;align-items:center;gap:8px;">

                            <div style="width:30px;height:30px;min-width:30px;border-radius:7px;background:#f1edff;color:#6846d8;display:flex;align-items:center;justify-content:center;">
                                <i class="uil uil-university"></i>
                            </div>

                            <div>
                                <div style="font-size:9px;font-weight:800;color:#21476f;">
                                    Other Bank
                                </div>

                                <div style="font-size:7px;color:#8294a8;">
                                    **** 9021
                                </div>
                            </div>

                        </div>

                        <div style="text-align:right;">
                            <div style="font-size:10px;font-weight:800;color:#174b77;">
                                ৳ 3,05,000
                            </div>

                            <div style="font-size:6px;color:#16a06a;">
                                Available
                            </div>
                        </div>

                    </div>


                    <!-- Add More Bank Example -->

                    <div class="bank-item bank-hidden" style="display:none;align-items:center;justify-content:space-between;padding:7px 9px;background:#f7faff;border:1px solid #e5edf6;border-radius:7px;">

                        <div style="display:flex;align-items:center;gap:8px;">

                            <div style="width:30px;height:30px;min-width:30px;border-radius:7px;background:#fff0f3;color:#e43b61;display:flex;align-items:center;justify-content:center;">
                                <i class="uil uil-university"></i>
                            </div>

                            <div>
                                <div style="font-size:9px;font-weight:800;color:#21476f;">
                                    Prime Bank
                                </div>

                                <div style="font-size:7px;color:#8294a8;">
                                    **** 5689
                                </div>
                            </div>

                        </div>

                        <div style="text-align:right;">
                            <div style="font-size:10px;font-weight:800;color:#174b77;">
                                ৳ 2,50,000
                            </div>

                            <div style="font-size:6px;color:#16a06a;">
                                Available
                            </div>
                        </div>

                    </div>


                    <div class="bank-item bank-hidden" style="display:none;align-items:center;justify-content:space-between;padding:7px 9px;background:#f7faff;border:1px solid #e5edf6;border-radius:7px;">

                        <div style="display:flex;align-items:center;gap:8px;">

                            <div style="width:30px;height:30px;min-width:30px;border-radius:7px;background:#f0f4ff;color:#315bd8;display:flex;align-items:center;justify-content:center;">
                                <i class="uil uil-university"></i>
                            </div>

                            <div>
                                <div style="font-size:9px;font-weight:800;color:#21476f;">
                                    Mutual Trust Bank
                                </div>

                                <div style="font-size:7px;color:#8294a8;">
                                    **** 3356
                                </div>
                            </div>

                        </div>

                        <div style="text-align:right;">
                            <div style="font-size:10px;font-weight:800;color:#174b77;">
                                ৳ 1,80,000
                            </div>

                            <div style="font-size:6px;color:#16a06a;">
                                Available
                            </div>
                        </div>

                    </div>

                </div>


                <!-- Cash in Hand -->

                <div style="margin-top:7px;padding:8px 10px;border-radius:7px;background:#f0fbf6;border:1px solid #d4f0e2;display:flex;align-items:center;justify-content:space-between;">

                    <div style="display:flex;align-items:center;gap:8px;">

                        <div style="width:28px;height:28px;border-radius:7px;background:#d9f6e8;color:#07935b;display:flex;align-items:center;justify-content:center;">
                            <i class="uil uil-money-bill"></i>
                        </div>

                        <div>
                            <div style="font-size:8px;color:#64816f;">
                                Cash in Hand
                            </div>

                            <div style="font-size:10px;font-weight:800;color:#19724d;">
                                ৳ 8,50,000
                            </div>
                        </div>

                    </div>

                    <div style="text-align:right;font-size:7px;color:#5f7e6d;">
                        Physical Cash
                    </div>

                </div>


                <!-- Total -->

                <div style="margin-top:6px;padding:8px 10px;border-radius:7px;background:#eef6ff;border:1px solid #d8e9fb;display:flex;justify-content:space-between;align-items:center;">

                    <span style="font-size:8px;color:#55718f;font-weight:700;">
                        Total Cash + Bank
                    </span>

                    <strong style="font-size:12px;color:#075bb6;">
                        ৳ 51,20,000
                    </strong>

                </div>

            </div>

        </div>

        <!-- Profit Loss -->

        <div class="gme-panel">

            <div class="gme-panel-header">

                <div class="gme-panel-title">
                    <i class="uil uil-chart-growth"></i>
                    Profit & Loss (YTD)
                </div>

                <a href="#" class="gme-view-all">
                    View Details →
                </a>

            </div>

            <div class="gme-panel-body">

                <div class="gme-profit-row">
                    <span>Revenue</span>
                    <strong>৳ 85,00,000</strong>
                </div>

                <div class="gme-profit-row">
                    <span>COGS</span>
                    <strong>৳ 58,00,000</strong>
                </div>

                <div class="gme-profit-row">
                    <span>Gross Profit</span>
                    <strong>৳ 27,00,000</strong>
                </div>

                <div class="gme-profit-row">
                    <span>Operating Expense</span>
                    <strong>৳ 11,00,000</strong>
                </div>

                <div class="gme-net-box">
                    <span>Net Profit</span>
                    <span>৳ 16,20,000</span>
                </div>

            </div>

        </div>


        <!-- Management Alerts -->

        <div class="gme-panel">

            <div class="gme-panel-header">

                <div class="gme-panel-title">
                    <i class="uil uil-bell"></i>
                    Management Alerts
                    <span style="
                        background:#ef4444;
                        color:#fff;
                        font-size:8px;
                        border-radius:10px;
                        padding:2px 6px;">
                        10
                    </span>
                </div>

                <a href="#" class="gme-view-all">
                    View All →
                </a>

            </div>

            <div class="gme-panel-body">

                <div class="gme-alert">
                    <span class="gme-alert-dot" style="background:#ef4444"></span>
                    <span class="gme-alert-text">
                        12 customers have overdue &gt; 90 days
                    </span>
                    <span class="gme-alert-link">View</span>
                </div>

                <div class="gme-alert">
                    <span class="gme-alert-dot" style="background:#f97316"></span>
                    <span class="gme-alert-text">
                        5 purchase orders delayed
                    </span>
                    <span class="gme-alert-link">View</span>
                </div>

                <div class="gme-alert">
                    <span class="gme-alert-dot" style="background:#f59e0b"></span>
                    <span class="gme-alert-text">
                        8 machines pending delivery &gt; 7 days
                    </span>
                    <span class="gme-alert-link">View</span>
                </div>

                <div class="gme-alert">
                    <span class="gme-alert-dot" style="background:#ef4444"></span>
                    <span class="gme-alert-text">
                        3 products below minimum stock
                    </span>
                    <span class="gme-alert-link">View</span>
                </div>

                <div class="gme-alert">
                    <span class="gme-alert-dot" style="background:#22c55e"></span>
                    <span class="gme-alert-text">
                        4 service complaints pending &gt; 15 days
                    </span>
                    <span class="gme-alert-link">View</span>
                </div>

                <div class="gme-alert">
                    <span class="gme-alert-dot" style="background:#2563eb"></span>
                    <span class="gme-alert-text">
                        9 new enquiry from 2 hospitals
                    </span>
                    <span class="gme-alert-link">View</span>
                </div>

            </div>

        </div>

    </div>

    @endif


    @if(hasPermission('dashboard.user') && !hasPermission('dashboard.admin'))

        @include('user-dashboard')

    @endif

</div>


<footer class="footer-wrapper p-3 text-center"
        style="
            background:#fff;
            border-top:1px solid #e5edf6;
            font-size:11px;
            color:#7a8da5;
        ">

    @php
        date_default_timezone_set('Asia/Dhaka');
    @endphp

    Copyright © {{ date('Y') }}

    <a href="https://gmebd.com"
       target="_blank"
       style="color:#1268ce;text-decoration:none;">

        Global Medical Engineering (BD) Ltd

    </a>

    . All rights reserved

</footer>

@endsection

 @section('page_scripts')

    @stack('script')

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

    <script>

        $(document).ready(function () {

            /* =====================================================
               EXISTING SUMMARY AJAX
            ====================================================== */

            function formatMoney(value) {

                value = Number(value) || 0;

                return new Intl.NumberFormat('en-IN', { minimumFractionDigits: 2,  maximumFractionDigits: 2 }).format(value);

            }


            function dailyloadSummary(type) {
                $.ajax({
                    url: "{{ route('dashboard.summary') }}",
                    type: "GET",
                    data: { type: type},
                    success: function (response) {


                        let currentSales = Number(response.currentSales) || 0;
                        let previousSales = Number(response.previousSales) || 0;

                        let dueSales = Number(response.dueSales) || 0;
                        let previousDueSales = Number(response.previousDueSales) || 0;

                        let paidSales = Number(response.paidSales) || 0;
                        let previousPaidSales = Number(response.previousPaidSales) || 0;
                       
                        let currentCollection = Number(response.currentCollection) || 0;
                        let previousCollection = Number(response.previousCollection) || 0;

                        let currentPurchase = Number(response.currentPurchase) || 0;
                        let previousPurchase = Number(response.previousPurchase) || 0;

                        let currentPayment = Number(response.currentPayment) || 0;
                        let previousPayment = Number(response.previousPayment) || 0;
 

                        /* Existing cards */ 
                        $('#today-sales').text(currentSales.toFixed(2));
                        $('#pre-today-sales').text('PRV: ' + previousSales.toFixed(2));
 
                        $('#today-due-sales').text(dueSales.toFixed(2));
                        $('#pre-today-due-sales').text('PRV: ' + previousDueSales.toFixed(2));
 
                        $('#today-paid-sales').text(paidSales.toFixed(2));
                        $('#pre-today-paid-sales').text('PRV: ' + previousPaidSales.toFixed(2));
 
                        $('#today-collection').text(currentCollection.toFixed(2));
                        $('#pre-today-collection').text('PRV: ' + previousCollection.toFixed(2));

                        $('#today-purchase').text(currentPurchase.toFixed(2));
                        $('#pre-today-purchase').text('PRV: ' + previousPurchase.toFixed(2));
 
                        $('#today-payment').text(currentPayment.toFixed(2));
                        $('#pre-today-payment').text('PRV: ' + previousPayment.toFixed(2));
  
                    },

                    error: function (xhr, status, error) {
                        console.error( 'Dashboard summary error:', error );
                    }

                });

            }

            function loadSummary(type) {
                $.ajax({
                    url: "{{ route('dashboard.summary') }}",
                    type: "GET",
                    data: { type: type},
                    success: function (response) {
                        let totalSales = Number(response.currentSales) || 0;
                        let previousSales = Number(response.previousSales) || 0;

                        let totalCollection = Number(response.currentCollection) || 0;
                        let previousCollection = Number(response.previousCollection) || 0;

                        let totalPurchase = Number(response.currentPurchase) || 0;
                        let previousPurchase = Number(response.previousPurchase) || 0;

                        let totalReceivable = Number(response.totalReceivable) || 0;
                        let previousReceivable = Number(response.previousReceivable) || 0;

                        let totalPayable = Number(response.totalPayable) || 0;
                        let previousPayable = Number(response.previousPayable) || 0;

                        let inventoryValue = Number(response.inventoryValue) || 0;
                        let previousInventoryValue = Number(response.previousInventoryValue) || 0;

                        let totalPayment = Number(response.currentPayment) || 0;
                        let previousPayment = Number(response.previousPayment) || 0;


                        /* Existing cards */
                        $('#total-sales').text(totalSales.toFixed(2));
                        $('#pre-total-sales').text('PRV: ' + previousSales.toFixed(2));

                        $('#total-collections').text(totalCollection.toFixed(2));
                        $('#pre-total-collections').text('PRV: ' + previousCollection.toFixed(2));

                        $('#total-purchases').text(totalPurchase.toFixed(2));
                        $('#pre-total-purchases').text('PRV: ' + previousPurchase.toFixed(2));
                      
                        $('#total-receivables').text(totalReceivable.toFixed(2));
                        $('#pre-total-receivables').text('PRV: ' + previousReceivable.toFixed(2));
                      
                        $('#total-payables').text(totalPayable.toFixed(2));
                        $('#pre-total-payables').text('PRV: ' + previousPayable.toFixed(2));

                        $('#inventory-values').text(inventoryValue.toFixed(2));
                        $('#pre-inventory-values').text('PRV: ' + previousInventoryValue.toFixed(2));

                        $('#total-payments').text(totalPayment.toFixed(2));
                        $('#pre-total-payments').text('PRV: ' + previousPayment.toFixed(2));


                    },

                    error: function (xhr, status, error) {
                        console.error( 'Dashboard summary error:', error );
                    }

                });

            }


            /* Default */
            dailyloadSummary('daily');
            loadSummary('monthly');
           

            /* =====================================================
               SALES / COLLECTION CHART
            ====================================================== */

            const chartElement = document.getElementById( 'salesCollectionChart' );


            if (chartElement) {

                new Chart(chartElement, {

                    type: 'bar',

                    data: {

                        labels: [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [
                            {
                                label: 'Sales',
                                data: [48, 44, 55, 58, 49, 58, 64, 82, 91, 85, 88, 92],
                                backgroundColor: '#167de9',
                                borderRadius: 2,
                                barThickness: 13

                            },

                            {
                                label: 'Collection',
                                type: 'line',
                                data: [ 32, 31, 40, 45,  41, 44, 60, 68, 65, 70, 75, 80],
                                borderColor:  '#119c6c',
                                backgroundColor: '#119c6c',
                                borderWidth: 2,
                                pointRadius: 3,
                                pointBackgroundColor: '#119c6c',
                                tension: .35
                            }

                        ]

                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },

                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    boxHeight: 7,
                                    font: { size: 9 }
                                }

                            },

                            tooltip: {
                                backgroundColor: '#12375e',
                                padding: 10,
                                titleFont: { size: 10 },
                                bodyFont: { size: 10 }

                            }

                        },

                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    font: { size: 8 },
                                    color: '#71869f'
                                }
                            },

                            y: {
                                beginAtZero: true,
                                grid: { color: '#edf2f7' },

                                ticks: {
                                    font: { size: 8 },
                                    color: '#71869f',
                                    callback:
                                        function(value) {
                                            return value + 'L';
                                        }
                                }
                            }
                        }
                    }

                });

            }


            $('#bankToggle').on('click', function () {
                var hiddenBanks = $('.bank-hidden');
                if (hiddenBanks.first().is(':visible')) {
                    hiddenBanks.css('display', 'none');
                    $(this).html('View Details →');

                } else {
                    hiddenBanks.css('display', 'flex');
                    $(this).html('Show Less ↑');

                }

            });
            
        });

    </script>
@endsection