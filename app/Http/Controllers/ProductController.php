<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Services\AddressService;
use App\Services\CategoryService;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CategoryController;



class ProductController extends Controller
{
    protected $addressController;
    protected $categoryController;

    // Inject AddressService vào Controller
    public function __construct(AddressController $addressController,  CategoryController $categoryController)
    {
        $this->addressController = $addressController;
        $this->categoryController = $categoryController;
    }

    public function index()
    {
        try {
            // Lấy tất cả sản phẩm
            $products = Product::all();

            // Duyệt qua từng sản phẩm để lấy địa chỉ 
            foreach ($products as $product) {
                
                $address = $this->addressController->getAddressById($product->id_address ?? null);
                $category = $this->categoryController->getCategoryById($product->id_category ?? null);

                // Nếu có địa chỉ, thêm vào sản phẩm
                $product->address = $address ?? null;
                $product->category = $category ?? null;
            }

            // Trả về danh sách sản phẩm cùng địa chỉ (nếu có)
            return response()->json([
                'status' => true,
                'message' => "Danh sách sản phẩm",
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            // Trả về lỗi nếu có ngoại lệ
            return response()->json([
                'status' => false,
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string|max:191',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'id_category' => 'required|integer',
            'create_day' => 'required|date',
            'id_address' => 'nullable|integer',
            'id_user' => 'nullable|integer',
            'img' => 'nullable|string|max:191',
            'isExist' => 'required|boolean',
        ]);
        
        $product = Product::create($request->all());
        return response()->json([
            'message' => 'Sản phẩm đã được tạo thành công',
            'data' => $product
        ], 201);
    }

    public function show(string $id)
    {
        // Lấy thông tin sản phẩm
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json(['message' => 'Sản phẩm không tồn tại'], 404);
        }

        $address = $this->addressController->getAddressById($product->id_address);
        $category = $this->categoryController->getCategoryById($product->id_category);

        // Nếu có địa chỉ, thêm vào dữ liệu trả về
        if ($address) {
            $product->address = $address;
        } else {
            $product->address = null;  // Nếu không có địa chỉ, trả về null
        }

        if ($category) {
            $product->category = $category;
        } else {
            $product->category = null;  
        }

        // Trả về thông tin sản phẩm cùng với địa chỉ
        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => $product->price,
            'status' => $product->status,
            'id_category' => $product->id_category,
            'create_day' => $product->create_day,
            'id_address' => $product->id_address,
            'id_user' => $product->id_user,
            'img' => $product->img,
            'isExist' => $product->isExist,
            'address' => $product->address,
            'category' => $product->category,
        ], 200);
    }


    public function update(Request $request, Product $product)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'img' => 'nullable|string',
            'isExist' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            $arr = [
                'success' => false,
                'message' => 'Lỗi kiểm tra dữ liệu',
                'data' => $validator->errors(),
            ];
            return response()->json($arr, 200);
        }

        // Cập nhật sản phẩm
        $product->update($input);

        $arr = [
            'status' => true,
            'message' => "Sản phẩm cập nhật thành công",
            'data' => new ProductResource($product),
        ];

        return response()->json($arr, 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $arr = [
            'status' => true,
            'message' => 'Sản phẩm đã được xóa',
            'data' => [],
        ];
        return response()->json($arr, 200);
    }

    public function countProductsByCategory()
    {
        // Truy vấn số lượng sản phẩm theo từng id_category
        $productCounts = Product::select('id_category')
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
        $productCount = Product::whereMonth('create_day', $month)
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
        $productCounts = Product::select('id_category', \DB::raw('COUNT(id) as total'))
            ->whereYear('create_day', '=', $year)
            ->whereMonth('create_day', '=', $month)
            ->groupBy('id_category')
            ->get();

        // Trả về kết quả dưới dạng JSON
        return response()->json($productCounts);
    }
    

}
