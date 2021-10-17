<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{$title}}</title>
    <style>
        body {
            font-family: DejaVu Sans
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #dddddd;
            border-collapse: collapse;s
        }

        .table th {
            font-weight: bold;
            padding: 5px;
            background: #efefef;
            border: 1px solid #dddddd;
        }

        .table td {
            border: 1px solid #dddddd;
            padding: 5px;
        }
    </style>
    <!-- <link rel="stylesheet" href="{{public_path('tailwind.min.css')}}"> -->
    <!-- TODO styles for table -->
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-3">{{$title}}</h2>
        <table class="table table-bordered mb-5">
            <thead>
                <tr class="table-danger">
                    <th scope="col">#</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Оплачуваний</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data ?? '' as $item)
                <tr>
                    <th scope="row">{{ $loop->index+1 }}</th>
                    <td>{{ $item['date'] }}</td>
                    <td>{{ $item['type'] }}</td>
                    <td>{{ $item['amount'] ? 'Так' : 'Ні' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <strong>Робочих днів - {{$workDays}}</strong>
        <strong>Оплачуваних днів - {{$paidDays}}</strong>

        <!--Talwind       <table class="table-auto">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Paid</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data ?? '' as $item)
                <tr>
                    <th scope="row">{{ $loop->index+1 }}</th>
                    <td>{{ $item['date'] }}</td>
                    <td>{{ $item['type'] }}</td>
                    <td>{{ $item['amount'] ? 'Yes' : 'No' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table> -->
    </div>

</body>

</html>