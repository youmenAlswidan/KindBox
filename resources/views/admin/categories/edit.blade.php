<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل التصنيف</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F8F8F8;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #9775FA;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .btn-submit {
            background-color: #9775FA;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-back {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #DE959C;
        }

        .current-image {
            margin-bottom: 10px;
        }

        img {
            max-width: 120px;
            border-radius: 8px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>تعديل التصنيف</h2>

        @if($errors->any())
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="name">اسم التصنيف:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>

            <label>الصورة الحالية:</label>
            <div class="current-image">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="صورة التصنيف">
                @else
                    لا توجد صورة
                @endif
            </div>

            <label for="image">تغيير الصورة (اختياري):</label>
            <input type="file" name="image" id="image">

            <button type="submit" class="btn-submit">تحديث</button>
        </form>

        <a href="{{ route('admin.categories.index') }}" class="btn-back">← العودة إلى التصنيفات</a>
    </div>
</body>
</html>
