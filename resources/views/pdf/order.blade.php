<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Lato', DejaVu Sans, sans-serif;
    font-size: 13px;
    background: #fff;
    color: #2c2c2c;
    padding: 40px;
}

/* ── HEADER ─────────────────────────────────── */
.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 3px solid #c8a96e;
    padding-bottom: 20px;
    margin-bottom: 28px;
}

.brand-left {
    flex: 1;
}

.brand-name {
    font-family: 'Playfair Display', DejaVu Serif, serif;
    font-size: 24px;
    font-weight: 700;
    color: #8b5c1a;
    letter-spacing: 0.5px;
    line-height: 1.2;
}

.brand-tagline {
    font-size: 11px;
    color: #a07840;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-top: 3px;
}

.brand-contact {
    text-align: right;
    font-size: 11.5px;
    color: #555;
    line-height: 1.7;
}

.brand-contact span {
    display: block;
}

.brand-contact .phone {
    font-weight: 700;
    color: #8b5c1a;
}

/* ── INVOICE TITLE BAND ──────────────────────── */
.invoice-title-band {
    background: linear-gradient(135deg, #8b5c1a 0%, #c8a96e 100%);
    color: #fff;
    text-align: center;
    padding: 10px 0;
    font-family: 'Playfair Display', DejaVu Serif, serif;
    font-size: 18px;
    letter-spacing: 4px;
    text-transform: uppercase;
    margin-bottom: 28px;
    border-radius: 2px;
}

/* ── INFO GRID ───────────────────────────────── */
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px 40px;
    background: #fdf8f2;
    border: 1px solid #e8d9c0;
    border-left: 4px solid #c8a96e;
    border-radius: 3px;
    padding: 18px 22px;
    margin-bottom: 28px;
}

.info-row {
    display: flex;
    gap: 8px;
    align-items: baseline;
    font-size: 12.5px;
}

.info-label {
    font-weight: 700;
    color: #8b5c1a;
    white-space: nowrap;
    min-width: 110px;
}

.info-value {
    color: #2c2c2c;
}

/* ── SECTION HEADING ─────────────────────────── */
.section-heading {
    font-family: 'Playfair Display', DejaVu Serif, serif;
    font-size: 14px;
    color: #8b5c1a;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px dashed #c8a96e;
}

/* ── TABLE ───────────────────────────────────── */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 24px;
}

thead tr {
    background: #8b5c1a;
    color: #fff;
}

thead th {
    padding: 10px 12px;
    text-align: left;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

tbody tr {
    border-bottom: 1px solid #ede0cc;
}

tbody tr:nth-child(even) {
    background: #fdf8f2;
}

tbody td {
    padding: 9px 12px;
    color: #2c2c2c;
    font-size: 13px;
}

.dish-index {
    width: 36px;
    color: #a07840;
    font-weight: 700;
    text-align: center;
}

/* ── TOTAL BOX ───────────────────────────────── */
.total-wrapper {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 36px;
}

.total-box {
    background: linear-gradient(135deg, #8b5c1a 0%, #c8a96e 100%);
    color: #fff;
    padding: 14px 30px;
    border-radius: 3px;
    text-align: right;
}

.total-label {
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    opacity: 0.85;
    margin-bottom: 2px;
}

.total-amount {
    font-family: 'Playfair Display', DejaVu Serif, serif;
    font-size: 22px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* ── DIVIDER ─────────────────────────────────── */
.divider {
    border: none;
    border-top: 1px dashed #c8a96e;
    margin-bottom: 18px;
}

/* ── FOOTER ──────────────────────────────────── */
.footer {
    text-align: center;
    font-size: 12px;
    color: #a07840;
    letter-spacing: 1px;
}

.footer .thanks {
    font-family: 'Playfair Display', DejaVu Serif, serif;
    font-size: 14px;
    color: #8b5c1a;
    margin-bottom: 4px;
}
</style>

</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="brand-left">
        <div class="brand-name">Thanh An Hội</div>
        <div class="brand-tagline">Dịch vụ nấu ăn</div>
    </div>
    <div class="brand-contact">
        <span class="phone">📞 0935 740 586 – 0704 419 862</span>
        <span>📍 17 Nguyễn Phúc Tần, Hội An, TP. Đà Nẵng</span>
    </div>
</div>

<!-- TITLE BAND -->
<div class="invoice-title-band">Hóa Đơn Đặt Tiệc</div>

<!-- ORDER INFO -->
<div class="info-grid">
    <div class="info-row">
        <span class="info-label">Mã đơn:</span>
        <span class="info-value">{{ $order->id }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Ngày tổ chức:</span>
        <span class="info-value">{{ $order->event_date }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Khách hàng:</span>
        <span class="info-value">{{ $order->customer->name ?? '' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Số bàn:</span>
        <span class="info-value">{{ $order->table_count }} bàn</span>
    </div>
    <div class="info-row">
        <span class="info-label">Tiền / bàn:</span>
        <span class="info-value">{{ number_format($order->price_per_table) }} đ</span>
    </div>
    @if ($order->deposit_amount > 0)
        <div class="info-row">
            <span class="info-label">Tiền cọc:</span>
            <span class="info-value">{{ number_format($order->deposit_amount) }} đ</span>
        </div>
    @endif
</div>

<!-- DISH TABLE -->
<div class="section-heading">Danh sách món</div>

<table>
    <thead>
        <tr>
            <th class="dish-index">#</th>
            <th>Tên món</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->dishes ?? [] as $index => $dish)
        <tr>
            <td class="dish-index">{{ $index + 1 }}</td>
            <td>{{ $dish->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- TOTAL -->
<div class="info-row" style="text-align:right; margin-top:20px;">
    <span style="font-size:18px;">Tổng tiền:</span>
    <span style="font-size:24px; font-weight:bold; color:#d32f2f;">
        {{ number_format($order->total_amount) }} đ
    </span>
</div>

<!-- FOOTER -->
<hr class="divider">
<div class="footer">
    <div class="thanks">Cảm ơn quý khách đã tin tưởng sử dụng dịch vụ!</div>
    <div>Thanh An Hội · 0935 740 586 · 17 Nguyễn Phúc Tần, Hội An, Đà Nẵng</div>
</div>

</body>
</html>