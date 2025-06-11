<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Price Threshold Alert</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
        }
    </style>
</head>

<body>
    <h2>🚨 Price Threshold Alert</h2>
    <p>The following raw materials have exceeded their price threshold:</p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Price</th>
                <th>Threshold</th>
                <th>View Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($materials as $material)
            <tr>
                <td>{{ $material['name'] }}</td>
                <td>{{ $material['rmcode'] }}</td>
                <td>₹{{ $material['price'] }}</td>
                <td>₹{{ $material['threshold'] }}</td>
                <td>
                    <a href="{{ url('/editrawmaterial/' . $material['id']) }}">View Details</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>Please update the prices if required. Thank you!</p>
</body>

</html>