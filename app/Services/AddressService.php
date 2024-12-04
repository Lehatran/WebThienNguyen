<?php

namespace App\Services;

use App\Models\Address;

class AddressService
{
    // Lấy tất cả các địa chỉ
    public function getAllAddresses()
    {
        return Address::all();
    }

    // Lấy địa chỉ theo ID
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
