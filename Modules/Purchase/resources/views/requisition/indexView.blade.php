<style>
    @import url('https://fonts.maateen.me/kalpurush/font.css');

    .my-header {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }


    .my-header img {
        max-width: 100px;
        margin-right: 20px;
    }

    .my-header h1 {
        margin: 0;
        font-size: 50px;
        font-weight: bold;
        color: rgb(0, 0, 187);
    }

    .my-header p {
        margin: 5px 0;
        font-size: 12px;
    }

    .title {
        text-align: center;
        margin-bottom: 20px;
    }

    .title h2 {
        margin: 0;
        font-size: 20px;
        text-decoration: underline;
    }

    footer {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    footer p {
        margin: 10px 0;
        font-size: 14px;
        width: 45%;
        text-align: center;
    }
    .custom-table, .custom-table td, .custom-table th, .custom-table tr {
        padding: 2px;
        margin: 2px;
        border:none;
        border-bottom: 1px solid #000000;
        border-right: none;
        border-left: none;
        
    }
</style>

<div class="row" style="font-size: 12px!important;">
    <div class="col-md-12 m-2">
        <x-error-alart />
    </div>
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                

                <header class="my-header">
                    @include('partials._for_pdf_header')
                </header>

                <section class="title">
                    <h2>Purchase Requisition List</h2>
                </section>

                <table style="width:100%" class="custom-table">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Requisition Id</th>
                            <th>Requisition Date</th>
                            <th>Suplier</th>
                            <th>Customer</th>
                            <th>Invoice To</th>
                            <th>Status</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($requisitions as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->requisition_no }}</td>
                                <td>{{ $value->invoice_date }}</td>
                                <td>{{ optional( $value->supplier)->company_name }}</td>
                                <td>{{ optional($value->customer)->company_name }}</td>
                                <td>{{ optional($value->warehouse)->name }}</td>
                                <td>
                                    @if ($value->status == 0)
                                        <span class="badge badge-round badge-warning">Pending</span>
                                    @elseif($value->status == 1)
                                        <span class="badge badge-round badge-success">Approved</span>
                                    @elseif($value->status == 4)
                                        <span class="badge badge-round badge-primary">Received</span>
                                    @elseif($value->status == 2)
                                        <span class="badge badge-round badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ optional($value->createdBy)->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <footer style="margin-top: 100px">
                    @include('partials._for_pdf_footer')
                </footer>
            </div>
        </div>
    </div>
</div>
