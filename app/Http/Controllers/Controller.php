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
use App\Models\Contact;
use App\Models\Setting;
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
                'photographies.is_richard_photo',
                'photographies.price',
                'photographies.discount_price',
                'photographies.title',
                'order_details.fname',
                'order_details.lname',
                'order_details.total_amount',
                'order_details.order_status'
            )
            ->get();

           

            
            $setting = Setting::first();
           

   
            $orderInfo = '';
            if ($photos->isNotEmpty()) 
            {
                $order = $photos->first(); 

                //  $total_amount = 0;
                // foreach ($photos as $photo_info) 
                // {
                //     if($photo_info->is_richard_photo == "Yes")
                //     {
                //         if($photo_info->discount_price !='')
                //         {
                //             $total_amount += $photo_info->discount_price;
                //         }
                //         else
                //         {
                //             $total_amount += $photo_info->price;
                //         }


                //     }
                //     else
                //         {
                //             $total_amount = $order->total_amount;
                //         }    
                // }


                 
                $orderInfo = "
                    <h4 style='margin:10px 0;'>Order Summary</h4>
                    <table style='width:100%; border-collapse:collapse;'>
                        <tr>
                            <td style='padding:8px; font-weight:bold;'>Name:</td>
                            <td style='padding:8px;'>{$order->fname} {$order->lname}</td>
                        </tr>
                        
                        <tr>
                            <td style='padding:8px; font-weight:bold;'>Order Status:</td>
                            <td style='padding:8px;'>{$order->order_status}</td>
                        </tr>

                            <tr>
                                <td style='padding:8px; font-weight:bold;'>Total Amount:</td>
                                <td style='padding:8px;'><span style='margin-right:2px;'>$</span><span>{$order->total_amount}</span></td>
                            </tr>


                    </table>
                ";
            }

$richardProducts = $photos->where('is_richard_photo', 'Yes');

$attachments = [];
$richaredphotos = [];
foreach ($photos as $photo) {
    if ($photo->back_image && Storage::disk('public')->exists($photo->back_image)) {
        
           
            $attachments[] = public_path(Storage::url($photo->back_image)); // only path inside 'public' disk
            
             if($photo->is_richard_photo == "Yes")
            {
                $richaredphotos[] = public_path(Storage::url($photo->back_image)); // only path inside 'public' disk 
            }
        
        
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

            
              \Mail::to(trim($emailAddress))->send(new DynamicEmail("Your Order Details", $template, $attachments));
              
              if(!empty($setting->admin_email))
              {
                    \Mail::to(trim($setting->admin_email))->send(new DynamicEmail("New Order Received", $template, $attachments));
              } 

              if(!empty($setting->partner_email))
              {
                    \Mail::to(trim($setting->partner_email))->send(new DynamicEmail("New Order Received", $template, $attachments));
              } 

             // Send to Printify email (only Richard products)
            if ($richardProducts->isNotEmpty() && !empty($setting->printify_email)) {
                \Mail::to(trim($setting->printify_email))
                    ->send(new DynamicEmail("Order Details", $template, $richaredphotos));
            }


        }     
    }


    /*----- Contact Form Send Mail -----*/
    public function sendContactForm($type = '', $job_ids = [], $other = [])
    {

        $emailTemplate = $type;
        if ($emailTemplate == "Contact Us") 
            {
            $ContactId = $job_ids;
           
            $emailAddress = $other;

            $contactInfo = Contact::where('id',$ContactId)->first(); 
             
           



           $template = "
<body style='font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;'>
    <div style='background-color: #ffffff; max-width: 600px; margin: 20px auto; padding: 0; border: 1px solid #dddddd;'>
        
        <!-- Header -->
        <div style='background-color: #17365d; color: white; text-align: center; padding: 15px;'>
            <h3 style='margin: 0; font-size: 24px;'>Contact Us Form</h3>
        </div>

        <!-- Content -->
        <div style='padding: 20px;'>
            <table style='width: 100%; border-collapse: collapse;'>
                <tr>
                    <td style='padding: 8px; font-weight: bold; color: #555555; width: 30%;'>Name</td>
                    <td style='padding: 8px; color: #222222;'>{$contactInfo->name}</td>
                </tr>
                <tr>
                    <td style='padding: 8px; font-weight: bold; color: #555555;'>Email</td>
                    <td style='padding: 8px; color: #222222;'>{$contactInfo->email}</td>
                </tr>
              
                <tr>
                    <td style='padding: 8px; font-weight: bold; color: #555555;'>Message</td>
                    <td style='padding: 8px; color: #222222;'>{$contactInfo->message}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div style='background-color: #f1f1f1; text-align: center; padding: 10px; font-size: 12px; color: #777777;'>
            This message was sent from your website's contact form.
        </div>
    </div>
</body>
";

           
           

            
              \Mail::to(trim($emailAddress))->send(new DynamicEmail("Contact Us Form.", $template, $other = ''));


        }     
    }

}
