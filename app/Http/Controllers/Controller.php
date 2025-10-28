<?php

namespace App\Http\Controllers;
use App\Mail\DynamicEmail;
use App\Mail\OrderDetailsMail;
use App\Mail\PartnerDetailsMail;
use App\Mail\GiftDetailsMail;


use App\Mail\ContactFormMail;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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


    // public function sendOrderForm($type = '', $order_ids = [], $other = [])
    // {

    //     $emailTemplate = $type;
    //     if ($emailTemplate == "Order Details") 
    //         {
    //         $orderId = $order_ids;
           
           
    //         $emailAddress = $other;

          
    //         // Get order details separately to avoid duplication
    //         $orderDetail = OrderDetail::where('guest_id', $orderId)->first();
            
    //         // Get cart items with photo details (check for both pending and completed)
    //         $photos = TempCart::where('temp_carts.guest_id', $orderId)
    //             ->whereIn('temp_carts.order_status', ['pending', 'completed'])
    //             ->join('photographies', 'temp_carts.photo_id', '=', 'photographies.id')
    //             ->select(
    //                 'temp_carts.*',
    //                 'photographies.back_image',
    //                 'photographies.is_richard_photo',
    //                 'photographies.price',
    //                 'photographies.discount_price',
    //                 'photographies.title'
    //             )
    //             ->get();
                
    //         // If we have order details, merge them with the first photo item for template compatibility
    //         if ($orderDetail && $photos->isNotEmpty()) {
    //             $firstPhoto = $photos->first();
    //             $firstPhoto->fname = $orderDetail->fname;
    //             $firstPhoto->lname = $orderDetail->lname;
    //             $firstPhoto->order_type = $orderDetail->order_type;
    //             $firstPhoto->transaction_id = $orderDetail->transaction_id;
    //             $firstPhoto->total_amount = $orderDetail->total_amount;
    //             $firstPhoto->order_status = $orderDetail->order_status;
    //             $firstPhoto->product_total = $orderDetail->product_total ?? null;
    //             $firstPhoto->delivery_charge = $orderDetail->delivery_charge ?? null;
    //             $firstPhoto->tax_rate = $orderDetail->tax_rate ?? null;
    //             $firstPhoto->tax_amount = $orderDetail->tax_amount ?? null;
    //             $firstPhoto->total_before_discount = $orderDetail->total_before_discount ?? null;
    //             $firstPhoto->discount_amount = $orderDetail->discount_amount ?? null;
    //             $firstPhoto->promo_code = $orderDetail->promo_code ?? null;
    //         }

            
    //         $setting = Setting::first();
           
 
    //         if ($photos->isNotEmpty()) {
    //             $order = $photos->first();

    //             $richardProducts = $photos->where('is_richard_photo', 'Yes');

    //             $attachments = [];
    //             $richaredphotos = [];
    //             foreach ($photos as $photo) {
    //                 if ($photo->back_image && Storage::disk('public')->exists($photo->back_image)) {
    //                     $attachments[] = public_path(Storage::url($photo->back_image));
                        
    //                     if($photo->is_richard_photo == "Yes") {
    //                         $richaredphotos[] = public_path(Storage::url($photo->back_image));
    //                     }
    //                 }
    //             }

    //             if($photo->is_gift_product === "yes" && !empty($photo->gift_product_id))
    //             {
    //                 $giftdata = array_filter(explode(",", trim($photo->gift_product_id)));
    //                 $giftItems = GiftProduct::whereIn('id', $giftdata)->get();

    //                 if($giftItems->count() > 0)
    //                 {
    //                     foreach($giftItems as $gift)
    //                     {
    //                         $filePath = $gift->product_image;
    //                     }
    //                     if (!empty($filePath) && Storage::disk('public')->exists($filePath)) 
    //                         {
    //                             $attachments[] = public_path(path: Storage::url($filePath));
                               
    //                             if($photo->is_richard_photo == "Yes") {
    //                                 $richaredphotos[] = public_path(path: Storage::url($filePath));
    //                             }
                                               
    //                         } 
    //                 }  
                    
    //             }    
                

    //             // Send emails using proper Mailable classes with error handling
    //             $emailRecipients = [
    //                 ['email' => trim($emailAddress), 'subject' => 'Your Order Details'],
    //             ];

    //             if (!empty($setting->admin_email)) {
    //                 $emailRecipients[] = ['email' => trim($setting->admin_email), 'subject' => 'New Order Received'];
    //             }

    //             if (!empty($setting->partner_email)) {
    //                 $emailRecipients[] = ['email' => trim($setting->partner_email), 'subject' => 'New Order Received'];
    //             }

    //             // Send main emails
    //             foreach ($emailRecipients as $recipient) {
    //                 try {
    //                     Mail::to($recipient['email'])
    //                         ->send(new OrderDetailsMail($order, $photos, $recipient['subject'], $attachments));
                        
    //                     Log::info("Email sent successfully to: " . $recipient['email']);
                        
    //                     // Add delay to prevent rate limiting
    //                     sleep(1);
                        
    //                 } catch (\Exception $e) {
    //                     Log::error("Failed to send email to {$recipient['email']}: " . $e->getMessage());
                        
    //                     // If it's a rate limiting error, wait longer and retry once
    //                     if (strpos($e->getMessage(), '450') !== false || strpos($e->getMessage(), 'rate') !== false) {
    //                         Log::info("Rate limiting detected, waiting 5 seconds before retry...");
    //                         sleep(5);
                            
    //                         try {
    //                             Mail::to($recipient['email'])
    //                                 ->send(new OrderDetailsMail($order, $photos, $recipient['subject'], $attachments));
    //                             Log::info("Email sent successfully on retry to: " . $recipient['email']);
    //                         } catch (\Exception $retryException) {
    //                             Log::error("Failed to send email on retry to {$recipient['email']}: " . $retryException->getMessage());
    //                         }
    //                     }
    //                 }
    //             }

    //             // Send to Printify email (only Richard products) with separate handling
    //             if ($richardProducts->isNotEmpty() && !empty($setting->printify_email)) {
    //                 try {
    //                     Mail::to(trim($setting->printify_email))
    //                         ->send(new OrderDetailsMail($order, $richardProducts, "Order Details - Richard Products", $richaredphotos));
                        
    //                     Log::info("Printify email sent successfully to: " . $setting->printify_email);
                        
    //                 } catch (\Exception $e) {
    //                     Log::error("Failed to send Printify email: " . $e->getMessage());
    //                 }
    //             }
    //         }
    //     }
    // }


    /*----- Contact Form Send Mail -----*/
    
    
    public function sendOrderForm($type = '', $order_ids = [], $other = [])
{
    $emailTemplate = $type;
    if ($emailTemplate == "Order Details") {
        $orderId = $order_ids;
        $emailAddress = $other;

        $orderDetail = OrderDetail::where('guest_id', $orderId)->first();

        $photos = TempCart::where('temp_carts.guest_id', $orderId)
                ->whereIn('temp_carts.order_status', ['pending', 'completed'])
                ->join('photographies', 'temp_carts.photo_id', '=', 'photographies.id')
                ->select(
                    'temp_carts.*',
                    'photographies.back_image',
                    'photographies.is_richard_photo',
                    'photographies.price',
                    'photographies.discount_price',
                    'photographies.title'
                )
                ->get();

        if ($orderDetail && $photos->isNotEmpty()) {
                $firstPhoto = $photos->first();
                $firstPhoto->fname = $orderDetail->fname;
                $firstPhoto->lname = $orderDetail->lname;
                $firstPhoto->address1 = $orderDetail->address1;
                $firstPhoto->address2 = $orderDetail->address2;
                $firstPhoto->country = $orderDetail->country;
                $firstPhoto->state = $orderDetail->state;
                $firstPhoto->zip = $orderDetail->zip;
                $firstPhoto->email = $orderDetail->email;
                $firstPhoto->mobile = $orderDetail->mobile;
                $firstPhoto->order_type = $orderDetail->order_type;
                $firstPhoto->transaction_id = $orderDetail->transaction_id;
                $firstPhoto->total_amount = $orderDetail->total_amount;
                $firstPhoto->order_status = $orderDetail->order_status;
                $firstPhoto->product_total = $orderDetail->product_total ?? null;
                $firstPhoto->delivery_charge = $orderDetail->delivery_charge ?? null;
                $firstPhoto->tax_rate = $orderDetail->tax_rate ?? null;
                $firstPhoto->tax_amount = $orderDetail->tax_amount ?? null;
                $firstPhoto->total_before_discount = $orderDetail->total_before_discount ?? null;
                $firstPhoto->discount_amount = $orderDetail->discount_amount ?? null;
                $firstPhoto->promo_code = $orderDetail->promo_code ?? null;
            }

        $setting = Setting::first();

        if ($photos->isNotEmpty()) {
            $order = $photos->first();
            $attachments = [];
            $creativePhotos = collect();
            $giftPhotos = collect();
            $richardProducts = collect();
            $richaredphotos = [];

            // Separate by product types
            foreach ($photos as $photo) {
                if ($photo->back_image && Storage::disk('public')->exists($photo->back_image)) {
                    $attachments[] = public_path(Storage::url($photo->back_image));
                }

                if ($photo->is_creative_art === "yes") {
                    $creativePhotos->push($photo);
                }
                if ($photo->is_gift_product === "yes") {
                    $giftPhotos->push($photo);
                }
                if ($photo->is_richard_photo === "Yes") {
                    $richardProducts->push($photo);
                }
            }

            // Attach gift product images if available
            foreach ($giftPhotos as $giftPhoto) {
                if (!empty($giftPhoto->gift_product_id)) {
                    $giftdata = array_filter(explode(",", trim($giftPhoto->gift_product_id)));
                    $giftItems = GiftProduct::whereIn('id', $giftdata)->get();

                    // foreach ($giftItems as $gift) {
                    //     $filePath = $gift->product_image;
                    //     if (!empty($filePath) && Storage::disk('public')->exists($filePath)) {
                    //         $attachments[] = public_path(Storage::url($filePath));
                    //     }
                    // }
                }
            }

            // Always send all details to admin
            if (!empty($setting->admin_email)) {
                try {
                    Mail::to(trim($setting->admin_email))
                        ->send(new OrderDetailsMail($order, $photos, 'Order Received From NEB Creations Photography', $attachments));
                    Log::info("Admin email sent successfully to: {$setting->admin_email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send admin email: " . $e->getMessage());
                }
            }

            // Send creative art–only details to partner
            if ($creativePhotos->isNotEmpty() && !empty($setting->partner_email)) {
                try {
                    Mail::to(trim($setting->partner_email))
                        ->send(new PartnerDetailsMail($order, $creativePhotos, 'Order Received From NEB Creations Photography', []));
                    Log::info("Creative Art email sent to: {$setting->partner_email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send Creative Art email: " . $e->getMessage());
                }
            }

            // Send gift-only details to printify
            if ($giftPhotos->isNotEmpty() && !empty($setting->printify_email)) {
                try {
                    Mail::to(trim($setting->printify_email))
                        ->send(new GiftDetailsMail($order, $giftPhotos, 'Order Received From NEB Creations Photography', $attachments=[]));
                    Log::info("Gift Product email sent to: {$setting->printify_email}");
                } catch (\Exception $e) {
                    Log::error("Failed to send Gift Product email: " . $e->getMessage());
                }
            }

            // Send Richard-only items to printify too (optional existing part)
            // if ($richardProducts->isNotEmpty() && !empty($setting->printify_email)) {
            //     try {
            //         Mail::to(trim($setting->printify_email))
            //             ->send(new OrderDetailsMail($order, $richardProducts, "Richard Products Order", $attachments));
            //         Log::info("Printify Richard email sent successfully.");
            //     } catch (\Exception $e) {
            //         Log::error("Failed to send Printify Richard email: " . $e->getMessage());
            //     }
            // }

            // Send to customer (main order)

            // if (!empty($emailAddress)) {
            //     try {
            //         Mail::to(trim($emailAddress))
            //             ->send(new OrderDetailsMail($order, $photos, 'Your Order Details', $attachments));
            //         Log::info("Customer email sent successfully to: {$emailAddress}");
            //     } catch (\Exception $e) {
            //         Log::error("Failed to send customer email: " . $e->getMessage());
            //     }
            // }
        }
    }
}

    
    
    
    public function sendContactForm($type = '', $job_ids = [], $other = [])
    {

        $emailTemplate = $type;
        if ($emailTemplate == "Contact Us") 
            {
            $ContactId = $job_ids;
           
            $emailAddress = $other;

            $contactInfo = Contact::where('id',$ContactId)->first(); 
             
           



            // Send contact form email with error handling
            try {
                Mail::to(trim($emailAddress))
                    ->send(new ContactFormMail($contactInfo, "Contact Us Form"));
                
                Log::info("Contact form email sent successfully to: " . $emailAddress);
                
            } catch (\Exception $e) {
                Log::error("Failed to send contact form email to {$emailAddress}: " . $e->getMessage());
                
                // If it's a rate limiting error, wait and retry once
                if (strpos($e->getMessage(), '450') !== false || strpos($e->getMessage(), 'rate') !== false) {
                    Log::info("Rate limiting detected for contact form, waiting 5 seconds before retry...");
                    sleep(5);
                    
                    try {
                        Mail::to(trim($emailAddress))
                            ->send(new ContactFormMail($contactInfo, "Contact Us Form"));
                        Log::info("Contact form email sent successfully on retry to: " . $emailAddress);
                    } catch (\Exception $retryException) {
                        Log::error("Failed to send contact form email on retry to {$emailAddress}: " . $retryException->getMessage());
                    }
                }
            }


        }     
    }

}
