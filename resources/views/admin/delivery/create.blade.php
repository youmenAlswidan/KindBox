<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #FFFFFF;
      font-family: 'Segoe UI', sans-serif;
      min-height: 100vh;
      padding: 0;
      margin: 0;
      overflow-x: hidden;
      position: relative;
    }

    .main-container {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 60px 20px;
    }

    .card-wrapper {
  background-color: #fff;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
  border-radius: 30px;
  padding: 35px;
  max-width: 500px;
  width: 100%;
  position: relative;
  z-index: 1;
}


    .form-title {
      font-size: 30px;
      font-weight: bold;
      color: #9775FA;
      margin-bottom: 30px;
      text-align: center;
    }

    .form-label {
      font-weight: bold;
      color: #9775FA;
    }

    .form-control {
      border-radius: 12px;
      border: 1px solid #C3C3C3;
    }

    .form-control:focus {
      border-color: #9775FA;
      box-shadow: 0 0 0 0.25rem rgba(151, 117, 250, 0.25);
    }

    .btn-submit {
      background: linear-gradient(90deg, #9775FA, #DE959C);
      color: white;
      border: none;
      padding: 12px;
      border-radius: 25px;
      font-weight: bold;
      font-size: 16px;
      transition: 0.3s ease;
    }

    .btn-submit:hover {
      transform: scale(1.05);
      background: linear-gradient(90deg, #DE959C, #9775FA);
    }

    /* صور موزعة حول النموذج */
    .decor-image {
      position: absolute;
      z-index: 0;
      opacity: 0.13;
    }

    .decor-image.top-left {
      top: 30px;
      left: 20px;
      width: 120px;
    }

    .decor-image.top-right {
      top: 20px;
      right: 30px;
      width: 100px;
    }

    .decor-image.bottom-left {
      bottom: 20px;
      left: 30px;
      width: 110px;
    }

    .decor-image.bottom-right {
      bottom: 30px;
      right: 20px;
      width: 130px;
    }

    .decor-image.center-right {
      top: 50%;
      right: -80px;
      transform: translateY(-50%);
      width: 140px;
    }

    @media (max-width: 768px) {
      .card-wrapper {
        padding: 30px;
      }
      .decor-image {
        display: none;
      }
    }
  </style>
</head>
<body>

  <!-- صور حول النموذج -->
  <img src="https://cdn-icons-png.flaticon.com/512/8090/8090406.png" class="decor-image top-left" alt="دراجة">
  <img src="https://cdn-icons-png.flaticon.com/512/2972/2972185.png" class="decor-image center-right" alt="مندوب توصيل">
  <img src="https://cdn-icons-png.flaticon.com/512/854/854878.png" class="decor-image bottom-right" alt="سيارة">
  <img src="https://cdn-icons-png.flaticon.com/512/4140/4140047.png" class="decor-image top-right" alt="شخص">
  <img src="https://cdn-icons-png.flaticon.com/512/3123/3123490.png" class="decor-image bottom-left" alt="شخص آخر">

  <div class="main-container">
    <div class="card-wrapper">
      <div class="form-title">🚚 Create Delivery Account  </div>

      @if ($errors->any())
        <div class="alert alert-danger rounded-3">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.delivery.store') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label">First_Name </label>
          <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Last_Name </label>
          <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
        </div>

        <div class="mb-3">
          <label class="form-label"> Email</label>
          <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Phone_Number </label>
          <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
        </div>

        <div class="mb-3">
          <label class="form-label">Password </label>
          <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-4">
          <label class="form-label"> Password_Confirm </label>
          <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-submit w-100">Register Deleviry </button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
