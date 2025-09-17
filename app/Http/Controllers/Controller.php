<?php

namespace App\Http\Controllers;
use App\Mail\DynamicEmail;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Photography;
use App\Models\Category;
use App\Models\TempCart;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Storage;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function sendOrderForm($type = '', $order_ids = [], $other = [])
    {

        $emailTemplate = $type;
        if ($emailTemplate == "Order Details") 
            {
            $orderId = $order_ids;
           
           
            $emailAddress = $other;

          
             $photos = TempCart::where('temp_carts.guest_id', $orderId)
    ->where('temp_carts.order_status', 'pending')
    ->join('photographies', 'temp_carts.photo_id', '=', 'photographies.id')
    ->join('order_details', 'temp_carts.guest_id', '=', 'order_details.guest_id')
    ->select(
        'temp_carts.*',
        'photographies.back_image',
        'photographies.title',
        'order_details.fname',
        'order_details.lname',
        'order_details.total_amount',
        'order_details.order_status'
    )
    ->get();

   



    $orderInfo = '';
if ($photos->isNotEmpty()) {
    $order = $photos->first(); 
    $orderInfo = "
        <h4 style='margin:10px 0;'>Order Summary</h4>
        <table style='width:100%; border-collapse:collapse;'>
            <tr>
                <td style='padding:8px; font-weight:bold;'>Name:</td>
                <td style='padding:8px;'>{$order->fname} {$order->lname}</td>
            </tr>
            <tr>
                <td style='padding:8px; font-weight:bold;'>Total Amount:</td>
                <td style='padding:8px;'>₹{$order->total_amount}</td>
            </tr>
            <tr>
                <td style='padding:8px; font-weight:bold;'>Order Status:</td>
                <td style='padding:8px;'>{$order->order_status}</td>
            </tr>
        </table>
    ";
}

$attachments = [];
foreach ($photos as $photo) {
    if ($photo->back_image && Storage::disk('public')->exists($photo->back_image)) {
        $attachments[] = public_path(Storage::url($photo->back_image)); // only path inside 'public' disk
    }


}

           $template = "
<body style='font-family: Arial, sans-serif; margin:0; padding:0; background-color:#f4f4f4;'>
    <div style='background-color:#ffffff; max-width:600px; margin:20px auto; border:1px solid #dddddd;'>
        
        <!-- Header -->
        <div style='background-color:#17365d; color:white; text-align:center; padding:15px;'>
            <h3 style='margin:0; font-size:24px;'>Order Details</h3>
        </div>

        <!-- Order Info -->
        <div style='padding:20px;'>
            {$orderInfo}
        </div>


        <!-- Footer -->
        <div style='background-color:#f1f1f1; text-align:center; padding:10px; font-size:12px; color:#777;'>
            Thank you for your purchase!
        </div>
    </div>
</body>
";

            
              \Mail::to(trim($emailAddress))->send(new DynamicEmail("Order Details", $template, $attachments));
              

        }     
    }
}
