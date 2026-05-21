<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>

    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #000;
        }

        .receipt {
            max-width: 400px;
            margin: auto;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .meta p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 2px 0;
            vertical-align: top;
        }

        th {
            border-bottom: 1px dashed #000;
            text-align: left;
        }

        td.right,
        th.right {
            text-align: right;
        }

        .product-name {
            font-weight: bold;
            padding-top: 8px;
        }

        .child-item {
            padding-left: 12px;
        }

        .note {
            font-size: 11px;
            padding-left: 12px;
            white-space: pre-line;
        }

        .summary {
            margin-top: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            white-space: pre-line;
        }
    </style>
</head>
<body>

@php
    function format_amount($n) {
        return fmod($n, 1) == 0
            ? number_format($n, 0, ',', '.')
            : number_format($n, 2, ',', '.');
    }

    $sales_invoice_details = [];

    foreach ($data['sales_invoice_details'] as $item) {
        if (isset($sales_invoice_details[$item['product_id']])) {
            $sales_invoice_details[$item['product_id']]['child'][] = $item;
        } else {
            $item['child'] = [];
            $sales_invoice_details[$item['product_id']] = $item;
        }
    }
@endphp

<div class="receipt">

    {{-- HEADER --}}
    <div class="center">
        <div class="bold">
            {{ config('general_settings.company_name') }}
        </div>

        <div>
            {{ config('general_settings.company_address') }}
        </div>

        <div>
            {{ config('general_settings.company_phone') }}
        </div>
    </div>

    <div class="line"></div>

    {{-- META --}}
    <div class="meta">
        <p>No : {{ $data['ref_number'] }}</p>
        <p>Kasir : {{ $data['created_by_name'] }}</p>
        <p>Tgl : {{ date('d/m/Y H:i:s', strtotime($data['created_at'])) }}</p>
        <p>Cust : {{ $data['customer_name'] }}</p>
        <p>Alamat : {{ $data['customer_address'] }}</p>
    </div>

    <div class="line"></div>

    {{-- TABLE HEADER --}}
    <table>
        <thead>
            <tr>
                <th>Qty</th>
                <th>Unit</th>
                <th class="right">Harga</th>
                <th class="right">Disc</th>
                <th class="right">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($sales_invoice_details as $item)

                @php
                    $price = floatval($item['unit_price']);
                    $qty = floatval($item['qty']);

                    $disc = $item['discount_amount'] > 0
                        ? ($item['discount_type'] == 'percentage'
                            ? $price * ($item['discount_amount'] / 100)
                            : $item['discount_amount'])
                        : 0;

                    $subtotal = ($price * $qty) - $disc;
                @endphp

                {{-- Product Name --}}
                <tr>
                    <td colspan="5" class="product-name">
                        {{ $item['product_name'] }}
                    </td>
                </tr>

                {{-- Main Row --}}
                <tr>
                    <td>{{ $qty }}</td>
                    <td>{{ $item['unit_name'] }}</td>
                    <td class="right">{{ format_amount($price) }}</td>
                    <td class="right">-{{ format_amount($disc) }}</td>
                    <td class="right">{{ format_amount($subtotal) }}</td>
                </tr>

                @if (!empty($item['note']))
                    <tr>
                        <td colspan="5" class="note">
                            {{ $item['note'] }}
                        </td>
                    </tr>
                @endif

                {{-- CHILD ITEMS --}}
                @foreach ($item['child'] as $child)

                    @php
                        $childPrice = floatval($child['unit_price']);
                        $childQty = floatval($child['qty']);

                        $childDisc = $child['discount_amount'] > 0
                            ? ($child['discount_type'] == 'percentage'
                                ? $childPrice * ($child['discount_amount'] / 100)
                                : $child['discount_amount'])
                            : 0;

                        $childSubtotal = ($childPrice * $childQty) - $childDisc;
                    @endphp

                    <tr>
                        <td class="child-item">{{ $childQty }}</td>
                        <td>{{ $child['unit_name'] }}</td>
                        <td class="right">
                            {{ format_amount($childPrice) }}
                        </td>
                        <td class="right">
                            -{{ format_amount($childDisc) }}
                        </td>
                        <td class="right">
                            {{ format_amount($childSubtotal) }}
                        </td>
                    </tr>

                    @if (!empty($child['note']))
                        <tr>
                            <td colspan="5" class="note">
                                {{ $child['note'] }}
                            </td>
                        </tr>
                    @endif

                @endforeach

            @endforeach
        </tbody>
    </table>

    <div class="line"></div>

    {{-- SUMMARY --}}
    <div class="summary">
        <div class="summary-row">
            <span>Subtotal</span>
            <span>{{ format_amount($data['subtotal']) }}</span>
        </div>

        <div class="summary-row">
            <span>Diskon</span>
            <span>-{{ format_amount($data['discount_amount']) }}</span>
        </div>

        <div class="summary-row bold">
            <span>Total</span>
            <span>{{ format_amount($data['total']) }}</span>
        </div>

        <div class="summary-row">
            <span>Bayar</span>
            <span>{{ format_amount($data['total_payment']) }}</span>
        </div>

        <div class="summary-row">
            <span>Kembali</span>
            <span>{{ format_amount($data['total_change']) }}</span>
        </div>
    </div>

    <div class="line"></div>

    {{-- FOOTER --}}
    <div class="footer">
        {{ config('general_settings.pos_receipt_footer_text') }}
    </div>

</div>

</body>
</html>