<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>تعديل حساب الدليفري</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background: #f7f7f7;
    margin: 0;
    padding: 20px;
  }

  .container {
    display: flex;
    gap: 10px;
    justify-content: center;
    align-items: flex-start;
    flex-wrap: wrap;
  }

  .image-box {
    flex: 0 0 300px;
  }

  .image-box img {
    width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .form-box {
    flex: 0 0 350px;
    background: white;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }

  .form-box h2 {
    margin-top: 0;
    margin-bottom: 20px;
    font-weight: bold;
    color: #444;
  }

  .form-group {
    margin-bottom: 15px;
  }

  label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #333;
  }

  input[type="text"], input[type="email"], input[type="password"] {
    width: 100%;
    padding: 8px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
  }

  button {
    width: 100%;
    padding: 10px 0;
    background: #9775FA;
    border: none;
    border-radius: 20px;
    color: white;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
  }

  button:hover {
    background: #7a5edb;
  }

  @media(max-width: 700px) {
    .container {
      flex-direction: column;
      align-items: center;
    }

    .image-box, .form-box {
      flex: 0 0 100%;
      max-width: 400px;
    }

    .image-box {
      margin-bottom: 20px;
    }
  }
</style>
</head>
<body>

<div class="container">
  <div class="image-box">
    <img src="https://i.postimg.cc/CLrYMP56/download-3-removebg-preview.png" alt="صورة الدليفري" />
  </div>

  <div class="form-box">
    <h2>تعديل حساب الدليفري</h2>

    <form action="{{ route('admin.delivery.update', $delivery->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="first_name">الاسم الأول</label>
        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $delivery->first_name) }}" required />
      </div>

      <div class="form-group">
        <label for="last_name">الاسم الأخير</label>
        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $delivery->last_name) }}" required />
      </div>

      <div class="form-group">
        <label for="email">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" value="{{ old('email', $delivery->email) }}" required />
      </div>

      <div class="form-group">
        <label for="phone_number">رقم الهاتف</label>
        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $delivery->phone_number) }}" />
      </div>

      <div class="form-group">
        <label for="password">كلمة المرور الجديدة</label>
        <input type="password" id="password" name="password" />
      </div>

      <div class="form-group">
        <label for="password_confirmation">تأكيد كلمة المرور</label>
        <input type="password" id="password_confirmation" name="password_confirmation" />
      </div>

      <button type="submit">تحديث</button>
    </form>
  </div>
</div>

</body>
</html>
