<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title> </title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            background-color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        h2 {
            color: #9775FA;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-primary {
            background: linear-gradient(90deg, #9775FA, #DE959C);
            border: none;
            font-weight: bold;
            border-radius: 25px;
            padding: 10px 25px;
            transition: 0.3s ease;
            box-shadow: 0 4px 15px rgba(151, 117, 250, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #DE959C, #9775FA);
            box-shadow: 0 6px 20px rgba(222, 149, 156, 0.45);
        }

        table {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            border-radius: 20px;
            overflow: hidden;
        }

        thead tr {
            background: linear-gradient(90deg, #9775FA, #DE959C);
            color: #fff;
            font-weight: 600;
            font-size: 16px;
        }

        tbody tr:hover {
            background-color: #f8f2ff;
            transition: background-color 0.3s ease;
        }

        tbody td {
            vertical-align: middle;
            font-size: 15px;
        }

        /* تصميم أزرار الإجراءات */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .action-buttons a,
        .action-buttons button {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.3s ease;
            box-shadow: 0 3px 8px rgba(151, 117, 250, 0.15);
            text-decoration: none;
            color: white;
            user-select: none;
        }

        .action-buttons a.btn-info {
            background: #9775FA;
        }

        .action-buttons a.btn-info:hover {
            background: #DE959C;
            box-shadow: 0 5px 15px rgba(222, 149, 156, 0.5);
            transform: translateY(-3px);
        }

        .action-buttons a.btn-warning {
            background: #DE959C;
        }

        .action-buttons a.btn-warning:hover {
            background: #9775FA;
            box-shadow: 0 5px 15px rgba(151, 117, 250, 0.5);
            transform: translateY(-3px);
        }

        .action-buttons button.btn-danger {
            background: #C33F6A;
        }

        .action-buttons button.btn-danger:hover {
            background: #9B2D57;
            box-shadow: 0 5px 15px rgba(155, 45, 87, 0.5);
            transform: translateY(-3px);
        }

        .action-buttons i.bi {
            font-size: 18px;
        }

        form {
            margin: 0;
        }

        /* تنسيق رسالة النجاح */
        .alert-success {
            background-color: #DE959C;
            border-color: #9775FA;
            color: white;
            font-weight: 600;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(222, 149, 156, 0.6);
        }

        /* تنسيق رسالة لا يوجد حسابات */
        tbody tr td[colspan="4"] {
            font-style: italic;
            color: #9775FA;
            font-weight: 600;
            padding: 25px 0;
        }
    </style>
</head>
<body>

<div class="container mt-5">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h2>Delivary Account</h2>
    <div class="d-flex justify-content-center mb-4">
        <a href="{{ route('admin.delivery.create') }}" class="btn btn-primary"> Create New Delivary </a>
    </div>

    <table class="table table-bordered table-striped text-center align-middle">
        <thead>
            <tr>
                <th> Full Name</th>
                <th>Email </th>
                <th>phone_number </th>
                <th style="width: 230px;">Option</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deliveries as $delivery)
                <tr>
                    <td>{{ $delivery->first_name }} {{ $delivery->last_name }}</td>
                    <td>{{ $delivery->email }}</td>
                    <td>{{ $delivery->phone_number ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.delivery.show', $delivery->id) }}" class="btn btn-info btn-sm" title="عرض التفاصيل">
                                <i class="bi bi-eye"></i> Show
                            </a>

                            <a href="{{ route('admin.delivery.edit', $delivery->id) }}" class="btn btn-warning btn-sm" title="تعديل">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <form action="{{ route('admin.delivery.destroy', $delivery->id) }}" method="POST" >
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit" title="حذف">
                                    <i class="bi bi-trash3"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"> no account added.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $deliveries->links() }}
    </div>

</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
