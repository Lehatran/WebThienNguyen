<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/list-user.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>List users</title>

</head>

<body>
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper">
            <!-- Topbar -->
            <nav class="topbar">
                <a class="back" href="{{ route('edit.profile.form') }}">
                    <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> </a>
                <a href="#" class="nav-link">
                    <span>Hi! Admin</span>
                </a>
            </nav>

            <!-- Main Content -->
            <form method="GET" action="{{ route('admin.users') }}">
                <input type="text" name="search" placeholder="Tìm kiếm tên hoặc email" value="{{ request('search') }}">
                <button type="submit">Tìm kiếm</button>
            </form>
            <div class="container-fluid">
                <h1>Danh sách người dùng</h1>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Ngày sinh</th>
                            <th>Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody>@foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->birthday }}</td>
                            <td>{{ $user->phoneNumber }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const rowsPerPage = 5; // Số hàng mỗi trang
    const table = document.querySelector("table tbody");
    const rows = Array.from(table.querySelectorAll("tr"));
    const pagination = document.createElement("div");
    pagination.classList.add("pagination");

    // Tính tổng số trang
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    // Hiển thị hàng theo trang
    function displayPage(page) {
        table.innerHTML = ""; // Xóa hàng cũ
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        rows.slice(start, end).forEach(row => table.appendChild(row));

        // Đánh dấu nút active
        document.querySelectorAll(".pagination button").forEach((btn, index) => {
            btn.classList.toggle("active", index + 1 === page);
        });
    }

    // Tạo các nút phân trang
    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement("button");
        button.textContent = i;
        button.addEventListener("click", () => displayPage(i));
        pagination.appendChild(button);
    }

    // Gắn phân trang vào DOM
    document.querySelector(".container-fluid").appendChild(pagination);

    // Hiển thị trang đầu tiên
    displayPage(1);
});
</script>
