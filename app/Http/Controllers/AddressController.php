<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function getListAddress()
    {
        $address = Address::all();
        return response()->json($address);
    }
    
    public function getAddressById($id)
    {
        $address = Address::find($id);
        if ($address) {
            return [
                'province' => $address->province,
                'district' => $address->district,
                'ward' => $address->ward,
            ];
        }
        return null;
    }
}
