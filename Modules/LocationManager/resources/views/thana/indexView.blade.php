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
                    <h2>Customer List</h2>
                </section>

                <table style="width:100%" class="custom-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 8%">Sl</th>
                            <th class="text-center">Division</th>
                            <th class="text-center">District</th>
                            <th class="text-center">Thana Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @csrf
                        @foreach ($thanas as $value)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center">{{ optional(optional($value->parent)->parent)->name }}</td>
                                <td class="text-center">{{ optional($value->parent)->name }}</td>
                                <td class="text-center">{{ $value->name }}</td>
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
