<?php

namespace App\Console\Commands;
use App\Models\Address;

use Illuminate\Console\Command;

class FetchAddressData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-address-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = storage_path('app/data/data.json');

        // Kiểm tra file có tồn tại không
        if (!file_exists($filePath)) {
            $this->error('JSON file not found at ' . $filePath);
            return 1;
        }

        // Đọc nội dung file
        $jsonContent = file_get_contents($filePath);

        // Chuyển dữ liệu JSON thành mảng
        $addresses = json_decode($jsonContent, true);

        if (is_array($addresses)) {
            foreach ($addresses as $city) {
                $provinceName = isset($city['Name']) ? $city['Name'] : null;

                foreach ($city['Districts'] as $district) {
                    $districtName = isset($district['Name']) ? $district['Name'] : null;

                    foreach ($district['Wards'] as $ward) {
                        $wardName = isset($ward['Name']) ? $ward['Name'] : null;

                        // Kiểm tra nếu wardName là null, gán giá trị mặc định hoặc bỏ qua
                        if ($wardName === null) {
                            continue; // Bỏ qua nếu không có ward
                        }

                        // Tạo dữ liệu vào bảng address
                        Address::create([
                            'province' => $provinceName,
                            'district' => $districtName,
                            'ward' => $wardName,
                        ]);
                    }
                }
            }

            $this->info('Address data has been imported successfully!');
        } else {
            $this->error('Invalid JSON format.');
            return 1;
        }

        return 0;
    }


}
