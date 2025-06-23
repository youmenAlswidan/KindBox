<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>تفاصيل الدليفري</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px 20px;
            color: #4A4A4A;
        }
        h2 {
            color: #9775FA;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(151, 117, 250, 0.25);
            border: none;
        }
        .card-body {
            padding: 30px 40px;
        }
        .card-title {
            font-size: 28px;
            font-weight: 700;
            color: #9775FA;
            margin-bottom: 20px;
            text-align: center;
        }
        .card-text {
            font-size: 18px;
            margin-bottom: 15px;
        }
        .card-text strong {
            color: #DE959C;
            min-width: 120px;
            display: inline-block;
        }
        .btn-secondary {
            display: block;
            width: 160px;
            margin: 30px auto 0;
            background: linear-gradient(90deg, #DE959C, #9775FA);
            border: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 16px;
            padding: 10px;
            color: white;
            box-shadow: 0 6px 15px rgba(222, 149, 156, 0.6);
            transition: background 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }
        .btn-secondary:hover {
            background: linear-gradient(90deg, #9775FA, #DE959C);
            box-shadow: 0 8px 20px rgba(151, 117, 250, 0.7);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Delivary Details </h2>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $delivery->first_name }} {{ $delivery->last_name }}</h5>
            <p class="card-text"><strong>Email :</strong> {{ $delivery->email }}</p>
            <p class="card-text"><strong>phone_number :</strong> {{ $delivery->phone_number ?? '-' }}</p>
            <p class="card-text"><strong>Delivery_id :</strong> {{ $delivery->id }}</p>
        </div>
    </div>

    <a href="{{ route('admin.delivery.index') }}" class="btn btn-secondary mt-4">Back to list </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
