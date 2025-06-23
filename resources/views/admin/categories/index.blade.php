<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إدارة التصنيفات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Tahoma', sans-serif;
            padding: 30px;
        }

        .card {
            background-color: #fff;
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .btn-custom {
            background-color: #9775FA;
            color: white;
            border-radius: 12px;
        }

        .btn-custom:hover {
            background-color: #7d5cf0;
        }

        .btn-danger {
            background-color: #DE959C !important;
            border-radius: 12px;
        }

        .btn-danger:hover {
            background-color: #c57880 !important;
        }

        th {
            background-color: #9775FA;
            color: white;
        }

        .table > :not(caption) > * > * {
            vertical-align: middle;
        }

        img.thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-dark">إدارة التصنيفات</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-custom">إضافة تصنيف جديد</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <div class="card p-4">
            <table class="table table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الصورة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="thumb">
                                @else
                                    <span class="text-muted">لا توجد صورة</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-custom">تعديل</a>
                                
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">لا توجد تصنيفات حالياً</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
