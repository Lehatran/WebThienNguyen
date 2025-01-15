<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa hồ sơ</title>
    <link rel="stylesheet" href="{{ asset('css/edit-profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="content">
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
                    <a href="{{ route('change.password.form')}}">Thay đổi mật khẩu</a>
                </li>

            </ul>
        </aside>





        <div class="profile-container">

            @if (session('success'))
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif
            <h2>Quản lý tài khoản</h2>

            <div class="tabs">
                <a href="{{ route('edit.profile.form') }}" class="tab active">Chỉnh sửa hồ sơ</a>
                <a class="tab" href="{{ route('change.password.form')}}">Thay đổi mật khẩu</a>

            </div>

            <div class="profile-form">
                <h3>Thông tin cá nhân</h3>

                <form action="{{ route('edit.profile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Họ và tên -->
                    <div class="form-group name-input">
                        <label for="fullName">Họ và tên</label>
                        <input type="text" id="fullName" name="name" value="{{ old('name', $user->name) }}" />
                        <div class="error-message">
                            @error('name') {{ $message }} @enderror
                        </div>
                    </div>

                    <hr style="width: 95%; border-top: 1px solid #d3d3d3; margin: 0 auto;" />

                    <h3 style="padding-top: 20px">Thông tin liên hệ</h3>
                    <div class="form-group contact-infor">
                        <div class="half-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="text" id="phone" name="phoneNumber"
                                value="{{ old('phoneNumber', $user->phoneNumber) }}" required minlength="10"
                                maxlength="10" />
                            <div class="error-message">
                                @error('phoneNumber') {{ $message }} @enderror
                            </div>
                        </div>
                        <div class="half-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                disabled />
                        </div>
                    </div>

                    <div class="save-button-container">
                        <button type="submit" class="save-button">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
