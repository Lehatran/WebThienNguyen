<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
class ProductsController extends Controller
{

    public function countProductsByCategory()
    {
        // Truy vấn số lượng sản phẩm theo từng id_category
        $productCounts = Products::select('id_category')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('id_category')
            ->get();

        return response()->json($productCounts);
    }


       // Đếm số lượng sản phẩm theo tháng cụ thể
    public function countProductsByMonth(Request $request)
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
        if (!is_numeric($month) || $month < 1 || $month > 12 || !is_numeric($year)) {
            return response()->json([
                'error' => 'Tháng phải từ 1 đến 12 và năm phải là số hợp lệ.'
            ], 400);
        }

        // Truy vấn đếm số lượng sản phẩm theo tháng và năm
        $productCount = Products::whereMonth('create_day', $month)
            ->whereYear('create_day', $year)
            ->count();

        return response()->json([
            'month' => $month,
            'year' => $year,
            'total_products' => $productCount
        ]);
    }
     /**
     * Lấy số lượng sản phẩm theo danh mục và tháng
     */
    public function countByCategoryAndMonth(Request $request)
    {
        // Lấy tháng và năm từ request
        $month = $request->input('month');
        $year = $request->input('year');

        // Lọc sản phẩm theo tháng, năm và nhóm theo danh mục
        $productCounts = Products::select('id_category', \DB::raw('COUNT(id) as total'))
            ->whereYear('create_day', '=', $year)
            ->whereMonth('create_day', '=', $month)
            ->groupBy('id_category')
            ->get();

        // Trả về kết quả dưới dạng JSON
        return response()->json($productCounts);
    }
}
