<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - {{ $receiptNo }}</title>
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 13px;
        }

        .toolbar {
            text-align: right;
            padding: 10px 20px;
            background: #f3f4f6;
        }

        .toolbar button {
            background: #0284c7;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }

        .toolbar button:hover {
            background: #0369a1;
        }

        .page {
            max-width: 780px;
            margin: 0 auto;
            padding: 20px;
        }

        .copy {
            padding: 18px 6px;
        }

        .copy + .copy {
            border-top: 1px dashed #999;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .header-center {
            flex: 1;
            text-align: center;
        }

        .school-name {
            font-size: 17px;
            font-weight: bold;
            margin: 0 0 2px 0;
        }

        .school-meta {
            font-size: 12px;
            color: #333;
            margin: 0;
        }

        .receipt-title {
            font-weight: bold;
            font-size: 13px;
            margin-top: 6px;
        }

        .header-right {
            font-size: 12px;
            text-align: right;
            white-space: nowrap;
            padding-top: 2px;
        }

        .header-right div {
            margin-bottom: 2px;
        }

        .header-right b {
            display: inline-block;
            min-width: 90px;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-top: 14px;
            font-size: 13px;
        }

        .received-line {
            margin-top: 14px;
            font-size: 13px;
        }

        .in-words {
            margin-top: 6px;
            font-size: 13px;
            padding-left: 20px;
        }

        .remarks {
            margin-top: 10px;
            font-size: 13px;
        }

        .balance-table {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        table.balances {
            border-collapse: collapse;
        }

        table.balances th,
        table.balances td {
            border: 1px solid #333;
            padding: 6px 18px;
            font-size: 12.5px;
            text-align: center;
        }

        table.balances th {
            font-weight: bold;
        }

        .signature {
            text-align: center;
            font-size: 12.5px;
        }

        .signature .line {
            border-top: 1px solid #333;
            width: 130px;
            margin: 30px auto 4px auto;
        }

        @media print {
            .toolbar {
                display: none;
            }

            body {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <button onclick="window.print()">Print</button>
    </div>

    <div class="page">
        @for ($i = 0; $i < 2; $i++)
            <div class="copy">
                <div class="header">
                    <div class="header-center">
                        <p class="school-name">{{ auth()->user()->school->name ?? 'e-Zone International Pvt. Ltd.' }}</p>
                        <p class="school-meta">{{ auth()->user()->school->address ?? 'Kathmandu' }}</p>
                        <p class="school-meta">Phone: {{ auth()->user()->school->phone ?? '-' }}</p>
                        <p class="receipt-title">Cash Receipt</p>
                    </div>
                    <div class="header-right">
                        <div><b>Receipt No.:</b> {{ $receiptNo }}</div>
                        <div><b>Paid Date:</b> {{ \Carbon\Carbon::parse($paymentDate)->format('Y/m/d') }}</div>
                        <div><b>Student ID:</b> {{ $student->student_uid }}</div>
                    </div>
                </div>

                <div class="info-row">
                    <div><b>Student Name:</b> {{ trim($student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name) }}</div>
                    <div><b>Class:</b> {{ $student->schoolClass->name ?? '-' }}</div>
                    <div><b>Section:</b> {{ $student->section->name ?? '-' }}</div>
                    <div><b>Roll No.:</b> {{ $student->roll_number ?? '-' }}</div>
                </div>

                <div class="received-line">
                    Received with thanks from <b>{{ trim($student->first_name . ' ' . $student->last_name) }}</b>
                    a sum of Rs. <b>{{ number_format($paidAmount, 2) }}</b>
                </div>

                <div class="in-words">
                    (In Words: Rs. {{ $amountInWords }} Rupees Only)
                </div>

                <div class="remarks">
                    Remarks:
                </div>

                <div class="balance-table">
                    <table class="balances">
                        <tr>
                            <th>Pre. Balance</th>
                            <th>Paid Amount</th>
                            <th>Remaining Balance</th>
                        </tr>
                        <tr>
                            <td>{{ number_format($preBalance, 2) }}</td>
                            <td>{{ number_format($paidAmount, 2) }}</td>
                            <td>{{ number_format($remainingBalance, 2) }}</td>
                        </tr>
                    </table>

                    <div class="signature">
                        <div class="line"></div>
                        {{ auth()->user()->name ?? 'admin' }}<br>
                        Received By
                    </div>
                </div>
            </div>
        @endfor
    </div>

</body>
</html>