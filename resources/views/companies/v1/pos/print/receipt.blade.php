<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Struk - {{ $data['ref_number'] }}</title>
        <style>
            @php
                $paper = isset($paper_size) ? (int) $paper_size : (int) config('local_user_settings.pos_printer_paper_size', 80);
                if (!in_array($paper, [58, 75, 80])) {
                    $paper = 80;
                }
            @endphp

            * { margin: 0; padding: 0; box-sizing: border-box; }

            body {
                font-family: Consolas, "Courier New", monospace;
                background: #fff;
                color: #000;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .receipt {
                font-weight: bold;
            }

            .paper-58 .receipt { width: 58mm; font-size: 10.5px; }
            .paper-75 .receipt { width: 75mm; font-size: 11.5px; }
            .paper-80 .receipt { width: 80mm; font-size: 12.5px; }

            .receipt {
                margin: 0 auto;
                padding: 1mm 1mm;
                line-height: 1.4;
                letter-spacing: 0.2px;
            }

            .text-center { text-align: center; display: block; width: 100%; }
            .text-left   { text-align: left; }
            .text-right  { text-align: right; }
            .bold        { font-weight: bold; }
            .muted       { color: #444; }

            .line {
                display: block;
                border-top: 1px solid #000;
                width: 100%;
                margin: 1.5mm 0;
            }

            .store-name   { font-size: 1.2em; }
            .store-meta   { font-size: 1em; }

            .meta-table { width: 100%; border-collapse: collapse; }
            .meta-table td { vertical-align: top; padding: 0; }
            .meta-table td.k { white-space: nowrap; padding-right: 2mm; }
            .meta-table td.v { white-space: normal; word-break: break-word; }

            .items-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }
            .items-table td {
                padding: 0.25mm 0;
                vertical-align: top;
            }
            .items-table thead td {
                padding-top: 0;
                border-bottom: 1px solid #000;
            }
            .items-table .c-qty   { text-align: left; }
            .items-table .c-unit  { text-align: left; white-space: nowrap; }
            .items-table .c-price,
            .items-table .c-disc,
            .items-table .c-total { text-align: right; white-space: nowrap; }
            .items-table .c-name  {}
            .items-table .c-qty,
            .items-table .c-price,
            .items-table .c-disc,
            .items-table .c-total { font-weight: bold; }
            .items-table .child td { color: #555; }
            .items-table .note td  { color: #000; padding-left: 3mm; font-weight: lighter; }

            .paper-58 .c-qty   { width: 3ch; }
            .paper-58 .c-unit  { width: 3ch; }
            .paper-58 .c-price { width: 7ch; }
            .paper-58 .c-disc  { width: 5ch; }
            .paper-58 .c-total { width: 10ch; }

            .paper-75 .c-qty   { width: 4ch; }
            .paper-75 .c-unit  { width: 4ch; }
            .paper-75 .c-price { width: 9ch; }
            .paper-75 .c-disc  { width: 6ch; }
            .paper-75 .c-total { width: 11ch; }

            .paper-80 .c-qty   { width: 4ch; }
            .paper-80 .c-unit  { width: 4ch; }
            .paper-80 .c-price { width: 9ch; }
            .paper-80 .c-disc  { width: 7ch; }
            .paper-80 .c-total { width: 11ch; }

            .summary-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }
            .summary-table td { padding: 0.25mm 0; vertical-align: top; }
            .summary-table .s-label { text-align: left; }
            .summary-table .s-value { text-align: right; white-space: nowrap; font-weight: bold; }
            .summary-table .row-total { font-size: 1.15em; }
            .summary-table .row-total .s-label { font-weight: bold; }

            .footer-text { padding-top: 2mm; }

            @media screen {
                body { padding: 10px; background: #e8e8e8; }
                .receipt {
                    background: #fff;
                    border: 1px solid #ccc;
                    box-shadow: 0 1px 4px rgba(0,0,0,.2);
                    max-width: {{ $paper }}mm;
                }
            }

            @media print {
                @page {
                    size: {{ $paper }}mm auto;
                    margin: 0;
                }
                body {
                    width: {{ $paper }}mm;
                    background: #fff;
                    padding: 0;
                }
                .receipt {
                    border: 0;
                    box-shadow: none;
                    max-width: none;
                }
            }
        </style>
    </head>
    <body class="paper-{{ $paper }}">
        <div class="receipt">
        
            @php
                if (!function_exists('rcpt_fmt')) {
                    function rcpt_fmt($n) {
                        return fmod($n, 1) == 0
                            ? number_format($n, 0, '.', ',')
                            : number_format($n, 2, '.', ',');
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
            
            <span class="text-center bold store-name">{{ config('general_settings.company_name') }}</span>
            <span class="text-center store-meta">{{ config('general_settings.company_address') }}</span>
            <span class="text-center store-meta">{{ config('general_settings.company_phone') }}</span>
            
            <span class="line"></span>
            
            <table class="meta-table">
                <tr><td class="k">No</td><td class="v">: {{ $data['ref_number'] }}</td></tr>
                <tr><td class="k">Kasir</td><td class="v">: {{ $data['created_by_name'] }}</td></tr>
                <tr><td class="k">Tgl</td><td class="v">: {{ date('d/m/Y H:i:s', strtotime($data['created_at'])) }}</td></tr>
                <tr><td class="k">Cust</td><td class="v">: {{ $data['customer_name'] }}</td></tr>
                <tr><td class="k">Alamat</td><td class="v">: {{ $data['customer_address'] }}</td></tr>
            </table>
        
            <span class="line"></span>
        
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
                            <td class="c-disc">{{ $disc > 0 ? '-'.rcpt_fmt($disc) : '-' }}</td>
                            <td class="c-total">{{ rcpt_fmt($subtotal) }}</td>
                        </tr>
                    
                        @if (!empty($item['note']))
                        <tr class="note"><td colspan="5">{{ $item['note'] }}</td></tr>
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
                                <td class="c-disc">{{ $cDisc > 0 ? '-'.rcpt_fmt($cDisc) : '-' }}</td>
                                <td class="c-total">{{ rcpt_fmt($cSubtotal) }}</td>
                            </tr>
                            @if (!empty($child['note']))
                            <tr class="note"><td colspan="5">{{ $child['note'] }}</td></tr>
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
                <tr class="row-total">
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
            <span class="text-center footer-text">{{ config('general_settings.pos_receipt_footer_text') }}</span>
        
        </div>
        <script>
          window.onload = function() {
              window.print();
        
              window.onafterprint = function () {
                //   window.close();
              };
          }
        </script>
    </body>
</html>