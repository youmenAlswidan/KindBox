{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <title>لوحة تحكم الأدمن</title>
    <style>
        /* Reset */
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #F8F8F8;
            color: #333;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #9775FA;
            color: white;
            padding: 20px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0; /* لأن الصفحة بالـ RTL */
        }
        .sidebar h2 {
            margin-bottom: 20px;
            font-size: 1.6rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 10px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        .sidebar a:hover {
            background-color: #7B5DE3;
        }
        /* Main content */
        .main-content {
            margin-right: 250px; /* تترك مساحة للـ sidebar */
            padding: 30px;
            flex-grow: 1;
        }
        .header {
            background-color: #9775FA;
            color: white;
            padding: 15px;
            text-align: center;
            margin-bottom: 30px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>القائمة الرئيسية</h2>
        <a href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
        <a href="{{ route('admin.categories.index') }}">إدارة التصنيفات</a>
        <a href="{{ route('admin.delivery.index') }}">إدارة الدليفري</a>
        <a href="{{ route('admin.documents.index') }}">مراجعة الوثائق</a>
        <a href="{{ route('admin.products.index') }}">عرض المنتجات</a>

        <!-- أضف روابط أخرى حسب الحاجة -->
    </div>

    <div class="main-content">
        <div class="header">
            <h1>مرحباً بك في لوحة تحكم الأدمن</h1>
        </div>
        <p>هنا يمكنك إدارة النظام.</p>
    </div>

</body>
</html>
