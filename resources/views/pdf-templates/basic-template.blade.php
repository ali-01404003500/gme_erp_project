<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .subtitle {

            font-size: 14px;
            margin-bottom: 8px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            white-space: nowrap;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 8px;
            text-decoration: underline;
        }
    </style>
    <style>
                        .header {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    text-align: center;
                }

                .header img {
                    max-width: 100px;
                    margin-right: 20px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 50px;
                    font-weight: bold;
                    color: rgb(0, 0, 187);
                }

                .header p {
                    margin: 5px 0;
                    font-size: 12px;
                }

    </style>
</head>
<body>

          <header class="my-header">
                            @include('partials._for_pdf_header_2nd')
                        </header>
    {{-- @include('partials._for_pdf_header') --}}
    <main>
        <div class="title">{{ $title }}</div>

        @if (!empty($subtitles))
            @if (is_array($subtitles))
                @foreach ($subtitles as $subtitle)
                    <div  class="subtitle">{{ $subtitle }}</div>
                @endforeach
            @else
                <div  class="subtitle">{{ $subtitles }}</div>
            @endif
        @endif
    
        <div class="table-content">
            @if (isset($table))
                {!! $table !!}
            @endif
            @stack('table')
        </div>
    </main>

    <div style="position: absolute; bottom: -0.375in; left: -0.5in; width: calc(100% + 1in); text-align: center;">
        @include('partials._for_pdf_footer')
    </div>
</body>
</html>
