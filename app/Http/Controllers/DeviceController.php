<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devices;
use Illuminate\Validation\ValidationException;

class DeviceController extends Controller
{
    public function verifyDevice(Request $request)
    {
        $this->validate($request, [
            'api_key' => '',
            'imei_1' => '',
            'imei_2' => '',
        ]);

        // Check if the API key is valid
        if ($request->api_key !== 'KJHIUH9796932LHPAPOEOB09876QWERTYUIOP') {
            return response()->json(['responseCode' => 401, 'message' => 'Unauthorized Request'], 401);
        }

        // Check if both IMEIs are provided
        if (empty($request->imei_1) || empty($request->imei_2)) {
            return response()->json(['responseCode' => 406, 'message' => 'Invalid or empty IMEI'], 406);
        }

        // Check if IMEI lengths are valid
        if (strlen($request->imei_1) < 14 || strlen($request->imei_1) > 15) {
            return response()->json(['responseCode' => 407, 'message' => 'Invalid IMEI 1 length'], 407);
        }


        if (strlen($request->imei_2) < 14 || strlen($request->imei_2) > 15) {
            return response()->json(['responseCode' => 407, 'message' => 'Invalid IMEI 2 length'], 407);
        }
        // If validation passes, you can perform further logic here
        // For example, check if the device IMEIs exist in the database
        
        $device = Devices::where('imei_1', $request->imei_1)
                        ->where('imei_2', $request->imei_2)
                        ->first();

        if ($device) {
            return response()->json([
                'status' => '1',
                'responseCode' => 200,
                'message' => 'Operation Successful',
                'serial_no' => $device->serial_number,
                'color' => $device->color,
            ], 200);
        } else {
            return response()->json(['responseCode' => 404, 'message' => 'Resource Not found'], 404);
        }
    }
}