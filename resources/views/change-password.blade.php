<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <link rel="stylesheet" href="{{ asset('css/change-password.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="content">
        <!-- <div class="app-container"> -->
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="profile-section">
                <div>Hi! {{ $user->name ?? 'Họ tên' }}</div>

            </div>
            <ul class="sidebar-links">
                <li>
                    <i class="fa fa-user" aria-hidden="true"></i>
                    Quản lý tài khoản
                </li>
                <li class="sub-link" routerLinkActive="active">
                    <a href="{{ route('edit.profile.form') }}">Chỉnh sửa hồ sơ</a>
                </li>
                <li class="sub-link" routerLinkActive="active">
                    <a href="{{ route('change.password.form') }}">Thay đổi mật khẩu</a>
                </li>

            </ul>
        </aside>

        <div class="profile-container">
            <h2>Quản lý tài khoản</h2>
            <div class="tabs">
                <a class="tab" href="{{ route('edit.profile.form') }}">Chỉnh sửa hồ sơ</a>
                <a class="tab active" href="{{ route('change.password.form')}}">Thay đổi mật khẩu</a>

            </div>
            @if (session('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <form action="{{ route('change.password') }}" method="POST">
                @csrf
                <!-- @method('PUT') -->
                <h3>Đổi mật khẩu</h3>

                <!-- Current Password -->
                <label>Mật khẩu hiện tại</label>
                <div class="password-input">
                    <input type="password" id="current-password" name="current_password" required />
                    <i class="fa fa-eye-slash" id="toggle-current-password"></i>
                    @error('current_password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- New Password -->
                <label>Mật khẩu mới</label>
                <div class="password-input">
                    <input type="password" id="new-password" name="new_password" required />
                    <i class="fa fa-eye-slash" id="toggle-new-password"></i>
                    <!-- @error('new_password')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror -->
                </div>

                <!-- Confirm Password -->
                <label>Xác nhận mật khẩu</label>
                <div class="password-input">
                    <input type="password" id="confirm-password" name="new_password_confirmation" required />
                    <i class="fa fa-eye-slash" id="toggle-confirm-password"></i>
                    <!-- @error('new_password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror -->
                </div>

                <div class="save-button-container">
                    <button class="save-button" type="submit">Lưu thay đổi</button>
                </div>
            </form>

        </div>
        <!-- </div> -->
    </div>

    <script>
    // Chức năng để mở hoặc đóng mắt
    document.getElementById('toggle-current-password').addEventListener('click', function() {
        const currentPasswordInput = document.getElementById('current-password');
        const icon = this;

        if (currentPasswordInput.type === 'password') {
            currentPasswordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            currentPasswordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });

    document.getElementById('toggle-new-password').addEventListener('click', function() {
        const newPasswordInput = document.getElementById('new-password');
        const icon = this;

        if (newPasswordInput.type === 'password') {
            newPasswordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            newPasswordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });

    document.getElementById('toggle-confirm-password').addEventListener('click', function() {
        const confirmPasswordInput = document.getElementById('confirm-password');
        const icon = this;

        if (confirmPasswordInput.type === 'password') {
            confirmPasswordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            confirmPasswordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
    </script>
</body>

</html>