<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    const NEW_DELIVERY_REQUEST = 0;
    const READY_TO_DELIVER = 1;
    const ACCEPT_BY_DELIVERY = 2;
    const ORIGIN_PICKUP = 3;
    const ARRIVE_TO_DESTINATION = 4;
    const COMPLETE = 5;
    const CANCELED = 10;

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

    static function translateStatus($status)
    {
        switch ($status) {
            case Cargo::NEW_DELIVERY_REQUEST:
                return 'new delivery request';
            case Cargo::READY_TO_DELIVER:
                return 'ready to deliver';
            case Cargo::ACCEPT_BY_DELIVERY;
                return 'accept by delivery';
            case Cargo::ORIGIN_PICKUP:
                return 'picked up from origin';
            case Cargo::ARRIVE_TO_DESTINATION:
                return 'delivery arrived to destination';
            case Cargo::COMPLETE:
                return 'cargo successfully delivered';
            case Cargo::CANCELED:
                return 'cargo canceled';
            default:
                echo 'unknown status';
        }

    }
}
