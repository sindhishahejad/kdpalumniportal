<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KDP Alumni Donation Receipt - {{ $donation->transaction_id }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 25px;
            font-size: 12px;
            background-color: #ffffff;
        }
        .receipt-container {
            border: 2px solid #1C3661;
            padding: 25px;
            position: relative;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #8b0000;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 18px;
            color: #1C3661;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 10px;
            color: #4b5563;
        }
        .exemption-banner {
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 6px 10px;
            text-align: center;
            font-size: 9px;
            color: #334155;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .title-block {
            text-align: center;
            margin-bottom: 18px;
        }
        .title-block h2 {
            font-size: 14px;
            color: #8b0000;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }
        .title-block span {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
        }
        .meta-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .meta-table td {
            padding: 3px 0;
            font-size: 11px;
        }
        .label {
            font-weight: bold;
            color: #475569;
            width: 130px;
        }
        .value {
            color: #0f172a;
            font-weight: 600;
        }
        .amount-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 12px 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .amount-row {
            display: table;
            width: 100%;
        }
        .amount-cell {
            display: table-cell;
            vertical-align: middle;
        }
        .amount-figures {
            font-size: 18px;
            font-weight: 900;
            color: #15803d;
        }
        .amount-words {
            font-size: 11px;
            font-style: italic;
            color: #166534;
            margin-top: 3px;
        }
        .legal-clause {
            font-size: 9px;
            color: #64748b;
            line-height: 1.4;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            text-align: justify;
        }
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 35px;
        }
        .signatures-table td {
            text-align: center;
            vertical-align: bottom;
            font-size: 10px;
        }
        .sig-line {
            border-top: 1px solid #334155;
            padding-top: 4px;
            font-weight: bold;
            text-transform: uppercase;
            width: 150px;
            margin: 0 auto;
        }
        .watermark {
            position: absolute;
            top: 40%;
            left: 20%;
            font-size: 60px;
            color: rgba(28, 54, 97, 0.04);
            transform: rotate(-30deg);
            font-weight: 900;
            letter-spacing: 10px;
            user-select: none;
            z-index: 0;
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="watermark">KDP PATAN</div>

    <!-- Header -->
    <div class="header">
        <h1>ALUMNI ASSOCIATION OF K. D. POLYTECHNIC</h1>
        <p>Govt. Polytechnic Campus, Patan, Gujarat - 384265 • Email: alumni@kdpatan.ac.in</p>
        <p>Registration No: GUJ/PATAN/2018/SOC/892 • Website: www.kdpatan.ac.in</p>
    </div>

    <!-- 80G Reference Banner -->
    <div class="exemption-banner">
        TAX EXEMPTION ACKNOWLEDGEMENT UNDER SECTION 80G OF THE INCOME TAX ACT, 1961<br>
        <span style="font-size: 8px; font-weight: normal; color: #64748b;">Order No. CIT(E)/AHM/80G/2020-21/KDP-098 • Valid in Perpetuity</span>
    </div>

    <!-- Title -->
    <div class="title-block">
        <h2>Official Donation Receipt</h2>
        <span>Formal Acknowledgement of Voluntary Contribution</span>
    </div>

    <!-- Receipt Meta -->
    <table class="meta-table">
        <tr>
            <td class="label">Receipt Number:</td>
            <td class="value" style="color: #8b0000; font-family: monospace;">{{ $receiptNo }}</td>
            <td class="label" style="text-align: right; width: 100px;">Date of Receipt:</td>
            <td class="value" style="text-align: right; width: 100px;">{{ $donation->created_at->format('d M, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Transaction Reference:</td>
            <td class="value" style="font-family: monospace;">{{ $donation->transaction_id }}</td>
            <td class="label" style="text-align: right;">Payment Mode:</td>
            <td class="value" style="text-align: right;">{{ $donation->payment_method }}</td>
        </tr>
    </table>

    <!-- Donor Information Table -->
    <table class="data-table" style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; margin-top: 10px;">
        <tr>
            <td class="label" style="padding: 4px 8px;">Received with Thanks from:</td>
            <td class="value" style="font-size: 13px; font-weight: bold; color: #1C3661; padding: 4px 8px;">
                {{ $donation->user->name }}
            </td>
        </tr>
        <tr>
            <td class="label" style="padding: 4px 8px;">Donor Alumni / ID:</td>
            <td class="value" style="padding: 4px 8px;">
                KDP-{{ str_pad($donation->user->id, 4, '0', STR_PAD_LEFT) }} 
                @if($donation->user->entry_no) (Roll: {{ $donation->user->entry_no }}) @endif
            </td>
        </tr>
        <tr>
            <td class="label" style="padding: 4px 8px;">Email &amp; Contact:</td>
            <td class="value" style="padding: 4px 8px;">
                {{ $donation->user->email }} @if($donation->user->phone) • {{ $donation->user->phone }} @endif
            </td>
        </tr>
        <tr>
            <td class="label" style="padding: 4px 8px;">Allocated Campaign Fund:</td>
            <td class="value" style="padding: 4px 8px; color: #8b0000;">
                {{ $donation->campaign }}
            </td>
        </tr>
        @if($donation->notes)
            <tr>
                <td class="label" style="padding: 4px 8px;">Purpose / Dedication:</td>
                <td class="value" style="padding: 4px 8px; font-style: italic; color: #475569;">
                    "{{ $donation->notes }}"
                </td>
            </tr>
        @endif
    </table>

    <!-- Amount Box -->
    <div class="amount-box">
        <div class="amount-row">
            <div class="amount-cell">
                <span style="font-size: 10px; text-transform: uppercase; font-weight: bold; color: #166534; display: block;">Total Contribution Amount</span>
                <span class="amount-figures">₹ {{ number_format($donation->amount, 2) }}</span>
                <div class="amount-words">Amount in words: {{ $amountInWords }}</div>
            </div>
            <div class="amount-cell" style="text-align: right; width: 100px;">
                <span style="display: inline-block; background-color: #15803d; color: #ffffff; font-size: 9px; font-weight: bold; padding: 4px 8px; border-radius: 3px; text-transform: uppercase;">
                    PAID &amp; VERIFIED
                </span>
            </div>
        </div>
    </div>

    <!-- Statutory 80G Note -->
    <div class="legal-clause">
        <strong>Statutory Tax Deduction Clause:</strong> Certified that this donation is received as a voluntary contribution exclusively towards the advancement of educational amenities, laboratory infrastructure, and student scholarship grants at K. D. Polytechnic, Patan. This electronic receipt qualifies for tax deduction under Section 80G of the Indian Income Tax Act, 1961. Please retain this certificate for your income tax filings.
    </div>

    <!-- Signatures Section -->
    <table class="signatures-table">
        <tr>
            <td style="width: 33%;">
                <div style="font-size: 9px; color: #64748b; margin-bottom: 30px;">Generated Digitally by Portal</div>
                <div class="sig-line">System Verifier</div>
            </td>
            <td style="width: 34%;">
                <div style="font-family: 'Times New Roman', Times, serif; font-style: italic; font-size: 15px; color: #1C3661; margin-bottom: 8px;">
                    R. M. Joshi
                </div>
                <div class="sig-line">Honorary Treasurer</div>
            </td>
            <td style="width: 33%;">
                <div style="font-family: 'Times New Roman', Times, serif; font-style: italic; font-size: 15px; color: #1C3661; margin-bottom: 8px;">
                    A. S. Patel
                </div>
                <div class="sig-line">President / Principal</div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
