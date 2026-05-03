<style>
    .pdf-header-table {
        width: 100%;
        border-collapse: collapse;
        border-bottom: 1px solid #3b5998;
    }
 
    .pdf-title {
        font-family: "Times New Roman", serif;
        font-size: 34px;
        font-weight: 900;
        color: #1a3a5f;
        margin: 0;
        line-height: 1.2;
    }

    .pdf-subtitle {
        font-size: 12px;
        color: #ff8300;
        margin: 0;
        line-height: 1.2;
    }


 
 
</style>

<div class="header-container"> 
    <table class="pdf-header-table" style="width:100%; border-collapse:collapse; border:none; border-bottom: 2px solid #3b5998; "   width: 100%;  cellspacing="0" cellpadding="0">
        <tr>

            <td style="background:#2f5597;  vertical-align:middle; border:none;padding-left:0.4in;"  > </td>

            {{-- LOGO --}}  
         
            @php
           
                $default_company_logo = 'assets/img/gme-logo.png';
            @endphp

            <td class="logo-box" style="width:15%; background:#ffffff;  vertical-align:middle; border:none;">
                <img src="{{ s3FileToBase64($company_info->company_logo) ?? $default_company_logo }}" alt="GME">
            </td>

            {{-- TEXT --}}
            <td style="width:85%;background:#ffffff;  vertical-align:middle; border:none;">
                <h1 class="pdf-title" style="margin:0; font-weight:bold;">
                    Global Medical Engineering (BD) Ltd.
                </h1>

                <p class="pdf-subtitle" style="margin:3px 0 0 0;">
                    Provider of Medical Equipment and Solutions for Hospitals, Diagnostics, Clinics and Healthcare Institutes.
                </p>
            </td>
        </tr>
    </table>

</div>