
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        font-size: 12px;
    }
    @page {
        margin-top: 110px;
        margin-bottom: 80px;
        margin-left: 40px;
        margin-right: 40px;
    }
    header {
        position: fixed;
        top: -110px;
        left: -40px;
        right: -40px;
        height: 110px;
        background-color: #fff;
        text-align: center;
        line-height: 1.4;
    }
    footer {
        position: fixed;
        bottom: -80px;
        left: -40px;
        right: -40px;
        height: 80px;
        background-color: #fff;
        text-align: center;
        line-height: 1.3;
        border-top: 1px solid #ccc;
    }
    .content {
        margin-top: 10px;
        margin-bottom: 20px;
        line-height: 1.5;
    }
</style>

<style>
    .header {
        width: 100%;
        margin-bottom: 10px;
        position: relative;
        overflow: hidden;
        margin-top: 20px;
    }

    .header-skew {
        width: 100%;
        transform: skewX(35deg);
        position: absolute;
        top: 0;
        left: 0;
        z-index: -99;
    }
    .header-skew {
        position: absolute;
        top: 5px;
        left: 0;
        transform: skewX(33deg);
    }

    .blue-left {
        width: 17%;
        height: 55px;
        border-left: 1px solid white !important;
        border-bottom: 1px solid white !important;
        border-right: 4px solid rgb(0, 0, 179);
        border-top: 4px solid rgb(0, 0, 179);
    }

    .blue-bottom {
        width: 83%;
        height: 55px;
        border-right: 1px solid white !important;
        border-top: 1px solid white !important;
        border-left: 4px solid rgb(0, 0, 179);
        border-bottom: 4px solid rgb(0, 0, 179);
    }
    

    .com-logo img {
        max-width: 75px;
        max-height: 75px;
        margin-left: 15px;
    }

    .com-info {
        text-align: center;
        padding-left: 25px;
    }

    .com h1 {
        margin: 0;
        font-size: 29px;
    }

    .com p {
        color: rgb(226, 35, 35);
        margin: 5px 0 0 5px;
        font-size: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
    }

    td {
        vertical-align: top;
    }

    .qr-code {
        text-align: right;
        vertical-align: top;
        padding-right: 15px;
    }

    .qr-code img {
        width: 70px;
        height: 70px;
    }

    .contact-info, .terms, .signature {
        margin: 20px 0;
    }
    .office-details {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
    }
    .office {
        width: 45%;
    }
    p {
        margin: 10px 0;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    .terms-table {
        width: 100%;
        margin: 20px 0;
        border: none;
    }
    .terms-table th, .terms-table td {
        padding: 10px 0;
        border: none;
    }
    .terms h3 {
        margin: 20px 0 10px;
    }
    .terms p {
        margin: 10px 0 20px;
    }
    h1 {
        font-size: 45px;
    }
    .signature {
        max-height: 100px;
    }
</style>

<div class="header">
    <table class="content-table" style="border: none;">
        <tr>
            @php
                $company_info = DB::table('company_infos')->first();
            @endphp

            <td class="com-logo" style="border: none;">
                <img src="{{ s3FileToBase64($company_info->company_logo) }}" alt="{{ $company_info->company_logo }}">
            </td>

            <td class="com-info" style="border: none; padding-top: 20px; text-align: center;" @if(!isset($qrCode) || $qrCode === false) colspan="2" @endif>
                <div class="com">
                    <div style="line-height: 0.8;">
                        <h1 style="color: rgb(13, 13, 92); font-size: 21px!important;">
                            {{ $company_info->company_name }}
                        </h1>
                        <p style="font-size: 8px!important; color:black;">{{ $company_info->company_bio }}</p>
                        <p style="font-size: 8px!important; color:black;">{{ $company_info->company_address }}</p>
                        <p style="font-size: 8px!important; color:black;">Hotline : {{ $company_info->company_phone }}</p>
                        <p style="font-size: 8px!important; color:black;">
                            e-mail : {{ $company_info->company_email }} &nbsp; web: {{ $company_info->website }}
                        </p>
                    </div>
                </div>
            </td>

            @if(isset($qrCode) && $qrCode === true)
                <td class="qr-code" style="border: none;">
                    <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate($qrCodeUrl)) }}" alt="QR Code">
                </td>
            @endif
        </tr>
    </table>
</div>
