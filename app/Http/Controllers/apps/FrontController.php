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

    

    $pageTitle['page_name'] = "Checkout";
    return view('checkout',compact('pageTitle','carts'));

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
    $total_amount = $request->get('total_amount');

  

    OrderDetail::create([
            'guest_id'     => $guestId,
            // 'user_id'      => auth()->id() ?? null,
            'user_id'      => null,
            'order_status'     => 'pending',
            'order_type'     => 'Online',
            'total_amount'       => $total_amount,
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
      $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Example Product',
                    ],
                    'unit_amount' => $total_amount *100, // amount in paise (INR) or cents (USD)
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);

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

    // Remove guestId from session
   // $request->session()->forget('guestId');
     
    $pageTitle['page_name'] = "Success";

    return view('success',compact('pageTitle'));
   
  }


  


  

  

  


 
  
}
