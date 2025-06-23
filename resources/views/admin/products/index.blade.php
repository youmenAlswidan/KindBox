<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>عرض المنتجات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F7F7F7;
            padding: 20px;
        }
        .product-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .product-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        .product-details {
            flex-grow: 1;
        }
        .product-details h3 {
            margin: 0 0 10px;
        }
        .shop-name {
            color: #555;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>قائمة المنتجات</h1>

    @foreach($products as $product)
        <div class="product-card">
            {{-- صورة المنتج --}}
            <img src="{{ asset('storage/' . $product->image) }}" alt="صورة المنتج">

            <div class="product-details">
                {{-- اسم المنتج --}}
                <h3>{{ $product->product_name }}</h3>

                {{-- اسم المتجر المرتبط --}}
                <p class="shop-name">المتجر: {{ $product->shop?->shop_name ?? 'غير معروف' }}</p>

                {{-- السعر --}}
                <p>السعر: {{ $product->price }} دينار</p>
            </div>

            {{-- زر حذف إن أحببت --}}
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف المنتج؟');">
                @csrf
                @method('DELETE')
                <button style="background:red; color:white; padding: 5px 10px; border-radius: 6px;">حذف</button>

            </form>
            <a href="{{ route('admin.products.show', $product->id) }}" style="background:#9775FA; color:white; padding: 5px 10px; border-radius: 6px; text-decoration: none; margin-left: 10px;">
    عرض التفاصيل
</a>

        </div>
    @endforeach

</body>
</html>
