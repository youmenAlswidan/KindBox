<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تفاصيل المنتج</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F0F0F0;
            padding: 30px;
        }
        .product-details {
            background: white;
            border-radius: 12px;
            padding: 20px;
            max-width: 700px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        img {
            width: 250px;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        h2 {
            color: #333;
        }
        .back-button {
            margin-top: 30px;
            display: inline-block;
            background-color: #777;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>

    <div class="product-details">
        <h2>{{ $product->product_name }}</h2>
        <img src="{{ asset('storage/' . $product->image) }}" alt="صورة المنتج">

        <p><strong>السعر:</strong> {{ $product->price }} دينار</p>
        <p><strong>الوصف:</strong> {{ $product->description }}</p>

        <hr>

        <h3>معلومات المتجر:</h3>
        <p><strong>اسم المتجر:</strong> {{ $product->shop?->shop_name }}</p>
        <p><strong>رقم الهاتف:</strong> {{ $product->shop?->phone_number }}</p>
        <p><strong>الموقع:</strong> {{ $product->shop?->location }}</p>

        @if($product->shop?->image_shop)
            <img src="{{ asset('storage/' . $product->shop->image_shop) }}" alt="صورة المتجر">
        @endif

        <a href="{{ route('admin.products.index') }}" class="back-button">رجوع إلى القائمة</a>
    </div>

</body>
</html>

