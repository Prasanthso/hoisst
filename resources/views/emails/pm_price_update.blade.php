<!DOCTYPE html>
<html>

<head>
    <title>Price Update Alert</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>🚨 Price Update Alert</h2>
    <p>The following packing materials require a price update:</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>PM Code</th>
                <th>Material Name</th>
                <th>View Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materials as $index => $material)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $material['pmcode'] }}</td>
                <td>{{ $material['name'] }}</td>
                <td>
                    <a href="{{ url('/editrawmaterial/' . $material['id']) }}">View Details</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>Please check the system for more details.</p>
</body>

</html>