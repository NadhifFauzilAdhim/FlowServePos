<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            font-size: 22px;
            color: #d47311;
            margin-bottom: 5px;
        }

        h2 {
            font-size: 16px;
            color: #555;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #888;
            font-size: 11px;
            margin-bottom: 20px;
        }

        .summary {
            display: flex;
            margin-bottom: 20px;
        }

        .summary-box {
            background: #f9f9f9;
            padding: 12px 18px;
            border-radius: 6px;
            margin-right: 15px;
        }

        .summary-box .label {
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
        }

        .summary-box .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #d47311;
            color: white;
            padding: 8px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background: #f0f0f0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #aaa;
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>☕ Lumina Café</h1>
    <p class="subtitle">Sales Report — {{ ucfirst($period) }} | {{ $from->format('d M Y') }} to
        {{ $to->format('d M Y') }}</p>

    <table>
        <tr>
            <td style="background: #f9f9f9; padding: 15px; width: 50%;">
                <div style="font-size: 10px; color: #888; text-transform: uppercase;">Total Revenue</div>
                <div style="font-size: 20px; font-weight: bold; color: #d47311;">Rp
                    {{ number_format($salesData['total_revenue'], 0, ',', '.') }}</div>
            </td>
            <td style="background: #f9f9f9; padding: 15px; width: 50%;">
                <div style="font-size: 10px; color: #888; text-transform: uppercase;">Total Orders</div>
                <div style="font-size: 20px; font-weight: bold; color: #333;">
                    {{ number_format($salesData['total_orders']) }}</div>
            </td>
        </tr>
    </table>

    <h2>Sales Breakdown</h2>
    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Orders</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < count($salesData['labels']); $i++)
                <tr>
                    <td>{{ $salesData['labels'][$i] }}</td>
                    <td>{{ $salesData['order_count'][$i] }}</td>
                    <td class="text-right">Rp {{ number_format($salesData['revenue'][$i], 0, ',', '.') }}</td>
                </tr>
            @endfor
            <tr class="total-row">
                <td>Total</td>
                <td>{{ number_format($salesData['total_orders']) }}</td>
                <td class="text-right">Rp {{ number_format($salesData['total_revenue'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if ($bestSellers->isNotEmpty())
        <h2>Best Selling Items</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Qty Sold</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bestSellers as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->menu->name }}</td>
                        <td>{{ $item->menu->category->name }}</td>
                        <td>{{ $item->total_qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }} — Lumina Café POS System
    </div>
</body>

</html>
