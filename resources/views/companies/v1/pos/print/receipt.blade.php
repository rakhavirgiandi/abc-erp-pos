<?php
  function format_amount($n) {
    return fmod($n, 1) == 0
        ? number_format($n, 0, '.', ',')
        : number_format($n, 2, '.', ',');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['number'] }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">    
    @if ($paper_size == 80)
        <style>
          @page {
  size: 80mm auto;
  margin: 0;
}

body {
  margin: 0;
  background: #fff;
  font-family: 'Roboto', sans-serif;
}

.receipt {
  width: 67mm;
  /* margin: 0 auto; */
  font-size: 11px;
  color: #000;
}

.center { text-align: center; }

.small { font-size: 10px; }

.store-name {
  font-weight: bold;
  font-size: 14px;
}

.divider {
  border-top: 1px dashed #000;
  margin: 6px 0;
}

.meta div {
  display: flex;
  justify-content: space-between;
  font-size: 10px;
}

table {
  width: 100%;
  border-collapse: collapse;
}

.items th {
  text-align: left;
  border-bottom: 1px dashed #000;
  padding-bottom: 3px;
  font-size: 10px;
}

.items td {
  font-size: 10px;
  padding: 2px 0;
}

.items .name-row td {
  font-weight: 500;
  padding-top: 5px;
}

.items .description-row td {
  font-size: 9px;
  padding-bottom: 3px;
}

.items tr:last-child td {
  border-bottom: 1px dashed #000;
  padding-bottom: 6px;
}

.right { text-align: right; }
.center-text { text-align: center; }

.summary td {
  padding: 3px 0;
  font-size: 11px;
}

.summary .price {
  text-align: right;
}

.grand td {
  font-weight: bold;
  font-size: 13px;
}
        </style>
    @else
    <style>
        @page {
          size: 58mm auto;
          margin: 0;
        }

        body {
          margin: 0;
          background: #fff;
          font-family: 'Roboto', sans-serif;
        }

        .receipt {
          width: 46.5mm;
          font-size: 9px;
          color: #000;
        }

        .center { text-align: center; }

        .small { 
          font-size: 8px; 
        }

        .store-name {
          font-weight: bold;
          font-size: 11px;
          margin-bottom: 1px;
        }

        .divider {
          border-top: 1px dashed #000;
          margin: 4px 0;
        }

        .meta div {
          display: flex;
          justify-content: space-between;
          font-size: 8px;
        }

        table {
          width: 100%;
          border-collapse: collapse;
        }

        .items {
          margin-bottom: 4px;
        }

        .items th {
          text-align: left;
          border-bottom: 1px dashed #000;
          padding-bottom: 2px;
          font-size: 9px;
        }

        .items td {
          vertical-align: top;
          font-size: 9px;
        }

        .items tr:last-child td {
          border-bottom: 1px dashed #000;
          padding-bottom: 4px;
        }

        .items .name-row td {
          padding-top: 2px;
        }

        .items tr:first-child td {
          padding-top: 4px;
        }

        .items .description-row td {
          padding-bottom: 2px;
        }

        .items .item-detail-row td {
          padding: 1px 0;
        }

        .summary td {
          padding: 1px 0;
          font-size: 9px;
        }

        .summary .price {
          text-align: right;
        }

        .grand td {
          padding: 3px 0;
          font-weight: bold;
          font-size: 10px;
        }

        .description-row td {
          font-size: 7px;
        }
        
    </style>
    @endif
