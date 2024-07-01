<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">
    
    @if ($mailData['userType'] == 'customer')
    <h1>Terimakasih atas orderan anda!</h1>
    <h2>Orderan ID Anda adalah: #{{ $mailData['order']->id }}</h2>
    @else
    <h1>Anda telah menerima pesanan:</h1>
    <h2>Orderan ID: #{{ $mailData['order']->id }}</h2>
    @endif

    <h2 class="h5 mb-3">Alamat Pengiriman</h2>
    <address>
        <strong>{{ $mailData['order']->first_name.' '.$mailData['order']->last_name }}</strong><br>
        {{ $mailData['order']->address }}<br>
        {{ $mailData['order']->city }}, {{ $mailData['order']->zip }} {{ getCountryInfo($mailData['order']->country_id)->name }}<br>
        Phone: {{ $mailData['order']->mobile }}<br>
        Email: {{ $mailData['order']->email }}
    </address>

    <h2>Produk</h2>

    <table cellpadding="3" cellscpacing="3" border="0" width="700">
        <thead>
            <tr style="background: #CCC">
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($mailData['order']->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>Rp.{{ NumberFormat($item->price) }}</td>
                <td>{{ $item->qty }}</td>
                <td>Rp.{{ NumberFormat($item->total) }}</td>
            </tr>
            @endforeach

            <tr>
                <th colspan="3" align="right">Subtotal:</th>
                <td>Rp.{{ NumberFormat($mailData['order']->subtotal) }}</td>
            </tr>

            <tr>
                <th colspan="3" align="right">Discount:{{ (!empty($mailData['order']->coupon_code)) ? '('.$mailData['order']->coupon_code.')' : '' }}</th>
                <td>Rp.{{ NumberFormat($mailData['order']->discount) }}</td>
            </tr>

            <tr>
                <th colspan="3" align="right">Shipping:</th>
                <td>Rp.{{ NumberFormat($mailData['order']->shipping) }}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Grand Total:</th>
                <td>Rp.{{ NumberFormat($mailData['order']->grand_total) }}</td>
            </tr>
        </tbody>
    </table>
    
</body>
</html>