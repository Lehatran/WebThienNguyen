<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header class="register-header">

        <h1 class="logo">Leboncoin</h1>
    </header>
    <div class="register-container">

        <main class="register-content">
            <div class="register">
                <div class="form-container">
                    <h2>Tạo tài khoản Leboncoin của bạn</h2>

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Đã xảy ra lỗi!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" class="register-form">
                        @csrf

                        <div class="form-group">
                            <label for="name">Họ Tên *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="birthday">Ngày Sinh *</label>
                            <input type="date" name="birthday" id="birthday" required>
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Số Điện Thoại *</label>
                            <input type="text" name="phoneNumber" id="phoneNumber" value="{{ old('phoneNumber') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="userName">Tên Người Dùng *</label>
                            <input type="text" name="userName" id="userName" value="{{ old('userName') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật Khẩu *</label>
                            <div class="password-container">
                                <input type="password" name="password" id="password" required>
                                <i class="fas fa-eye-slash toggle-password" data-target="#password"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Xác Nhận Mật Khẩu *</label>
                            <div class="password-container">
                                <input type="password" name="password_confirmation" id="password_confirmation" required>
                                <i class="fas fa-eye-slash toggle-password" data-target="#password_confirmation"></i>

                            </div>
                        </div>
                        <button type="submit" class="btn-submit">Đăng ký</button>
                    </form>
                    <a class="hrlogin" href="{{ route('login.form') }}">Bạn đã có tài khoản? Đăng nhập</a>
                </div>
            </div>
            <div class="carousel">
                <div class="carousel-container">
                    <!-- Cột 1 (Cuộn nhanh) -->
                    <div class="column column-1">
                        <div class="items">
                            <div class="item" style="background-color:rgb(242, 181, 207)">
                                <img src="/images/icon_house.png" />
                            </div>
                            <div class="item" style="background-color:rgb(244, 225, 193)">
                                <img src="/images/icon_oto.png" />
                            </div>
                            <div class="item" style="background-color:rgb(195, 244, 252)">
                                <img src="/images/icon_phone.png" />
                            </div>
                        </div>
                    </div>
                    <!-- Cột 2 (Cuộn chậm) -->
                    <div class="column column-2">
                        <div class="items">
                            <div class="item" style="background-color:rgb(238, 226, 187)">
                                <img src="/images/icon_ao.png" />
                            </div>
                            <div class="item" style="background-color:rgb(182, 225, 233)">
                                <img src="/images/icon_book.png" />
                            </div>
                            <div class="item" style="background-color:rgb(197, 249, 192)">
                                <img src="/images/icon_dochoi.png" />
                            </div>
                        </div>
                    </div>
                    <!-- Cột 3 (Cuộn vừa) -->
                    <div class="column column-3">
                        <div class="items">
                            <div class="item" style="background-color:rgb(224, 248, 245)">
                                <img src="/images/icon_tui.png" />
                            </div>
                            <div class="item" style="background-color:rgb(250, 210, 208)">
                                <img src="/images/icon_ghe.png" />
                            </div>
                            <div class="item" style="background-color:rgb(249, 224, 180)">
                                <img src="/images/icon_oto.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>

<script>
document.querySelectorAll('.toggle-password').forEach(item => {
    item.addEventListener('click', function() {
        const targetInput = document.querySelector(this.dataset.target);
        const isPassword = targetInput.getAttribute('type') === 'password';
        targetInput.setAttribute('type', isPassword ? 'text' : 'password');
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});
</script>


<script>
$(document).ready(function() {
    $('.register-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: '{{ route("register") }}',
            data: formData,
            success: function(response) {
                // Hiển thị thông báo đăng ký thành công
                alert('Đăng ký thành công!');
                // Điều hướng sang trang đăng nhập
                window.location.href = '{{ route("login.form") }}';
            },
            error: function(xhr) {
                // Xử lý lỗi và hiển thị thông báo
                const errorResponse = xhr.responseJSON;
                if (errorResponse && errorResponse.errors) {
                    let errorMessages = 'Đăng ký thất bại:\n';
                    for (const key in errorResponse.errors) {
                        errorMessages += `- ${errorResponse.errors[key][0]}\n`;
                    }
                    alert(errorMessages);
                } else {
                    alert('Đã xảy ra lỗi không xác định. Vui lòng thử lại.');
                }
            }
        });
    });
});
</script>


<script>
// JavaScript để tạo cuộn tuần hoàn
document.querySelectorAll(".items").forEach((list) => {
    const items = Array.from(list.children);
    // Nhân đôi danh sách hình ảnh để tạo cuộn liên tục
    for (let i = 0; i < 9; i++) { // 9 lần sao chép thêm (tổng cộng 10 phần tử)
        items.forEach((item) => {
            const clone = item.cloneNode(true);
            list.appendChild(clone);
        });
    }
});
</script>