</head>
<body>
  <div class="receipt">
    <div class="center">
      <div class="store-name">{{ config('settings.company_name') }}</div>
      <div style="margin-bottom: 2px">{{ config('settings.company_address') }}</div>
      <div>{{ config('settings.company_phone') }}</div>
    </div>

    <div class="divider"></div>

    <div class="meta" style="margin-bottom: 1rem">
      <div>No: {{ $data['ref_number'] }}</div>
      <div>Kasir: {{ $data['created_by_name'] }}</div>
      <div>Tgl: {{ \Carbon\Carbon::parse($data['created_at'])->format('d/m/Y H:i:s') }}</div>
      <div>Pelanggan: {{ $data['customer_name'] }}</div>
    </div>

    @php

      $sales_invoice_details = [];
      $child_idx = 0;

      foreach ($data['sales_invoice_details'] as $row => $item) {
        if (isset($sales_invoice_details[$item['product_id']]) && $sales_invoice_details[$item['product_id']]) {
          $sales_invoice_details[$item['product_id']]['child'][$child_idx] = $item;
          $child_idx++;
        } else {
          $sales_invoice_details[$item['product_id']] = $item;  
          $sales_invoice_details[$item['product_id']]['child'] = [];  
        }
      }

    @endphp

    <table class="items">
      <thead>
        <tr>
          <th class="qty" style="text-align: center">Qty</th>
          <th class="unit" style="text-align: center">Unit</th>
          <th class="price" style="text-align: center">Harga</th>
          <th class="price" style="text-align: center">Diskon</th>
          <th class="price" style="text-align: right">Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($sales_invoice_details as $idx => $item)
          @php
            $price = floatval($item['unit_price']);
            $discount_value = floatval($item['discount_amount']);
            $qty = floatval($item['qty']);
            $disc = 0;

            if ($discount_value > 0) {
              if ($item['discount_type'] == 'percentage') {
                  $disc = $price * ($discount_value / 100);
                } else {
                  $disc = $discount_value;
              }
            }

            $subtotal_item = ($price * $qty) - $disc;
          @endphp
          <tr class="name-row">
            <td colspan="6">{{ $item['product_name'] }}</td>
          </tr>
          <tr class="item-detail-row">
            <td style="text-align: center">{{ $qty }}</td>
            <td style="text-align: center">{{ $item['unit_name'] }}</td>
            <td style="text-align: center">{{ format_amount($price) }}</td>
            <td style="text-align: center">-{{ format_amount($disc) }}</td>
            <td style="text-align: right">{{ format_amount($subtotal_item) }}</td>
          </tr>
          @if ($item['note'])
          <tr class="description-row">
            <td></td>
            <td colspan="5">{{ $item['note'] }}</td>
          </tr>
          @endif
          @if (count($item['child']) > 0)
            @foreach ($item['child'] as $item_child)
              @php
                $price = floatval($item_child['unit_price']);
                $discount_value = floatval($item_child['discount_amount']);
                $qty = floatval($item_child['qty']);
                $disc = 0;

                if ($discount_value > 0) {
                  if ($item['discount_type'] == 'percentage') {
                      $disc = $price * ($discount_value / 100);
                    } else {
                      $disc = $discount_value;
                  }
                }
              
                $subtotal_item = ($price * $qty) - $disc;
              @endphp
              <tr class="item-detail-row">
                <td style="text-align: center">{{ $qty }}</td>
                <td style="text-align: center">{{ $item_child['unit_name'] }}</td>
                <td style="text-align: center">{{ format_amount($price) }}</td>
                <td style="text-align: center">-{{ format_amount($disc) }}</td>
                <td style="text-align: right">{{ format_amount($subtotal_item) }}</td>
              </tr>
              @if ($item_child['note'])
              <tr class="description-row">
                <td></td>
                <td colspan="5">{{ $item_child['note'] }}</td>
              </tr>
              @endif
            @endforeach
          @endif
        @endforeach
      </tbody>
    </table>

    @php
      $subtotal = floatval($data['subtotal']);
      $discount_amount = floatval($data['discount_amount']);
      $total = floatval($data['total']);
      $total_payment = floatval($data['total_payment']);
      $total_change = floatval($data['total_change']);
    @endphp

    <table class="summary">
      <tr>
        <td>Subtotal</td>
        <td class="price">{{ format_amount($subtotal) }}</td>
      </tr>
      <tr>
        <td>Diskon</td>
        <td class="price">-{{ format_amount($discount_amount) }}</td>
      </tr>
      <tr class="grand">
        <td>Total</td>
        <td class="price">{{ format_amount($total) }}</td>
      </tr>
      <tr>
        <td>Bayar</td>
        <td class="price">{{ format_amount($total_payment) }}</td>
      </tr>
      <tr>
        <td>Kembali</td>
        <td class="price">{{ format_amount($total_change) }}</td>
      </tr>
    </table>

    <div class="divider"></div>

    <div class="center small">
      {{ config('settings.pos_receipt_footer_text') }}
    </div>

  </div>
</body>
</html>