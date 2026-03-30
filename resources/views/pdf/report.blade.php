<h1>Report</h1>

<h2>Loan Summary</h2>
<ul>
    <li>Total: {{ $loans->count() }}</li>
    <li>Approved: {{ $loans->where('status','approved')->count() }}</li>
</ul>

<h2>Items</h2>
<ul>
    <li>Total: {{ $items->count() }}</li>
</ul>