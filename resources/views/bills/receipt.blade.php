{{-- resources/views/bills/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $bill->id }}</title>
    <style>
        /* Base layout tuned for 58mm thermal printers */
        html, body {
            padding: 0;
            margin: 0;
            background: #fff;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 12px; /* Monospace for alignment */
            color: #000;
        }
        .receipt {
            width: 220px; /* ~58mm visual width in many browsers */
            margin: 0 auto;
            padding: 8px 8px 12px;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .muted { color: #222; }
        .logo {
            width: 80px;
            height: auto;
            margin: 0 auto 4px;
            display: block;
        }
        .title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 6px;
        }
        .hr {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
        }
        .items th,
        .items td {
            padding: 2px 0;
            vertical-align: top;
            word-break: break-word;
        }
        .items thead th {
            border-bottom: 1px dashed #000;
            font-weight: 700;
        }
        .items tfoot td {
            border-top: 1px dashed #000;
            padding-top: 4px;
        }
        .line {
            display: flex;
            justify-content: space-between;
            gap: 8px;
        }
        .small { font-size: 11px; }
        .staffs { color: #111; }
        .footer { margin-top: 8px; }

        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }
            body { margin: 0; }
            .receipt { width: 58mm; margin: 0; padding: 6px; }
        }
    </style>
</head>
<body>
    @php
        $salon = $bill->salon ?? null;
        $logoUrl = data_get($salon, 'logo_url');
        $customerName = $bill->customer_name ?? '-';
        $billDate = optional($bill->created_at)->format('d M Y, H:i');
        $items = $bill->items ?? collect();
        $calcTotal = $items->sum(function ($i) { return ($i->quantity ?? 1) * ($i->price ?? 0); });
        $paidAmount = $bill->paid_amount ?? optional($bill->payments)->sum('amount') ?? 0;
        $dueAmount = max(0, $calcTotal - $paidAmount);
    @endphp

    <div class="receipt">
        {{-- Header: Logo + Salon Name --}}
        <div class="center">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo" class="logo">
            @endif
            <div class="title">{{ data_get($salon, 'name', config('app.name')) }}</div>
        </div>

        <div class="hr"></div>

        {{-- Bill meta --}}
        <div class="meta small">
            <div class="meta-row"><span>Bill ID</span><span class="right">#{{ $bill->id }}</span></div>
            <div class="meta-row"><span>Date</span><span class="right">{{ $billDate }}</span></div>
            <div class="meta-row"><span>Customer</span><span class="right">{{ $customerName }}</span></div>
        </div>

        <div class="hr"></div>

        {{-- Items --}}
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 48%">Item</th>
                    <th class="right" style="width: 12%">Qty</th>
                    <th class="right" style="width: 20%">Price</th>
                    <th class="right" style="width: 20%">Subt</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    @php
                        $serviceName = data_get($item->service, 'name');
                        $displayName = $serviceName ?: 'Deleted Service';
                        $qty = $item->quantity ?? 1;
                        $unitPrice = $item->price ?? 0;
                        $subtotal = $qty * $unitPrice;
                        $staffNames = optional($item->staffs)->pluck('name')->filter()->values()->all();
                    @endphp
                    <tr>
                        <td>
                            {{ $displayName }}
                            @if(!empty($staffNames))
                                <div class="staffs small">[{{ implode(', ', $staffNames) }}]</div>
                            @endif
                        </td>
                        <td class="right">{{ $qty }}</td>
                        <td class="right">{{ number_format($unitPrice, 2) }}</td>
                        <td class="right">{{ number_format($subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td class="right"><strong>Total</strong></td>
                    <td class="right"><strong>{{ number_format($calcTotal, 2) }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="right">Paid</td>
                    <td class="right">{{ number_format($paidAmount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="right">Due</td>
                    <td class="right">{{ number_format($dueAmount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="hr"></div>

        <div class="footer center small">
            Thank you for visiting!
        </div>
    </div>

    <script>
        window.addEventListener('load', function () {
            try { window.print(); } catch (e) {}
        });
    </script>
</body>
</html>


