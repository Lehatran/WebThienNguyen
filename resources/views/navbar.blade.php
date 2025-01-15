<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Insert title here</title>
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <!-- Thêm jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>

<body>
    <div class="container_navbar">
        <div class="navbar">
            <a href="http://localhost:8001/list_post" class="logo">
                <img src="/images/logo.png" />
                <p>ThienNguyen</p>
            </a>
            <div class="post">
                <button>
                    <p class="icon_add">+</p>
                    <p class="text_add">Them bai dang</p>
                </button>
            </div>
            <div class="input_search">
                <input id="searchInput" type="text" name="inf_search" placeholder="Nhap noi dung tim kiem" />
                <button id="searchButton">
                    <img src="/images/search.webp" />
                </button>
            </div>
            <div class="favorite">
                <button id="favoriteButton">
                    <div class="img_favorite">
                        <img src="/images/heart_border.svg">
                        <p class="count" id="favoriteCount">0</p> <!-- Số lượng yêu thích sẽ được hiển thị ở đây -->
                    </div>
                    <p>Favorite</p>
                </button>
            </div>
            <div class="account">
                <button class="name_account" id="accountButton">
                    <img src="/images/icon_account.png">
                    <p id="nameUser">{{ $user->name ?? 'Họ tên' }}</p>
                </button>
                <div class="button_intro hidden" id="dropdownMenu">
                    <div class="intro_inf">
                        <p class="text_register">Đăng bài </p>
                        <p class="text_des">Để được đăng tin cho thuê bất động sản</p>
                        <button>Đăng kí ngay</button>
                        <img src="/images/thiennguyen.png" />
                    </div>
                    <div class="buttons" *ngIf="actor == 'User'">
                        <button>
                            <i class="fa-solid fa-pen"></i>
                            <p>Đăng bài </p>
                        </button>
                        <button>
                            <i class="fa-solid fa-user"></i>
                            <p>Quản lý tài khoản</p>
                        </button>

                        <button class="logout" onclick="logout()">
                            <p>Đăng xuất</p>
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="buttons" *ngIf="actor == 'Admin'">
                        <button>
                            <i class="fa-solid fa-bars"></i>
                            <p>Duyệt bài đăng</p>
                        </button>
                        <button>
                            <i class="fa fa-address-card" aria-hidden="true"></i>
                            <p>Xem danh sách user</p>
                        </button>
                        <button>
                            <i class="fa fa-signal" aria-hidden="true"></i>
                            <p>Thống kê</p>
                        </button>
                        <button class="logout" onclick="logout()">
                            <p>Đăng xuất</p>
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
function logout() {
    const token = localStorage.getItem('token');
    if (!token) {
        alert('Bạn chưa đăng nhập hoặc token không hợp lệ!');
        window.location.href = '/login';
        return;
    }

    // Lấy CSRF token từ meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    fetch('/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken // Gửi CSRF token
            },
        })
        .then(response => response.json().then(data => ({
            status: response.status,
            data
        })))
        .then(({
            status,
            data
        }) => {
            if (status === 200) {
                localStorage.removeItem('token');
                alert('Đăng xuất thành công!');
                window.location.href = '/login';
            } else {
                console.error('Logout error:', data);
                alert(data.message || `Lỗi đăng xuất với mã trạng thái: ${status}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Không thể kết nối đến server.');
        });
}
</script>

<script>
$(document).ready(function() {
    // Hiển thị hoặc ẩn menu khi nhấn vào "Họ tên"
    $("#accountButton").click(function(e) {
        e.stopPropagation(); // Ngăn chặn sự kiện click lan ra ngoài
        $("#dropdownMenu").toggleClass("hidden"); // Hiển thị/Ẩn menu
    });

    // Đóng menu khi click ra ngoài
    $(document).click(function(event) {
        if (!$(event.target).closest("#accountButton, #dropdownMenu").length) {
            $("#dropdownMenu").addClass("hidden"); // Đóng menu
        }
    });
});
</script>

<script>
$(document).ready(function() {
    // Xử lý sự kiện click nút tìm kiếm
    $('#searchButton').click(function() {
        const query = $('#searchInput').val();
        window.location.href = `/list_post?query=${encodeURIComponent(query)}`;
    });
    const userId = {
        {
            $user - > id ?? 0
        }
    };
    const actor = {
        {
            $user - > role
        }
    };
    alert("actor: ", actor);

    // Gọi API để lấy số lượng yêu thích
    $.ajax({
        url: `/api/favorites/check?user_id=${userId}`, // Đảm bảo API endpoint đúng
        method: 'GET',
        success: function(response) {
            if (response.success) {
                // Cập nhật số lượng yêu thích vào thẻ <p>
                $('#favoriteCount').text(response.favorite_count || '0');
                $('#nameUser').text(response.user_name);
            } else {
                alert('Không thể lấy số lượng yêu thích');
            }
        },
        error: function() {
            alert('Đã có lỗi xảy ra khi gọi API');
        }
    });
});
$(document).ready(function() {
    // Điều hướng đến danh sách yêu thích
    $('#favoriteButton').click(function() {
        window.location.href = '/favorites';
    });
});
</script>