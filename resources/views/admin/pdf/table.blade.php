<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            direction: rtl; 
            margin: 20px; 
            font-size: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #4c4cd8;
            padding-bottom: 20px;
        }
        .header h1 { color: #4c4cd8; margin: 5px 0; font-size: 20px; }
        .header h2 { color: #333; margin: 5px 0; font-size: 18px; }
        .header h3 { color: #666; margin: 5px 0; font-size: 16px; }
        .header p { color: #888; margin: 5px 0; font-size: 14px; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: center; 
            font-size: 10px;
        }
        th { 
            background-color: #f8f9fa; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('storage/school_logos/taleblogo.png') }}" alt="Logo" style="width: 150px; margin-bottom: 20px;" />
        <h2>منصة طالب</h2>
        <p>تاريخ التقرير: {{ now()->format('Y/m/d') }}</p>
    </div>
    
    @if(count($headers) > 0 && count($rows) > 0)
    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>