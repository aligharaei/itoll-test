<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos';
    protected $fillable = [
        'origin_lat',
        'origin_long',
        'origin_address',
        'sender_name',
        'sender_mobile',
        'destination_lat',
        'destination_long',
        'destination_address',
        'receiver_name',
        'receiver_mobile',
        'customer_id',
        'delivery_id',
        'status',
        'tracking_code'
    ];

    const READY_TO_DELIVER = 0;
    const ORIGIN_GET = 1;
    const CANCELED = 10;

    static function trackCodeGenerator($length = 6)
    {
        $random = "";
        srand((double)microtime() * 1000000);

        $data = "123456123456789712345678989";
        // $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz"; // if you need alphabatic also

        for ($i = 0; $i <= $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }

        return $random;

    }
}
