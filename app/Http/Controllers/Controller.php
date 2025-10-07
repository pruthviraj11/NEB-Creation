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
use App\Models\CreativeArt;
use App\Models\BulkPurchase;
use App\Models\GiftProduct;
use App\Models\ProductVarient;
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
                'order_details.order_type',
                'order_details.transaction_id',
                'order_details.total_amount',
                'order_details.order_status'
            )
            ->get();

           

           

            
            $setting = Setting::first();
           

   
            $orderInfo = '';
            if ($photos->isNotEmpty()) 
            {
                $order = $photos->first(); 
                 
                $orderInfo = "
                    <h4 style='margin:10px 0;'>Order Summary</h4>
                    <table style='width:100%; border-collapse:collapse;'>
                        <tr>
                            <td style='padding:8px; font-weight:bold;'>Name:</td>
                            <td style='padding:8px;'>{$order->fname} {$order->lname}</td>
                        </tr>

                        <tr>
                            <td style='padding:8px; font-weight:bold;'>Transaction Id:</td>
                            <td style='padding:8px;'>{$order->transaction_id}</td>
                        </tr>

                        <tr>
                            <td style='padding:8px; font-weight:bold;'>Payment Mode:</td>
                            <td style='padding:8px;'>{$order->order_type}</td>
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

            $productDetails = "
<h4 style='color:#17365d;'>Product Details</h4>
<table style='width:100%; border-collapse:collapse;'>
    <tr style='background-color:#f1f1f1;'>
        <th style='padding:8px; text-align:left; border:1px solid #ddd;'>#</th>
        <th style='padding:8px; text-align:left; border:1px solid #ddd;'>Title</th>
    </tr>
";

$count = 1;
foreach ($photos as $photo) {

    $productDetails .= "
        <tr>
            <td style='padding:8px; border:1px solid #ddd; vertical-align:top;'>{$count}</td>
            <td style='padding:8px; border:1px solid #ddd;'>
                <strong>{$photo->title}</strong>
    ";

    // --- If Creative Art Exists ---
    if ($photo->is_creative_art == "yes" && !empty($photo->creative_info)) {
        $cdata = explode(",", $photo->creative_info);
        $creativeItems = CreativeArt::whereIn('id', $cdata)->get();

        if ($creativeItems->count() > 0) {
            $productDetails .= "
                <div style='margin-top:10px;'>
                    <p style='margin:5px 0; font-weight:bold; color:#17365d;'>Creative Arts:</p>
                    <table style='width:90%; margin-left:10px; border-collapse:collapse; font-size:14px;'>
                        <tr style='background-color:#fafafa;'>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Title</th>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Price</th>
                        </tr>
            ";

            foreach ($creativeItems as $creative) {
                $productDetails .= "
                    <tr>
                        <td style='padding:6px; border:1px solid #ddd;'>{$creative->title}</td>
                        <td style='padding:6px; border:1px solid #ddd;'>$" . number_format($creative->price, 2) . "</td>
                    </tr>
                ";
            }

            $productDetails .= "</table></div>";
        }
    }

    /*----- If Bulk product Exits -----*/
   
    if ($photo->is_bulk_purchase == "yes" && !empty($photo->bulk_info)) {
        
        $cartexplode = explode(",", $photo->extra_bulk);
        $cartexplode = array_map('trim', $cartexplode); 
        
        
        $bulkdata = explode(",", $photo->bulk_info);
        $bulkItems = BulkPurchase::whereIn('id', $bulkdata)->get();

        if ($bulkItems->count() > 0) {
            $productDetails .= "
                <div style='margin-top:10px;'>
                    <p style='margin:5px 0; font-weight:bold; color:#17365d;'>Bulk Purchase:</p>
                    <table style='width:90%; margin-left:10px; border-collapse:collapse; font-size:14px;'>
                        <tr style='background-color:#fafafa;'>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Title</th>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Price</th>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Quantity</th>
                        </tr>
            ";

            foreach ($bulkItems as $index => $bulk) {
                $productDetails .= "
                    <tr>
                        <td style='padding:6px; border:1px solid #ddd;'>{$bulk->title}</td>
                        <td style='padding:6px; border:1px solid #ddd;'>$" . number_format($bulk->price, 2) . "</td>
                        <td style='padding:6px; border:1px solid #ddd;'>{$cartexplode[$index]}</td>
                    </tr>
                ";
            }

            $productDetails .= "</table></div>";
        }
    }

    /*---- Canvas Check ----*/
     if ($photo->is_canvas == "yes") 
    {
        $productDetails .= "
                <div style='margin-top:10px;'>
                    <p style='margin:5px 0; font-weight:bold; color:#17365d;'>Canvas:</p>
                    <table style='width:90%; margin-left:10px; border-collapse:collapse; font-size:14px;'>
                        <tr style='background-color:#fafafa;'>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Buy 2 16x20's $195 Get 1 Free</th>
                           
                        </tr>
            ";
        $productDetails .= "</table></div>";

    }
    
    /*--- Gift Product Section ----*/
    if ($photo->is_gift_product == "yes" && !empty($photo->gift_product_id)) {
        
        // $cartexplode = explode(",", $photo->extra_bulk);
        // $cartexplode = array_map('trim', $cartexplode); 
        
        
        $giftdata = explode(",", $photo->gift_product_id);
        $giftItems = GiftProduct::whereIn('id', $giftdata)->get();

        if ($giftItems->count() > 0) {
            $productDetails .= "
                <div style='margin-top:10px;'>
                    <p style='margin:5px 0; font-weight:bold; color:#17365d;'>Gift Products:</p>
                    <table style='width:90%; margin-left:10px; border-collapse:collapse; font-size:14px;'>
                        <tr style='background-color:#fafafa;'>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Image</th>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Title</th>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Price</th>
                            <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Varient</th>
                        </tr>
            ";

            foreach ($giftItems as $index => $gift) 
            {
                $imageUrl = Storage::url($gift->product_image);
                $productName = htmlspecialchars($gift->product_name, ENT_QUOTES);
                $price = isset($gift->product_price) ? number_format($gift->product_price, 2) : '0.00';

                if($gift->product_varient == 1)
                {
                    $gvarients = explode(",", $photo->varient_id);
                    $product_variant =ProductVarient::where('id',$gvarients)->first();
                }

                 if(isset($product_variant))
                {
                        $varientSize = $product_variant->title;
                }
               else
                {
                    $varientSize = "NA";
                } 
                
                
                
                $productDetails .= "
                    <tr>
                        <td style='padding:6px; border:1px solid #ddd; text-align:center;'>
                            <img src='{$imageUrl}' 
                                alt='{$productName}' 
                                style='max-width:40px; height:auto; object-fit:cover; border-radius:4px;'>
                        </td>
                        <td style='padding:6px; border:1px solid #ddd;'>{$productName}</td>
                        <td style='padding:6px; border:1px solid #ddd;'>$$price</td>
                        <td style='padding:6px; border:1px solid #ddd;'>{$varientSize}</td>
                    </tr>
                ";
            }

            $productDetails .= "</table></div>";
        }
    }





    $productDetails .= "</td></tr>";
    $count++;
}

$productDetails .= "</table>";


                    



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

         <div style='padding:20px;'>
            {$productDetails}
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
