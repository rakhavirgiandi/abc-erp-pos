<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk - {{ $data['ref_number'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9px;
            background: #fff;
            color: #000;
            width: 100%;
        }

        .receipt {
            width: 100%;
            padding: 4px;
        }

        .line {
            display: block;
            border-top: 1px dashed #000;
            width: 100%;
            margin: 2px 0;
        }

        .text-center { text-align: center; display: block; width: 100%; }
        .bold { font-weight: bold; }

        /* Meta */
        .meta-table {
            width: auto;
            border-collapse: collapse;
        }
        .meta-table td { vertical-align: top; white-space: nowrap; padding: 0; }
        .meta-table td:first-child { padding-right: 4px; }

        /* Items */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .items-table td {
            padding: 1px 2px;
            overflow: hidden;
        }
        .items-table .c-qty   { width: 10%; text-align: left; }
        .items-table .c-unit  { width: 10%; text-align: left; }
        .items-table .c-name  { text-align: left; }
        .items-table .c-price { width: 32%; text-align: right; }
        .items-table .c-disc  { width: 15%; text-align: right; }
        .items-table .c-total { width: 33%; text-align: right; }
        .items-table .child td { color: #555; }
        .items-table .note td  { font-size: 9px; font-style: italic; padding-left: 10px; }

        /* Summary */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .summary-table td { padding: 1px 2px; white-space: nowrap; overflow: hidden; }
        .summary-table .s-label { text-align: left; }
        .summary-table .s-value { width: 80px; text-align: right; }
        .summary-table .bold td { font-weight: bold; }

        @media print {
            @page { margin: 0; size: auto; }
            body { width: 100%; }
        }
    </style>
</head>
<body>
<div class="receipt">

    @php
        if (!function_exists('rcpt_fmt')) {
            function rcpt_fmt($n) {
                return fmod($n, 1) == 0
                    ? number_format($n, 0, ',', '.')
                    : number_format($n, 2, ',', '.');
            }
        }

        $groupedItems = [];
        foreach ($data['sales_invoice_details'] as $item) {
            if (isset($groupedItems[$item['product_id']])) {
                $groupedItems[$item['product_id']]['child'][] = $item;
            } else {
                $item['child'] = [];
                $groupedItems[$item['product_id']] = $item;
            }
        }
    @endphp

    {{-- Header --}}
    <span class="text-center bold">{{ config('general_settings.company_name') }}</span>
    <span class="text-center">{{ config('general_settings.company_address') }}</span>
    <span class="text-center">{{ config('general_settings.company_phone') }}</span>

    <span class="line" style="margin: .5rem 0;"></span>

    {{-- Meta --}}
    <table class="meta-table">
        <tr><td>No</td><td>: {{ $data['ref_number'] }}</td></tr>
        <tr><td>Kasir</td><td>: {{ $data['created_by_name'] }}</td></tr>
        <tr><td>Tgl</td><td>: {{ date('d/m/Y H:i:s', strtotime($data['created_at'])) }}</td></tr>
        <tr><td>Cust</td><td>: {{ $data['customer_name'] }}</td></tr>
        <tr><td>Alamat</td><td>: {{ $data['customer_address'] }}</td></tr>
    </table>

    <span class="line" style="margin: .5rem 0 .25rem  0;"></span>

    {{-- Items --}}
    <table class="items-table">
        <thead>
            <tr>
                <td class="c-qty bold">Qty</td>
                <td class="c-unit bold">Unit</td>
                <td class="c-price bold">Harga</td>
                <td class="c-disc bold">Disc</td>
                <td class="c-total bold">Total</td>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="5"><span class="line"></span></td></tr>

            @foreach ($groupedItems as $item)
                @php
                    $price    = floatval($item['unit_price']);
                    $qty      = floatval($item['qty']);
                    $disc     = $item['discount_amount'] > 0
                                    ? ($item['discount_type'] == 'percentage'
                                        ? $price * ($item['discount_amount'] / 100)
                                        : floatval($item['discount_amount']))
                                    : 0;
                    $subtotal = ($price * $qty) - $disc;
                @endphp
                <tr><td class="c-name bold" colspan="5">{{ $item['product_name'] }}</td></tr>
                <tr>
                    <td class="c-qty">{{ rcpt_fmt($qty) }}</td>
                    <td class="c-unit">{{ $item['unit_name'] }}</td>
                    <td class="c-price">{{ rcpt_fmt($price) }}</td>
                    <td class="c-disc">{{ $disc > 0 ? '-'.rcpt_fmt($disc) : '-0' }}</td>
                    <td class="c-total">{{ rcpt_fmt($subtotal) }}</td>
                </tr>

                @if (!empty($item['note']))
                <tr class="note"><td colspan="6">{{ $item['note'] }}</td></tr>
                @endif

                @foreach ($item['child'] as $child)
                    @php
                        $cPrice    = floatval($child['unit_price']);
                        $cQty      = floatval($child['qty']);
                        $cDisc     = $child['discount_amount'] > 0
                                        ? ($child['discount_type'] == 'percentage'
                                            ? $cPrice * ($child['discount_amount'] / 100)
                                            : floatval($child['discount_amount']))
                                        : 0;
                        $cSubtotal = ($cPrice * $cQty) - $cDisc;
                    @endphp
                    <tr class="child">
                        <td class="c-qty">{{ rcpt_fmt($cQty) }}</td>
                        <td class="c-unit">{{ $child['unit_name'] }}</td>
                        <td class="c-price">{{ rcpt_fmt($cPrice) }}</td>
                        <td class="c-disc">{{ $cDisc > 0 ? '-'.rcpt_fmt($cDisc) : '-0' }}</td>
                        <td class="c-total">{{ rcpt_fmt($cSubtotal) }}</td>
                    </tr>
                    @if (!empty($child['note']))
                    <tr class="note"><td colspan="6">{{ $child['note'] }}</td></tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>

    <span class="line"></span>

    {{-- Summary --}}
    <table class="summary-table">
        <tr>
            <td class="s-label">Subtotal</td>
            <td class="s-value">{{ rcpt_fmt($data['subtotal']) }}</td>
        </tr>
        <tr>
            <td class="s-label">Diskon</td>
            <td class="s-value">-{{ rcpt_fmt($data['discount_amount']) }}</td>
        </tr>
        <tr class="bold">
            <td class="s-label">Total</td>
            <td class="s-value">{{ rcpt_fmt($data['total']) }}</td>
        </tr>
        <tr>
            <td class="s-label">Bayar</td>
            <td class="s-value">{{ rcpt_fmt($data['total_payment']) }}</td>
        </tr>
        <tr>
            <td class="s-label">Kembali</td>
            <td class="s-value">{{ rcpt_fmt($data['total_change']) }}</td>
        </tr>
    </table>

    <span class="line"></span>

    {{-- Footer --}}
    <span class="text-center" style="margin-top: .5rem">{{ config('general_settings.pos_receipt_footer_text') }}</span>

</div>
<script>
  window.onload = function() {
      window.print();
  }
</script>
</body>
</html>