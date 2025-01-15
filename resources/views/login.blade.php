<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header class="register-header">
        <!-- <a href="#" class="back-button">←</a> -->
        <h1 class="logo">Leboncoin</h1>
    </header>
    <div class="register-container">

        <main class="register-content">
            <div class="register">
                <div class="form-container">
                    <h2>Đăng nhập tài khoản Leboncoin của bạn</h2>



                    <form action="{{ route('login') }}" method="POST" class="register-form">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Mật Khẩu *</label>
                            <div class="password-container">
                                <input type="password" name="password" id="password" required>
                                <i class="fas fa-eye-slash toggle-password" id="toggle-password"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit">Đăng nhập</button>
                    </form>
                    <a class="hrlogin" href="{{ route('register.form') }}">Bạn chưa có tài khoản? Đăng ký</a>
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
document.addEventListener("DOMContentLoaded", function() {
    const togglePassword = document.getElementById("toggle-password");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function() {
        // Chuyển đổi thuộc tính `type` giữa password và text
        const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);

        // Thay đổi icon mắt
        togglePassword.classList.toggle("fa-eye");
        togglePassword.classList.toggle("fa-eye-slash");
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
            url: '{{ route("login") }}',
            data: formData,
            success: function(response) {
                if (response.access_token) {
                    localStorage.setItem('token', response.access_token);
                    alert('Đăng nhập thành công');
                    window.location.href = '{{ route("edit.profile.form") }}';
                } else {
                    alert("Đăng nhập thất bại");
                }
            },
            error: function(xhr) {
                const errorResponse = xhr.responseJSON;
                if (errorResponse && errorResponse.message) {
                    alert(errorResponse.message);
                } else {
                    alert('Đăng nhập thất bại, vui lòng thử lại.');
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

<script>
// Kiểm tra thông báo từ sessionStorage
document.addEventListener("DOMContentLoaded", function() {
    const notification = "{{ session('notify') }}"; // Lấy thông báo từ session
    if (notification) {
        // Hiển thị thông báo trình duyệt
        if (Notification.permission === "granted") {
            new Notification("Thông báo", {
                body: notification
            });
        } else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    new Notification("Thông báo", {
                        body: notification
                    });
                }
            });
        } else {
            // Hiển thị thông báo fallback (nếu không được phép)
            alert(notification);
        }
    }
});
</script>