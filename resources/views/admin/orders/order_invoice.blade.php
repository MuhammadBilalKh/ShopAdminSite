<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        /* ============================================================
       Invoice Page – Self-contained styles (no Bootstrap needed).
       Screen styles show the action toolbar + centred invoice card.
       Print styles strip everything except the invoice itself.
    ============================================================ */

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #6c63ff;
            --dark: #1a1a2e;
            --secondary: #ff6584;
            --accent: #43c6ac;
            --border: #e9ecef;
            --radius: 12px;
        }

        html {
            font-size: 14px;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;
            background: #f0f2f8;
            color: #2d2d2d;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* ── Action Toolbar (screen only) ── */
        .inv-toolbar {
            position: sticky;
            top: 0;
            z-index: 200;
            background: var(--dark);
            padding: .75rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            box-shadow: 0 2px 16px rgba(0, 0, 0, .25);
        }

        .inv-toolbar-brand {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.5px;
        }

        .inv-toolbar-brand span {
            color: var(--secondary);
        }

        .inv-toolbar-id {
            font-size: .82rem;
            color: rgba(255, 255, 255, .5);
            font-weight: 600;
            margin-left: .5rem;
        }

        .inv-toolbar-actions {
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .tbr-btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            border: none;
            border-radius: 50px;
            padding: .48rem 1.1rem;
            font-family: inherit;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            transition: opacity .2s, transform .2s;
            white-space: nowrap;
            text-decoration: none;
        }

        .tbr-btn:hover {
            opacity: .85;
            transform: translateY(-1px);
        }

        .tbr-btn i {
            font-size: .95rem;
        }

        .tbr-btn.back {
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .tbr-btn.print {
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .tbr-btn.pdf {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(108, 99, 255, .4);
        }

        .tbr-btn.pdf:hover {
            box-shadow: 0 6px 20px rgba(108, 99, 255, .55);
        }

        /* ── Page layout ── */
        .inv-page {
            padding: 32px 20px 64px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── Not found state ── */
        .inv-not-found {
            text-align: center;
            padding: 80px 20px;
            color: #888;
        }

        .inv-not-found i {
            font-size: 4rem;
            color: #ccc;
            display: block;
            margin-bottom: 1rem;
        }

        .inv-not-found h2 {
            font-size: 1.4rem;
            color: #444;
            margin-bottom: .5rem;
        }

        /* ══════════════════════════════════════
       INVOICE CARD
    ══════════════════════════════════════ */
        .invoice-card {
            background: #fff;
            width: 100%;
            max-width: 860px;
            border-radius: var(--radius);
            box-shadow: 0 8px 40px rgba(0, 0, 0, .12);
            overflow: hidden;
            position: relative;
        }

        /* Cancelled watermark */
        .invoice-card.is-cancelled::before {
            content: 'CANCELLED';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 7rem;
            font-weight: 900;
            color: rgba(255, 101, 132, .08);
            letter-spacing: 10px;
            pointer-events: none;
            white-space: nowrap;
            z-index: 0;
        }

        /* ── Invoice Header ── */
        .inv-header {
            background: linear-gradient(135deg, var(--dark) 0%, var(--primary) 100%);
            padding: 36px 44px 32px;
            color: #fff;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            position: relative;
            z-index: 1;
        }

        .inv-brand {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1;
        }

        .inv-brand span {
            color: var(--secondary);
        }

        .inv-brand-sub {
            font-size: .72rem;
            color: rgba(255, 255, 255, .5);
            margin-top: 5px;
            letter-spacing: .6px;
            text-transform: uppercase;
        }

        .inv-title-block {
            text-align: right;
        }

        .inv-doc-title {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .92);
        }

        .inv-order-id {
            font-size: .92rem;
            color: rgba(255, 255, 255, .6);
            margin-top: 5px;
            font-weight: 600;
        }

        .inv-status-pill {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 16px;
            border-radius: 50px;
            font-size: .78rem;
            font-weight: 800;
        }

        /* ── Meta strip ── */
        .inv-meta {
            background: #f8f9fc;
            padding: 16px 44px;
            display: flex;
            gap: 0;
            border-bottom: 1px solid var(--border);
            position: relative;
            z-index: 1;
        }

        .inv-meta-item {
            flex: 1;
            padding-right: 20px;
        }

        .inv-meta-item:last-child {
            padding-right: 0;
        }

        .inv-meta-label {
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: #aaa;
            margin-bottom: 4px;
        }

        .inv-meta-value {
            font-size: .88rem;
            font-weight: 700;
            color: var(--dark);
        }

        .payment-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #ede9ff;
            color: var(--primary);
            font-weight: 700;
            font-size: .8rem;
            padding: 3px 12px;
            border-radius: 50px;
        }

        /* ── Address block ── */
        .inv-addresses {
            display: flex;
            padding: 24px 44px;
            border-bottom: 1px solid var(--border);
            gap: 0;
            position: relative;
            z-index: 1;
        }

        .inv-addr {
            flex: 1;
            padding-right: 28px;
        }

        .inv-addr:last-child {
            padding-right: 0;
        }

        .inv-addr-title {
            font-size: .7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--primary);
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #ede9ff;
        }

        .inv-addr-name {
            font-weight: 700;
            font-size: .92rem;
            margin-bottom: 4px;
            color: var(--dark);
        }

        .inv-addr-line {
            font-size: .82rem;
            color: #666;
            line-height: 1.75;
        }

        /* ── Section headings inside card ── */
        .inv-section {
            padding: 0 44px;
            position: relative;
            z-index: 1;
        }

        .inv-section-title {
            font-size: .7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--primary);
            padding: 20px 0 10px;
            border-bottom: 2px solid #ede9ff;
            margin-bottom: 0;
        }

        /* ── Items table ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .86rem;
        }

        .items-table thead tr {
            background: var(--dark);
            color: #fff;
        }

        .items-table thead th {
            padding: 11px 13px;
            font-weight: 700;
            font-size: .73rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f8f9fc;
        }

        .items-table tbody tr:nth-child(odd) {
            background: #fff;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .items-table tbody td {
            padding: 12px 13px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .col-num {
            width: 44px;
            text-align: center;
            color: #aaa;
            font-weight: 700;
        }

        .col-img {
            width: 56px;
        }

        .col-name {}

        .col-qty {
            width: 70px;
            text-align: center;
            white-space: nowrap;
            font-weight: 700;
        }

        .col-unit {
            width: 110px;
            text-align: right;
            white-space: nowrap;
            font-weight: 600;
            color: #555;
        }

        .col-total {
            width: 110px;
            text-align: right;
            white-space: nowrap;
            font-weight: 800;
            color: var(--dark);
        }

        .item-thumb {
            width: 46px;
            height: 46px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
        }

        .item-name {
            font-weight: 700;
            color: var(--dark);
            line-height: 1.3;
        }

        .item-cat {
            font-size: .75rem;
            color: #aaa;
            margin-top: 2px;
        }

        /* ── Totals ── */
        .inv-totals-row {
            padding: 16px 44px 24px;
            display: flex;
            justify-content: flex-end;
            position: relative;
            z-index: 1;
        }

        .totals-box {
            width: 300px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        .t-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 16px;
            font-size: .87rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .t-row:last-child {
            border-bottom: none;
        }

        .t-row .t-lbl {
            color: #777;
            font-weight: 600;
        }

        .t-row .t-val {
            font-weight: 700;
            color: var(--dark);
        }

        .t-row.discount .t-lbl,
        .t-row.discount .t-val {
            color: #059669;
        }

        .t-row.grand {
            background: linear-gradient(135deg, var(--dark), var(--primary));
            padding: 13px 16px;
        }

        .t-row.grand .t-lbl {
            color: rgba(255, 255, 255, .8);
            font-weight: 700;
            font-size: .9rem;
        }

        .t-row.grand .t-val {
            color: #fff;
            font-weight: 800;
            font-size: 1.08rem;
        }

        /* ── Timeline ── */
        .inv-timeline {
            padding: 0 44px 28px;
            position: relative;
            z-index: 1;
        }

        .tl-list {
            list-style: none;
            margin-top: 12px;
        }

        .tl-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding-bottom: 16px;
            position: relative;
        }

        .tl-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 18px;
            bottom: 0;
            width: 2px;
            background: #ede9ff;
        }

        .tl-dot-wrap {
            flex-shrink: 0;
            padding-top: 2px;
        }

        .tl-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--primary);
            border: 3px solid #ede9ff;
            display: block;
        }

        .tl-item:last-child .tl-dot {
            background: var(--accent);
        }

        .tl-content {}

        .tl-status {
            font-weight: 700;
            font-size: .88rem;
            color: var(--dark);
        }

        .tl-date {
            font-size: .76rem;
            color: #aaa;
            margin-top: 2px;
        }

        /* ── Notes / Terms ── */
        .inv-notes {
            padding: 0 44px 28px;
            position: relative;
            z-index: 1;
        }

        .notes-box {
            background: #f8f9fc;
            border: 1.5px dashed var(--border);
            border-radius: 10px;
            padding: 14px 18px;
            font-size: .82rem;
            color: #777;
            line-height: 1.7;
            margin-top: 12px;
        }

        .notes-box strong {
            color: var(--dark);
        }

        /* ── Invoice footer ── */
        .inv-footer {
            background: #f8f9fc;
            border-top: 1px solid var(--border);
            padding: 20px 44px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: relative;
            z-index: 1;
        }

        .inv-footer-thanks {
            font-size: .82rem;
            color: #888;
            line-height: 1.6;
        }

        .inv-footer-thanks strong {
            color: var(--dark);
            font-size: .88rem;
        }

        .inv-footer-legal {
            text-align: right;
            font-size: .72rem;
            color: #bbb;
            line-height: 1.6;
        }

        /* ── Page number (print) ── */
        .inv-page-num {
            text-align: center;
            font-size: .72rem;
            color: #ccc;
            padding: 10px 0 16px;
            position: relative;
            z-index: 1;
        }

        /* ══════════════════════════════════════
       PRINT STYLES
    ══════════════════════════════════════ */
        @media print {
            html {
                font-size: 12px;
            }

            body {
                background: #fff !important;
            }

            .inv-toolbar {
                display: none !important;
            }

            .inv-page {
                padding: 0 !important;
                background: #fff !important;
            }

            .invoice-card {
                box-shadow: none !important;
                border-radius: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            /* Force background colours to print */
            .inv-header,
            .t-row.grand {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .items-table tbody tr:nth-child(even),
            .inv-meta,
            .inv-footer {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }

        /* ══════════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════════ */
        @media (max-width: 700px) {
            .inv-header {
                padding: 24px 20px 20px;
                flex-direction: column;
                gap: 12px;
            }

            .inv-title-block {
                text-align: left;
            }

            .inv-meta {
                flex-wrap: wrap;
                padding: 14px 20px;
                gap: 12px 0;
            }

            .inv-meta-item {
                flex: 0 0 50%;
            }

            .inv-addresses {
                flex-direction: column;
                padding: 20px;
                gap: 20px;
            }

            .inv-addr {
                padding-right: 0;
            }

            .inv-section,
            .inv-totals-row,
            .inv-timeline,
            .inv-notes,
            .inv-footer {
                padding-left: 20px;
                padding-right: 20px;
            }

            .inv-doc-title {
                font-size: 1.3rem;
            }

            .col-img,
            .item-thumb {
                display: none;
            }

            .totals-box {
                width: 100%;
            }

            .inv-footer {
                flex-direction: column;
                text-align: center;
            }

            .inv-footer-legal {
                text-align: center;
            }

            .inv-toolbar {
                padding: .6rem 1rem;
            }

            .tbr-btn span {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-card" id="invoiceCard" style="">

        <!-- ── Header ── -->
        <div class="inv-header">
            <div>
                <div class="inv-brand" id="invBrand">{{ APPLICATION_NAME }}</div>
                <div class="inv-brand-sub">Your Trusted Online Store</div>
            </div>
            <div class="inv-title-block">
                <div class="inv-doc-title">Invoice</div>
                <div class="inv-order-id" id="invOrderId">ORD-{{ $orderDetail->customer_order_id }}</div>
            </div>
        </div>

        <div class="inv-meta">
            <div class="inv-meta-item">
                <div class="inv-meta-label">Invoice Date</div>
                <div class="inv-meta-value" id="invInvoiceDate">{{ $orderDetail->created_at->toDateString() }}</div>
            </div>
            <div class="inv-meta-item">
                <div class="inv-meta-label">Payment</div>
                <div class="inv-meta-value" id="invPayment"><span class="payment-chip"><i class="ri-bank-card-line"
                            style="font-size:.85rem"></i>Cash on Delivery</span></div>
            </div>
            <div class="inv-meta-item">
                <div class="inv-meta-label">Total Items</div>
                <div class="inv-meta-value" id="invItemCount">{{ count($orderDetail->getOrderLineItems) }}</div>
            </div>
        </div>

        <div class="inv-addresses">
            <div class="inv-addr" id="addrBillTo">
                <div class="inv-addr-title">Bill To</div>
                <div class="inv-addr-name">{{ $orderDetail->getOrderBy->full_name }}</div>
                <div class="inv-addr-line">{{ $orderDetail->getOrderBy->email_address }}</div>
                <div class="inv-addr-line">{{ $orderDetail->getOrderBy->mobile_number }}</div>
                <div class="inv-addr-line">{{ $orderDetail->getOrderBy->address }}</div>
            </div>
            <div class="inv-addr" id="addrShipTo">
                <div class="inv-addr-title">Ship To</div>
                <div class="inv-addr-name">{{ $orderDetail->getOrderBy->full_name }}</div>
                <div class="inv-addr-line">{{ $orderDetail->getOrderBy->address }}</div>
            </div>
            <div class="inv-addr" id="addrFrom">
                <div class="inv-addr-title">From</div>
                <div class="inv-addr-name">ShopZone</div>
                <div class="inv-addr-line">support@shopzone.com</div>
                <div class="inv-addr-line">www.shopzone.com</div>
                <div class="inv-addr-line"></div>
            </div>
        </div>

        <div class="inv-section">
            <div class="inv-section-title">Items Ordered</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="col-num">#</th>
                        <th class="col-img"></th>
                        <th class="col-name">Product</th>
                        <th class="col-qty">Qty</th>
                        <th class="col-unit">Unit Price</th>
                        <th class="col-total">Total</th>
                    </tr>
                </thead>
                <tbody id="invItemsBody">
                    @foreach ($orderDetail->getOrderLineItems as $key => $value)
                        <tr>
                            <td class="col-num">{{ $key + 1 }}</td>
                            <td class="col-img">
                                <img src="{{ asset('product_profile_image/' . $value->getLineItemProduct->product_profile_image) }}"
                                    alt="{{ $value->getLineItemProduct->product_name }}" class="item-thumb"
                                    onerror="this.src='https://placehold.co/46x46/ede9ff/6c63ff?text=P'">
                            </td>
                            <td class="col-name">
                                <div class="item-name">{{ $value->getLineItemProduct->product_name }}</div>
                            </td>
                            <td class="col-qty">{{ $value->quantity }}</td>
                            <td class="col-unit">Rs. {{ $value->price }}</td>
                            <td class="col-total">Rs. {{ number_format($value->price * $value->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="inv-totals-row">
            <div class="totals-box" id="invTotalsBox">
                <div class="t-row">
                    <span class="t-lbl">Subtotal</span>
                    <span class="t-val">Rs. {{ number_format($orderDetail->total_amount) }}</span>
                </div>
                <div class="t-row">
                    <span class="t-lbl"><i class="ri-truck-line"
                            style="margin-right:4px;color:#43c6ac"></i>Shipping</span>
                    <span class="t-val"
                        style="color:#059669">{{ $orderDetail->getShippingMethod->shipping_method_name }}</span>
                </div>
                <div class="t-row grand">
                    <span class="t-lbl">Grand Total</span>
                    <span class="t-val">Rs.
                        {{ number_format($orderDetail->total_amount + $orderDetail->getShippingMethod->cost) }}</span>
                </div>
            </div>
        </div>

        <div class="inv-notes">
            <div class="inv-section-title" style="padding-top:0">Notes &amp; Terms</div>
            <div class="notes-box">
                <strong>Payment Terms:</strong> Payment was collected at the time of order placement.<br>
                <strong>Returns:</strong> Items can be returned within 30 days of delivery in original condition.<br>
                <strong>Disputes:</strong> For any billing discrepancies, contact <strong>support@shopzone.com</strong>
                within 7 days of invoice date.
            </div>
        </div>

        <div class="inv-footer">
            <div class="inv-footer-thanks" id="invFooterThanks"><strong>Thank you for shopping with
                    ShopZone!</strong><br>
                Questions? Email <strong>support@shopzone.com</strong><br>
                quoting order <strong>ORD-1789493808219</strong>.</div>
            <div class="inv-footer-legal">
                This is a computer-generated invoice.<br>
                No signature required.
            </div>
        </div>

    </div>
</body>

</html>
