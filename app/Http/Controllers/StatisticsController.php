<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class StatisticsController extends Controller
{

    public function index()
    {
        // Gọi API từ service Category trên cổng 8002
        $categoriesResponse = Http::get('http://127.0.0.1:8002/api/categories');
        $categories = $categoriesResponse->json();

        // Gọi API từ service Product trên cổng 8001
        $productCountsResponse = Http::get('http://127.0.0.1:8001/api/products/count-by-category');
        $productCounts = $productCountsResponse->json();

        // Ghép nối dữ liệu
        $statistics = [];
        foreach ($categories as $category) {
            $count = collect($productCounts)->firstWhere('id_category', $category['id'])['total'] ?? 0;
            $statistics[] = [
                'category_name' => $category['name'],
                'product_count' => $count
            ];
        }

        return response()->json($statistics);
    }

     /**
     * Thống kê số lượng sản phẩm theo tháng và danh mục.
     */
    public function productByCategory(Request $request)
    {
        // Lấy tham số tháng và năm từ request
        $month = $request->input('month');
        $year = $request->input('year');

        // Kiểm tra nếu không có tháng hoặc năm thì trả về lỗi
        if (!$month || !$year) {
            return response()->json([
                'error' => 'Vui lòng cung cấp đầy đủ tháng và năm.'
            ], 400);
        }

        // Gọi API từ service Category trên cổng 8002 để lấy danh sách các danh mục
        $categoriesResponse = Http::get('http://127.0.0.1:8002/api/categories');
        $categories = $categoriesResponse->json();

        // Kiểm tra lỗi khi gọi API categories
        if ($categoriesResponse->failed()) {
            return response()->json([
                'error' => 'Lỗi khi gọi API categories.'
            ], 500);
        }

        // Gọi API từ service Product trên cổng 8001 để lấy số lượng sản phẩm theo tháng và danh mục
        $productCountsResponse = Http::get('http://127.0.0.1:8001/api/products/count-by-category-and-month', [
            'month' => $month,
            'year' => $year
        ]);
        $productCounts = $productCountsResponse->json();

        // Kiểm tra lỗi khi gọi API product counts
        if ($productCountsResponse->failed()) {
            return response()->json([
                'error' => 'Lỗi khi gọi API product counts.'
            ], 500);
        }

        // Kiểm tra xem $categories và $productCounts có chứa dữ liệu hay không
        if (empty($categories) || empty($productCounts)) {
            return response()->json([
                'error' => 'Không thể lấy dữ liệu từ các API categories hoặc product counts.'
            ], 500);
        }

        // Ghép nối dữ liệu: Tạo mảng thống kê sản phẩm theo danh mục
        $statistics = [];
        foreach ($categories as $category) {
            // Tìm số lượng sản phẩm trong danh mục này
            $count = collect($productCounts)->firstWhere('id_category', $category['id']);
            $count = $count ? $count['total'] : 0;

            // Thêm kết quả vào mảng thống kê
            $statistics[] = [
                'category_name' => $category['name'],
                'product_count' => $count
            ];
        }

        // Trả về kết quả thống kê
        return response()->json([
            'month' => $month,
            'year' => $year,
            'statistics' => $statistics
        ]);
    }


}