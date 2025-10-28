<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>
<body style="font-family: Arial, sans-serif; margin:0; padding:0; background-color:#f4f4f4;">
  <div style="background-color:#ffffff; max-width:650px; margin:20px auto; border:1px solid #dddddd; box-shadow:0 2px 6px rgba(0,0,0,0.1); border-radius:6px; overflow:hidden;">

    <!-- Header -->
    <div style="background-color:#17365d; color:#ffffff; padding:20px 25px;">
  <table style="width:100%; border-collapse:collapse;">
    <tr>
      <td style="text-align:left;">
        <h2 style="margin:0; font-size:24px; font-weight:600;">Order Details</h2>
      </td>
      <td style="text-align:right; font-size:14px; color:#cbd4e1;">
        {{ date('M d, Y') }}
      </td>
    </tr>
  </table>
</div>

    <!-- Body -->
    <div style="padding:25px; color:#333;">
      <p style="font-size:15px; line-height:1.6; margin:0 0 15px;">
                Dear <strong>Jess</strong>,
            </p>
            <p style="font-size:15px; line-height:1.6;">
                You have received a new order from <strong>NEB Creations</strong>. Please find the order details below.
            </p>


      <!-- Shipping Details -->
      <h3 style="margin-top:25px; font-size:18px; color:#17365d; border-bottom:2px solid #17365d; padding-bottom:6px;">Shipping Details</h3>

      <table style="width:100%; border-collapse:collapse; margin-top:10px; font-size:14px;">
        <tr>
          <td style="padding:10px; border:1px solid #ccc; font-weight:bold; width:30%;">Name</td>
          <td style="padding:10px; border:1px solid #ccc;">{{ $order->fname }} {{ $order->lname }}</td>
        </tr>
        <tr>
          <td style="padding:10px; border:1px solid #ccc; font-weight:bold;">Email</td>
          <td style="padding:10px; border:1px solid #ccc;">{{ $order->email }}</td>
        </tr>
        <tr>
          <td style="padding:10px; border:1px solid #ccc; font-weight:bold;">Mobile No</td>
          <td style="padding:10px; border:1px solid #ccc;">{{ $order->mobile }}</td>
        </tr>
        <tr>
          <td style="padding:10px; border:1px solid #ccc; font-weight:bold;">Address</td>
          <td style="padding:10px; border:1px solid #ccc;">{{ $order->address1 }}<br>{{ $order->address2 }}</td>
        </tr>
        <tr>
          <td style="padding:10px; border:1px solid #ccc; font-weight:bold;">Country</td>
          <td style="padding:10px; border:1px solid #ccc;">{{ $order->country }}</td>
        </tr>
        <tr>
          <td style="padding:10px; border:1px solid #ccc; font-weight:bold;">State</td>
          <td style="padding:10px; border:1px solid #ccc;">{{ $order->state }}</td>
        </tr>
        <tr>
          <td style="padding:10px; border:1px solid #ccc; font-weight:bold;">Zip</td>
          <td style="padding:10px; border:1px solid #ccc;">{{ $order->zip }}</td>
        </tr>
      </table>
    </div>

    <!-- Product Details -->
    <div style="padding:25px;">
      <h3 style="margin-top:0; font-size:18px; color:#17365d; border-bottom:2px solid #17365d; padding-bottom:6px;">Product Details</h3>

      <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <thead>
          <tr style="background-color:#f1f1f1;">
            <th style="padding:10px; text-align:left; border:1px solid #ddd;">#</th>
            <th style="padding:10px; text-align:left; border:1px solid #ddd;">Title</th>
          </tr>
        </thead>
        <tbody>
          @foreach($photos as $index => $photo)
          <tr>
            <td style="padding:10px; border:1px solid #ddd; vertical-align:top;">{{ $index + 1 }}</td>
            <td style="padding:10px; border:1px solid #ddd;">
              {{-- <strong>{{ $photo->title }}</strong> --}}

              {{-- Gift Products --}}
              @if($photo->is_gift_product === "yes" && !empty($photo->gift_product_id))
                @php
                  try {
                    $giftdata = array_filter(explode(",", trim($photo->gift_product_id)));
                    $giftItems = \App\Models\GiftProduct::whereIn('id', $giftdata)->get();
                  } catch (\Exception $e) {
                    $giftItems = collect();
                  }
                @endphp

                @if($giftItems->count() > 0)
                  <div style="margin-top:12px;">
                    <p style="margin:5px 0; font-weight:bold; color:#17365d;">Gift Products:</p>
                    <table style="width:95%; margin-left:10px; border-collapse:collapse; font-size:13px;">
                      <tr style="background-color:#fafafa;">
                        <th style="padding:6px; text-align:left; border:1px solid #ddd;">Image</th>
                        <th style="padding:6px; text-align:left; border:1px solid #ddd;">Title</th>
                        <th style="padding:6px; text-align:left; border:1px solid #ddd;">Size</th>
                      </tr>
                      @foreach($giftItems as $gift)
                        @php
                          $giftfilePath = $gift->product_image ?? '';
                          $giftimageUrl = '';

                        //   if (!empty($filePath) && Storage::disk('public')->exists($filePath)) {
                        //       $imageUrl = url(Storage::url($filePath));
                        //   }

                          if (!empty($giftfilePath) && Storage::disk('public')->exists($giftfilePath)) {
                            
                           // public_path(Storage::url($photo->back_image));
                           
                           $giftimageUrl = asset(Storage::url($giftfilePath));
                                  
                        }

                          $productName = htmlspecialchars($gift->product_name ?? 'N/A', ENT_QUOTES);
                          $varientSize = 'NA';
                          if ($gift->product_varient == 1) {
                              $variant = \App\Models\ProductVarient::find($gift->id);
                              $varientSize = $variant->title ?? 'NA';
                          }
                        @endphp

                        <tr>
                         <td style='padding:6px; border:1px solid #ddd; text-align:center;'>

                          
                            @if($giftimageUrl)
                                <img src="{{ $giftimageUrl }}" alt="{{ $productName }}" style='max-width:40px; height:auto; object-fit:cover; border-radius:4px;'>
                            @else
                                <span style='font-size:12px; color:#999;'>No Image</span>
                            @endif
                        </td>   
                          <td style="padding:6px; border:1px solid #ddd;">{{ $productName }}</td>
                          <td style="padding:6px; border:1px solid #ddd;">{{ $varientSize }}</td>
                        </tr>
                      @endforeach
                    </table>
                  </div>
                @endif
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Footer -->
    <div style="background-color:#17365d; color:white; text-align:center; padding:12px; font-size:13px;">
            &copy; {{ date('Y') }} NEB Creations Photography. All rights reserved.
        </div>

  </div>
</body>

</html>

