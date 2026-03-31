<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
        }

        .text-muted {
            color: #6b7280;
            font-size: 11px;
        }

        .grid {
            width: 100%;
            margin-top: 10px;
        }

        .grid td {
            width: 50%;
            padding: 10px;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
        }

        .value {
            font-size: 18px;
            font-weight: bold;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 8px;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .status-approved {
            color: #16a34a;
            font-weight: bold;
        }

        .status-rejected {
            color: #dc2626;
            font-weight: bold;
        }

        .status-cancelled {
            color: #d97706;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <h1>Loan & Inventory Report</h1>
    <p class="text-muted">
        Generated at: {{ now()->format('d M Y H:i') }}
    </p>

    <!-- LOAN SUMMARY -->
    <h2>Loan Summary</h2>

    <table class="grid">
        <tr>
            <td>
                <div class="card">
                    Total Loans
                    <div class="value">{{ $loans->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Approved
                    <div class="value">{{ $loans->where('status','approved')->count() }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="card">
                    Rejected
                    <div class="value">{{ $loans->where('status','rejected')->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Cancelled
                    <div class="value">{{ $loans->where('status','cancelled')->count() }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- ITEM SUMMARY -->
    <h2>Item Summary</h2>

    <table class="grid">
        <tr>
            <td>
                <div class="card">
                    Total Items
                    <div class="value">{{ $items->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Total Units
                    <div class="value">{{ $itemUnits->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Damaged Units
                    <div class="value">{{ $itemUnits->where('condition','damaged')->count() }}</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="card">
                    Minor Damaged Units
                    <div class="value">{{ $itemUnits->where('condition', 'minor_damage')->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Major Damaged Units
                    <div class="value">{{ $itemUnits->where('condition', 'major_damage')->count() }}</div>
                </div>
            </td>
            <td>
                <div class="card">
                    Lost Units
                    <div class="value">{{ $itemUnits->where('condition','lost')->count() }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- DETAIL TABLE -->
    <h2>Recent Loans</h2>

    <table>
        <thead>
            <tr>
                <th>Loan Code</th>
                <th>User</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans->take(10) as $loan)
                <tr>
                    <td>{{ $loan->loan_code }}</td>
                    <td>{{ $loan->user->name ?? '-' }}</td>
                    <td class="status-{{ $loan->status }}">
                        {{ ucfirst($loan->status) }}
                    </td>
                    <td>{{ $loan->created_at->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>