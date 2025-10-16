<?php

namespace App\Http\Controllers\apps;
use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\CreateContactRequest;
use App\Models\Photography;
use App\Models\Category;
use App\Models\TempCart;
use App\Models\OrderDetail;
use App\Models\Setting;
use App\Models\CreativeArt;
use App\Models\GiftProduct;
use App\Models\BulkPurchase;
use App\Models\ProductVarient;
use App\Models\ProductVarientPrice;







use App\Services\ContactService;

use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;

class FrontController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */

   protected ContactService $contactService;


  public function __construct(ContactService $contactService)
  {
    // $this->middleware('auth');
    $this->contactService = $contactService;
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  /*----------- Check Guest ID ---------*/
  
  public function checkGuestId($guest_id, Request $request)
    {
        $guestId = $guest_id;
        $storeGuestId = $request->session()->put('guestId', $guestId);
    }

    public function ajaxcartdetails(Request $request)
    {
        $guestId = $request->session()->get('guestId');
         $cartsdata = TempCart::where('temp_carts.guest_id', $guestId)
        ->where('temp_carts.order_status', 'pending')
        ->join('photographies', 'temp_carts.photo_id', '=', 'photographies.id')
        ->select(
            'temp_carts.id',
            'temp_carts.total_amount',
            'photographies.title',
            'photographies.slug',
            'photographies.front_image'
        )
        ->get();

      return response()->json($cartsdata);
        
    }

    public function deletecartitem($itemId, Request $request)
    {
        $deleted = TempCart::where('id', $itemId)->delete();

        if ($deleted) {
            return response()->json([
                'status' => 'success',
                'message' => 'Item removed successfully',
                'item_id' => $itemId
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Item not found or could not be deleted'
        ], 404);
    }
    


    
  
  
  public function index()
  {
    $pageTitle['page_name'] = "Home";
    
    $photos = Photography::where('is_home','yes')->where('status','1')->orderBy('id','desc')->get();
    
    return view('index',compact('pageTitle','photos'));
  }

  public function about_us()
  {
    $pageTitle['page_name'] = "About Us";
    
   $photos = Photography::where('status','1')->get();
    
    return view('about_us',compact('pageTitle','photos'));
  }

public function photos()
  {
    $pageTitle['page_name'] = "Photos";
    
   $photos = Photography::where('status','1')->orderBy('id','desc')->get();
    
    return view('photos',compact('pageTitle','photos'));
  }


  public function contact_us()
  {
    $pageTitle['page_name'] = "Contact Us";
    return view('contact_us',compact('pageTitle'));
  }

  public function store(CreateContactRequest $request)
  {
    try {
      $ContactData['name'] = $request->get('name');
      $ContactData['email'] = $request->get('email');
      $ContactData['mobile'] = $request->get('mobile');
      $ContactData['message'] = $request->get('message');

      $contact = $this->contactService->create($ContactData);
      $lastId = $contact->id;

      // $other = ['email_address' => 'vcprajapati.mscit@gmail.com'];

      $dbemail = Setting::first();
      $emailAddress = $dbemail->admin_email;
     $this->sendContactForm('Contact Us', [$lastId], $other = $emailAddress);




      if (!empty($contact)) {
        return redirect()->route("front-contact")->with('success', 'Contact Information Inserted Successfully');
      } else {
        return redirect()->back()->with('error', 'Error while Adding Contact');
      }
    } catch (\Exception $error) {
      dd($error->getMessage());
      return redirect()->route("front-contact")->with('error', 'Error while adding Contact');
    }
  }


  public function photo_details($slug,Request $request)
  {
    $guestId = $request->session()->get('guestId');
    
    $photo = Photography::where('photographies.slug', $slug)
    ->join('categories', 'photographies.category_id', '=', 'categories.id')
    ->select('photographies.*', 'categories.category as category_title')
    ->first();

    $features= Photography::where('category_id', $photo->category_id)->where('id', '!=', $photo->id)->get();
   
   $creatives = CreativeArt::where('status','1')->get();

   $giftProducts = GiftProduct::where('status',1)->get();

   $bulkpurchases = BulkPurchase::where('status',1)->get();

   $varients = ProductVarient::where('status',1)->get();

     
   
    $pageTitle['page_name'] = $photo->title;
    
   
    
    return view('photo_details',compact('pageTitle','photo','features','creatives','giftProducts','bulkpurchases','varients'));
  }

  public function add_cart(Request $request)
  {
    $guestId = $request->session()->get('guestId');
    $photo_Id = $request->get('photo_id');

   
    $creative_arts = $request->get('creative_art');
    if($creative_arts != '')
    {
        $is_creative_art = "yes";
        $creative_values = implode(",",$creative_arts);

    }
    else
      {
        $is_creative_art = "no";
        $creative_values = NULL;
      }  


     $bulk_info = $request->get('bulk_id');
     $bquntity = $request->get('bulk_quntity');

     if (!empty($bulk_info)) 
    {
      $is_bulk = "yes";
      $bulk_data = [];
        foreach ($bulk_info as $index => $bulk_id_value) {
            $quantity = isset($bquntity[$index]) ? $bquntity[$index] : 0;
            $bulk_data[] = [
                'bulk_id' => $bulk_id_value,
                'bulk_quantity' => $quantity
            ];
        }

   
    
    $bulk_id = implode(",", array_column($bulk_data, 'bulk_id'));
    $bulk_quntity = implode(",", array_column($bulk_data, 'bulk_quantity'));

   
    
} else {
    $is_bulk = "no";
    $bulk_id = NULL;
    $bulk_quntity = NULL;
    $bulk_data = [];
}


  $canvas = $request->get('canvas');
  


     $gift_info = $request->get('gift_id');

     if($gift_info != '')
     {
         $is_gift = "yes";
         $gift_id = implode(",",$gift_info);
        
     }  
     else
     {
        $is_gift = "no";
        $gift_id = NULL;
         
     }

     $varient_info = $request->get('varient');

     if($varient_info != '')
     {
        
         $varient_id = implode(",",$varient_info);
        
     }  
     else
     {
        
        $varient_id = NULL;
         
     }

    
   
    $checkData = TempCart::where('guest_id',$guestId)->where('photo_id',$photo_Id)->where('order_status','pending')->count();
    if ($checkData == 0)
    {
        $amount = $request->get('total_price');
        $quantity = 1; // default = 1

        TempCart::create([
            'guest_id'     => $guestId,
            // 'user_id'      => auth()->id() ?? null,
            'user_id'      => null,
            'photo_id'     => $photo_Id,
            // 'quantity'     => $quantity,
            // 'amount'       => $amount,
            'total_amount' => $amount,
            'is_creative_art' => $is_creative_art,
            'creative_info' => $creative_values,
            'is_bulk_purchase' => $is_bulk,
            'bulk_info' => $bulk_id,
            'extra_bulk' => $bulk_quntity,
            'is_canvas' => $request->get('canvas') ? 'yes' : NULL,
            'is_gift_product' => $is_gift,
            'gift_product_id' => $gift_id,
            'varient_id' => $varient_id,
            'order_status' => 'pending',
        ]);
    }


    $pageTitle['page_name'] = "Cart";
    return redirect()->route("front-view-cart")->with('success', '');
  }



  

  public function cart(Request $request)
  {
    $guestId = $request->session()->get('guestId');
    
    $carts = TempCart::where('temp_carts.guest_id', $guestId)
    ->where('temp_carts.order_status', 'pending')
    ->join('photographies', 'temp_carts.photo_id', '=', 'photographies.id')
    ->select('temp_carts.*', 'photographies.title', 'photographies.slug', 'photographies.front_image')
    ->get();


    $varients = ProductVarient::where('status',1)->get();


    foreach ($carts as $cart) 
    {

      /*----- Creative Section ------*/
      
      if ($cart->is_creative_art == "yes") {
      
          $creativeIds = explode(',', $cart->creative_info);

          $creativeIds = array_filter(array_map('trim', $creativeIds));

          $creative_datas = CreativeArt::whereIn('id', $creativeIds)->get();

          $cart->creative_items = $creative_datas;
        
      }


      /*----- Bulk Purchase Section ------*/
      if ($cart->is_bulk_purchase == "yes") 
      {
          $bulkIds = explode(',', $cart->bulk_info);

          $bulkIds = array_filter(array_map('trim', $bulkIds));

          $bulk_datas = BulkPurchase::whereIn('id', $bulkIds)->get();

          $cart->bulk_items = $bulk_datas;
      }


      /*----- Gift product Section ------*/

      if ($cart->is_gift_product == "yes") {
      
          $giftIds = explode(',', $cart->gift_product_id);

          $giftIds = array_filter(array_map('trim', $giftIds));

          $gift_datas = GiftProduct::whereIn('id', $giftIds)->get();

          $cart->gift_items = $gift_datas;
        
      }

    


    }
    $pageTitle['page_name'] = "Cart";
    return view('cart',compact('pageTitle','carts','varients'));
  }

   public function removeTempCart($cardId)
    {
        $deletePhoto = TempCart::where('id',$cardId)->delete();

        return redirect()->route("front-view-cart")->with('success', 'Photo Deleted Successfully');
    }



  public function checkout(Request $request)
    {
        $guestId = $request->session()->get('guestId');
        $carts = TempCart::where('temp_carts.guest_id', $guestId)
    ->where('temp_carts.order_status', 'pending')
    ->join('photographies', 'temp_carts.photo_id', '=', 'photographies.id')
    ->select('temp_carts.*', 'photographies.title', 'photographies.slug', 'photographies.front_image')
    ->get();

    // Get saved billing details from session (if any) for prefilling form
    $billingDetails = $request->session()->get('billing_details', []);
    
    // Add a session flash message if billing details are being prefilled
    if (!empty($billingDetails) && !$request->session()->has('prefill_notified')) {
        $request->session()->flash('info', 'Your billing details have been restored from your previous attempt.');
        $request->session()->put('prefill_notified', true);
    }

    // Get delivery charge and tax rate from settings
    $settings = Setting::first();
    $delivery_charge = $settings ? $settings->delivery_charge : 5.00;
    $tax_rate = $settings ? $settings->tax_rate : 9.25;

    $pageTitle['page_name'] = "Checkout";
    return view('checkout',compact('pageTitle','carts','billingDetails','delivery_charge','tax_rate'));

    }


  public function add_checkout(Request $request)
  {
     $pageTitle['page_name'] = "CheckOut";
    
    $guestId = $request->session()->get('guestId');

    $first_name = $request->get('first_name');
    $last_name = $request->get('last_name');
    $mobile = $request->get('mobile');
    $email = $request->get('email');
    $address = $request->get('address');
    $address2 = $request->get('address2');
    $country = $request->get('country');
    $state = $request->get('state');
    $zip = $request->get('zip');
    //$payment = $request->get('payment');
    $product_total = $request->get('product_total');
    
    // Get delivery charge and tax rate from settings instead of hardcoded values
    $settings = Setting::first();
    $settings_delivery_charge = $settings ? $settings->delivery_charge : 5.00;
    $settings_tax_rate = $settings ? $settings->tax_rate : 9.25;
    
    $delivery_charge = $request->get('delivery_charge', $settings_delivery_charge);
    $tax_rate = $request->get('tax_rate', $settings_tax_rate);
    $tax_amount = $request->get('tax_amount');
    $total_before_discount = $request->get('total_before_discount');
    $discount_amount = $request->get('discount_amount', 0);
    $total_amount = $request->get('total_amount');
    $applied_promo_code = $request->get('applied_promo_code', '');

    // Store billing details in session for prefilling on return (e.g., from payment cancellation)
    $billingDetails = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'mobile' => $mobile,
        'email' => $email,
        'address' => $address,
        'address2' => $address2,
        'country' => $country,
        'state' => $state,
        'zip' => $zip,
        'promo_code' => $applied_promo_code,
    ];
    $request->session()->put('billing_details', $billingDetails);

    OrderDetail::create([
            'guest_id'     => $guestId,
            // 'user_id'      => auth()->id() ?? null,
            'user_id'      => null,
            'order_status'     => 'pending',
            'order_type'     => 'Online',
            'product_total'      => $product_total,
            'delivery_charge'    => $delivery_charge,
            'tax_rate'           => $tax_rate,
            'tax_amount'         => $tax_amount,
            'total_before_discount' => $total_before_discount,
            'total_amount'       => $total_amount,
            'discount_amount'    => $discount_amount,
            'promo_code'         => $applied_promo_code,
            'coupon_id'          => $request->session()->get('applied_promo_code.coupon_id'),
            'fname' =>$first_name,
            'lname' =>$last_name,
            'email' =>$email,
            'mobile' =>$mobile,
            'address1' =>$address,
            'address2' =>$address2,
            'country' =>$country,
            'state' =>$state,
            'zip' =>$zip,
        ]);
    
    
    // if($payment == "cod")
    // {
    //     $emailAddress = $request->get('email');
    //     $this->sendOrderForm('Order Details', [$guestId], $other = $emailAddress);
    //     return redirect()->route("front-success")->with('success', '');
    //   }
    // else
    // {
      $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
      
      // Determine the amount to send to Stripe
      // If promo code applied, use subtotal before discount; otherwise use total
      $stripeAmount = (!empty($applied_promo_code) && $discount_amount > 0) 
          ? $total_before_discount 
          : $total_amount;
      
      // Prepare session data
      $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Photography Order',
                    ],
                    'unit_amount' => $stripeAmount * 100, // amount in cents (USD)
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
      ];

      // If a promo code was applied on our checkout page, apply it to Stripe session
      if (!empty($applied_promo_code) && $discount_amount > 0) {
          $promoCodeData = $request->session()->get('applied_promo_code');
          if ($promoCodeData && isset($promoCodeData['promotion_code_id'])) {
              $sessionData['discounts'] = [[
                  'promotion_code' => $promoCodeData['promotion_code_id']
              ]];
          }
      } else {
          // Only allow promotion codes if no discount was already applied
          $sessionData['allow_promotion_codes'] = true;
      }

      $session = $stripe->checkout->sessions->create($sessionData);

        return redirect($session->url);

   // }
    
   
    
  }


  

   public function stripe_success(Request $request)
  {
      $guestId = $request->session()->get('guestId');

      $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
      $sessionId = $request->get('session_id');

      // Retrieve full Checkout Session object
    $session = $stripe->checkout->sessions->retrieve($sessionId, [
        'expand' => ['payment_intent', 'customer'] // expands nested objects
    ]);

    // PaymentIntent holds transaction details
    $paymentIntent = $session->payment_intent;

    // Example: extract information
    $paymentData = [
        'session_id'      => $session->id,
        'customer_email'  => $session->customer_details->email ?? null,
        'customer_name'   => $session->customer_details->name ?? null,
        'amount'          => $session->amount_total, // in paise
        'currency'        => strtoupper($session->currency),
        'payment_status'  => $session->payment_status, // e.g., "paid"
        'payment_intent'  => is_object($paymentIntent) ? $paymentIntent->id : $paymentIntent,
    ];
   
    
    OrderDetail::where('guest_id', $guestId)
        ->where('order_status', 'pending') // only update pending carts
        ->update([
            'order_status' => 'completed',
            'order_type' => 'online',
            'transaction_id' => $paymentData['payment_intent'],
            'total_amount' => $paymentData['amount']/100,
        ]);

        

         $emailAddress = $paymentData['customer_email'];
         $this->sendOrderForm('Order Details', [$guestId], $other = $emailAddress);
    
    // Clear billing details and promo code data from session as payment is successful
    $request->session()->forget('billing_details');
    $request->session()->forget('applied_promo_code');
    $request->session()->forget('prefill_notified');

    return redirect()->route("front-success")->with('success', '');
   
  }


  public function success(Request $request)
  {
    $guestId = $request->session()->get('guestId');
    

 TempCart::where('guest_id', $guestId)
        ->where('order_status', 'pending') // only update pending carts
        ->update([
            'order_status' => 'completed'
        ]);

    // Clear billing details and promo code data from session as order is complete
    $request->session()->forget('billing_details');
    $request->session()->forget('applied_promo_code');
    $request->session()->forget('prefill_notified');

    // Remove guestId from session
   // $request->session()->forget('guestId');
     
    $pageTitle['page_name'] = "Success";

    return view('success',compact('pageTitle'));
   
  }


  /**
   * Validate and apply promo code using Stripe API
   * 
   * To use this feature:
   * 1. Create coupons in Stripe Dashboard
   * 2. Create promotional codes linked to those coupons
   * 3. Customers can enter codes on checkout page OR on Stripe checkout page
   * 
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function validatePromoCode(Request $request)
  {
    try {
        $promoCode = strtoupper(trim($request->get('promo_code')));
        $subtotal = $request->get('total_before_discount', $request->get('total_amount', 0)); // Subtotal includes product + delivery + tax
        
        if (empty($promoCode)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a promo code.'
            ], 400);
        }

        // Initialize Stripe
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        
        try {
            // Log the promo code being searched
            \Log::info('Searching for promo code: ' . $promoCode);
            
            // First, try to retrieve the promotional code - check both active and inactive
            $promotionCodes = $stripe->promotionCodes->all([
                'code' => $promoCode,
                'limit' => 10 // Increase limit to see if there are multiple matches
            ]);

            // Log the full response for debugging
            \Log::info('Stripe API Response for promo codes:', [
                'searched_code' => $promoCode,
                'results_count' => count($promotionCodes->data),
                'results' => json_decode(json_encode($promotionCodes->data), true)
            ]);

            if (empty($promotionCodes->data)) {
                // Try searching without case sensitivity and without active filter
                $allPromoCodes = $stripe->promotionCodes->all(['limit' => 100]);
                $matchingCodes = [];
                
                foreach ($allPromoCodes->data as $code) {
                    if (strtoupper($code->code) === $promoCode) {
                        $matchingCodes[] = [
                            'id' => $code->id,
                            'code' => $code->code,
                            'active' => $code->active,
                            'coupon_id' => $code->coupon->id ?? 'N/A'
                        ];
                    }
                }
                
                \Log::info('Manual search results:', [
                    'searched_code' => $promoCode,
                    'matching_codes' => $matchingCodes
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired promo code. Code searched: ' . $promoCode,
                    'debug_info' => $matchingCodes // Remove this in production
                ], 400);
            }

            $promotionCode = $promotionCodes->data[0];
            $coupon = $promotionCode->coupon;

            // Log the found promotion code details
            \Log::info('Found promotion code:', [
                'promotion_code_id' => $promotionCode->id,
                'code' => $promotionCode->code,
                'active' => $promotionCode->active,
                'coupon_id' => $coupon->id,
                'coupon_valid' => $coupon->valid,
                'coupon_name' => $coupon->name ?? 'N/A',
                'percent_off' => $coupon->percent_off,
                'amount_off' => $coupon->amount_off,
                'max_redemptions' => $coupon->max_redemptions,
                'times_redeemed' => $coupon->times_redeemed
            ]);

            // Check if coupon is valid
            if (!$coupon->valid) {
                \Log::warning('Coupon is not valid', ['coupon_id' => $coupon->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'This promo code has expired.'
                ], 400);
            }

            // Check redemption limits
            if ($coupon->max_redemptions && $coupon->times_redeemed >= $coupon->max_redemptions) {
                return response()->json([
                    'success' => false,
                    'message' => 'This promo code has reached its usage limit.'
                ], 400);
            }

            // Calculate discount
            $discountAmount = 0;
            $discountPercentage = 0;

            if ($coupon->percent_off) {
                // Percentage discount
                $discountPercentage = $coupon->percent_off;
                $discountAmount = round(($subtotal * $discountPercentage) / 100, 2);
                
                // Apply max discount limit if set
                if ($coupon->amount_off && $discountAmount > ($coupon->amount_off / 100)) {
                    $discountAmount = $coupon->amount_off / 100;
                }
            } elseif ($coupon->amount_off) {
                // Fixed amount discount (amount is in cents, convert to dollars/rupees)
                $discountAmount = $coupon->amount_off / 100;
            }

            // Ensure discount doesn't exceed subtotal
            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }

            // Store promo code in session for later use
            $request->session()->put('applied_promo_code', [
                'code' => $promoCode,
                'coupon_id' => $coupon->id,
                'promotion_code_id' => $promotionCode->id,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage
            ]);

            return response()->json([
                'success' => true,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'coupon_name' => $coupon->name ?: $promoCode,
                'message' => 'Promo code applied successfully!'
            ]);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid promo code.'
            ], 400);
        }

    } catch (\Exception $e) {
        \Log::error('Promo code validation error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Unable to validate promo code. Please try again.'
        ], 500);
    }
  }

  public function debugPromoCodes()
  {
    try {
        $stripeSecret = config('services.stripe.secret');
        $stripe = new \Stripe\StripeClient($stripeSecret);
        
        // Determine if using test or live keys
        $isTestMode = strpos($stripeSecret, 'sk_test_') === 0;
        $isLiveMode = strpos($stripeSecret, 'sk_live_') === 0;
        
        // Get all promotional codes
        $allPromoCodes = $stripe->promotionCodes->all(['limit' => 100]);
        
        $codes = [];
        foreach ($allPromoCodes->data as $promoCode) {
            $codes[] = [
                'id' => $promoCode->id,
                'code' => $promoCode->code,
                'active' => $promoCode->active,
                'coupon' => [
                    'id' => $promoCode->coupon->id,
                    'valid' => $promoCode->coupon->valid,
                    'name' => $promoCode->coupon->name ?? 'N/A',
                    'percent_off' => $promoCode->coupon->percent_off,
                    'amount_off' => $promoCode->coupon->amount_off,
                    'currency' => $promoCode->coupon->currency ?? 'N/A',
                    'max_redemptions' => $promoCode->coupon->max_redemptions,
                    'times_redeemed' => $promoCode->coupon->times_redeemed,
                    'created' => date('Y-m-d H:i:s', $promoCode->coupon->created),
                ]
            ];
        }
        
        return response()->json([
            'success' => true,
            'stripe_environment' => $isTestMode ? 'TEST' : ($isLiveMode ? 'LIVE' : 'UNKNOWN'),
            'stripe_key_prefix' => substr($stripeSecret, 0, 8) . '...',
            'total_codes' => count($codes),
            'codes' => $codes,
            'search_for_regtest' => array_filter($codes, function($code) {
                return strtoupper($code['code']) === 'REGTEST';
            })
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
  }

  public function createRegtestPromo()
  {
    try {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        
        // First create a coupon
        $coupon = $stripe->coupons->create([
            'percent_off' => 15,
            'duration' => 'once',
            'name' => 'REGTEST Discount',
        ]);
        
        // Then create the promotional code
        $promoCode = $stripe->promotionCodes->create([
            'coupon' => $coupon->id,
            'code' => 'REGTEST',
            'active' => true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'REGTEST promo code created successfully!',
            'coupon' => [
                'id' => $coupon->id,
                'percent_off' => $coupon->percent_off,
                'name' => $coupon->name,
            ],
            'promo_code' => [
                'id' => $promoCode->id,
                'code' => $promoCode->code,
                'active' => $promoCode->active,
            ]
        ]);
        
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        return response()->json([
            'success' => false,
            'error' => 'Stripe API Error: ' . $e->getMessage(),
            'type' => 'InvalidRequestException'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'type' => get_class($e)
        ]);
    }
  }

  public function cancel(Request $request)
  {
    $guestId = $request->session()->get('guestId');
    
    // Optional: Log the cancellation for analytics
    \Log::info('Payment cancelled for guest: ' . $guestId);
    
    // Check if user has items in cart
    $cartCount = TempCart::where('guest_id', $guestId)
                        ->where('order_status', 'pending')
                        ->count();
    
    if ($cartCount === 0) {
        // If no items in cart, redirect to home or cart page
        return redirect()->route('front-view-cart')->with('info', 'Your cart is empty. Add items to proceed with checkout.');
    }
    
    // Redirect back to checkout page with a message
    return redirect()->route('front-checkout')->with('warning', 'Payment was cancelled. Your items are still in your cart. You can try again or modify your order.');
  }


  


  

  

  


 
  
}
