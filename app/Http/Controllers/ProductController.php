<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Support\Facades\Validator;
use App\Services\AddressService;



class ProductController extends Controller
{
    protected $addressService;

    // Inject AddressService vào Controller
    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    // Sử dụng service trong một phương thức của controller
    public function getAddress($id)
    {
        $address = $this->addressService->getAddressById($id);

        if ($address) {
            return response()->json(['status' => true, 'data' => $address], 200);
        }

        return response()->json(['status' => false, 'message' => 'Địa chỉ không tìm thấy'], 404);
    }

    public function index()
    {
        try {
            // Lấy tất cả sản phẩm
            $products = Product::all();

            // Duyệt qua từng sản phẩm để lấy địa chỉ (nếu có AddressService)
            foreach ($products as $product) {
                // Lấy địa chỉ từ AddressService (nếu có)
                $address = $this->addressService->getAddressById($product->id_address ?? null);

                // Nếu có địa chỉ, thêm vào sản phẩm
                $product->address = $address ?? null;
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

    // Lấy địa chỉ từ AddressService
    $address = $this->addressService->getAddressById($product->id_address);

    // Nếu có địa chỉ, thêm vào dữ liệu trả về
    if ($address) {
        $product->address = $address;
    } else {
        $product->address = null;  // Nếu không có địa chỉ, trả về null
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
        'address' => $product->address
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
            'id_category' => 'nullable|integer',
            'id_address' => 'nullable|integer',
            'id_user' => 'nullable|integer',
            'img' => 'nullable|string',
            'isExist' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            $arr = [
                'success' => false,
                'message' => 'Lỗi kiểm tra dữ liệu',
                'data' => $validator->errors()
            ];
            return response()->json($arr, 200);
        }
        $product->name = $input['name'];
        $product->price = $input['price'];
        $product->save();
        $arr = [
            'status' => true,
            'message' => "Sản phẩm cập nhật thành công",
            'data' => new ProductResource($product)
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

    

}
