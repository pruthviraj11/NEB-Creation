<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>
<body style='font-family: Arial, sans-serif; margin:0; padding:0; background-color:#f4f4f4;'>
    <div style='background-color:#ffffff; max-width:600px; margin:20px auto; border:1px solid #dddddd;'>
        
        <!-- Header -->
        <div style='background-color:#17365d; color:white; text-align:center; padding:15px;'>
            <h3 style='margin:0; font-size:24px;'>Order Details</h3>
        </div>

        <!-- Order Summary -->
        <div style='padding:20px;'>
            <h4 style='margin:10px 0;'>Order Summary</h4>
            <table style='width:100%; border-collapse:collapse;'>
                <tr>
                    <td style='padding:8px; font-weight:bold;'>Name:</td>
                    <td style='padding:8px;'>{{ $order->fname }} {{ $order->lname }}</td>
                </tr>
                <tr>
                    <td style='padding:8px; font-weight:bold;'>Transaction Id:</td>
                    <td style='padding:8px;'>{{ $order->transaction_id }}</td>
                </tr>
                <tr>
                    <td style='padding:8px; font-weight:bold;'>Payment Mode:</td>
                    <td style='padding:8px;'>{{ $order->order_type }}</td>
                </tr>
                <tr>
                    <td style='padding:8px; font-weight:bold;'>Order Status:</td>
                    <td style='padding:8px;'>{{ $order->order_status }}</td>
                </tr>
                <tr>
                    <td style='padding:8px; font-weight:bold;'>Total Amount:</td>
                    <td style='padding:8px;'>${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Pricing Breakdown -->
        @if(isset($order->product_total))
        <div style='padding:20px; padding-top:0;'>
            <h4 style='color:#17365d; margin:15px 0 10px 0;'>Pricing Breakdown</h4>
            <table style='width:100%; border-collapse:collapse; background-color:#f9f9f9; border:1px solid #ddd;'>
                <tr>
                    <td style='padding:12px; border:1px solid #ddd; background-color:#f1f1f1;'>Product Total:</td>
                    <td style='padding:12px; border:1px solid #ddd; text-align:right; background-color:#f1f1f1;'>${{ number_format($order->product_total, 2) }}</td>
                </tr>
                <tr>
                    <td style='padding:12px; border:1px solid #ddd;'>Delivery Charge:</td>
                    <td style='padding:12px; border:1px solid #ddd; text-align:right;'>${{ number_format($order->delivery_charge, 2) }}</td>
                </tr>
                <tr>
                    <td style='padding:12px; border:1px solid #ddd;'>Tax ({{ number_format($order->tax_rate, 2) }}%):</td>
                    <td style='padding:12px; border:1px solid #ddd; text-align:right;'>${{ number_format($order->tax_amount, 2) }}</td>
                </tr>
                <tr style='font-weight:bold;'>
                    <td style='padding:12px; border:1px solid #ddd; background-color:#f1f1f1;'>Subtotal:</td>
                    <td style='padding:12px; border:1px solid #ddd; text-align:right; background-color:#f1f1f1;'>${{ number_format($order->total_before_discount, 2) }}</td>
                </tr>
                
                @if($order->discount_amount > 0 && !empty($order->promo_code))
                <tr style='color:#28a745;'>
                    <td style='padding:12px; border:1px solid #ddd;'>Coupon Discount ({{ strtoupper($order->promo_code) }}):</td>
                    <td style='padding:12px; border:1px solid #ddd; text-align:right;'>-${{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                
                <tr style='font-weight:bold; background-color:#17365d; color:white; font-size:16px;'>
                    <td style='padding:15px; border:1px solid #ddd;'>Total Amount:</td>
                    <td style='padding:15px; border:1px solid #ddd; text-align:right;'>${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>
        @endif

        <!-- Product Details -->
        <div style='padding:20px;'>
            <h4 style='color:#17365d;'>Product Details</h4>
            <table style='width:100%; border-collapse:collapse;'>
                <tr style='background-color:#f1f1f1;'>
                    <th style='padding:8px; text-align:left; border:1px solid #ddd;'>#</th>
                    <th style='padding:8px; text-align:left; border:1px solid #ddd;'>Title</th>
                </tr>
                
                @foreach($photos as $index => $photo)
                <tr>
                    <td style='padding:8px; border:1px solid #ddd; vertical-align:top;'>{{ $index + 1 }}</td>
                    <td style='padding:8px; border:1px solid #ddd;'>
                        <strong>{{ $photo->title }}</strong>
                        
                        {{-- Creative Arts --}}
                        @if($photo->is_creative_art === "yes" && !empty($photo->creative_info))
                            @php
                                try {
                                    $cdata = array_filter(explode(",", trim($photo->creative_info)));
                                    $creativeItems = \App\Models\CreativeArt::whereIn('id', $cdata)->get();
                                } catch (\Exception $e) {
                                    $creativeItems = collect();
                                }
                            @endphp
                            
                            @if($creativeItems->count() > 0)
                            <div style='margin-top:10px;'>
                                <p style='margin:5px 0; font-weight:bold; color:#17365d;'>Creative Arts:</p>
                                <table style='width:90%; margin-left:10px; border-collapse:collapse; font-size:14px;'>
                                    <tr style='background-color:#fafafa;'>
                                        <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Title</th>
                                        <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Price</th>
                                    </tr>
                                    @foreach($creativeItems as $creative)
                                    <tr>
                                        <td style='padding:6px; border:1px solid #ddd;'>{{ $creative->title ?? 'N/A' }}</td>
                                        <td style='padding:6px; border:1px solid #ddd;'>${{ number_format($creative->price ?? 0, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                        @endif

                        {{-- Bulk Purchase --}}
                        @if($photo->is_bulk_purchase === "yes" && !empty($photo->bulk_info))
                            @php
                                try {
                                    $cartexplode = array_map('trim', explode(",", trim($photo->extra_bulk ?? '')));
                                    $bulkdata = array_filter(explode(",", trim($photo->bulk_info)));
                                    $bulkItems = \App\Models\BulkPurchase::whereIn('id', $bulkdata)->get();
                                } catch (\Exception $e) {
                                    $bulkItems = collect();
                                    $cartexplode = [];
                                }
                            @endphp
                            
                            @if($bulkItems->count() > 0)
                            <div style='margin-top:10px;'>
                                <p style='margin:5px 0; font-weight:bold; color:#17365d;'>Bulk Purchase:</p>
                                <table style='width:90%; margin-left:10px; border-collapse:collapse; font-size:14px;'>
                                    <tr style='background-color:#fafafa;'>
                                        <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Title</th>
                                        <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Price</th>
                                        <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Quantity</th>
                                    </tr>
                                    @foreach($bulkItems as $bulkIndex => $bulk)
                                    @php
                                        $quantity = intval($cartexplode[$bulkIndex] ?? 1);
                                        $price = floatval($bulk->price ?? 0);
                                        $totalPrice = $price * $quantity;
                                    @endphp
                                    <tr>
                                        <td style='padding:6px; border:1px solid #ddd;'>{{ $bulk->title ?? 'N/A' }}</td>
                                        <td style='padding:6px; border:1px solid #ddd;'>${{ number_format($totalPrice, 2) }} ({{ $quantity }} × ${{ number_format($price, 2) }})</td>
                                        <td style='padding:6px; border:1px solid #ddd;'>{{ $quantity }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                        @endif

                        {{-- Canvas --}}
                        @if($photo->is_canvas === "yes")
                        <div style='margin-top:10px;'>
                            <p style='margin:5px 0; font-weight:bold; color:#17365d;'>Canvas:</p>
                            <table style='width:90%; margin-left:10px; border-collapse:collapse; font-size:14px;'>
                                <tr style='background-color:#fafafa;'>
                                    <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Buy 2 16x20's $195 Get 1 Free</th>
                                </tr>
                            </table>
                        </div>
                        @endif

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
                            <div style='margin-top:10px;'>
                                <p style='margin:5px 0; font-weight:bold; color:#17365d;'>Gift Products:</p>
                                <table style='width:90%; margin-left:10px; border-collapse:collapse; font-size:14px;'>
                                    <tr style='background-color:#fafafa;'>
                                        {{-- <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Image</th> --}}
                                        <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Title</th>
                                        <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Price</th>
                                        <th style='padding:6px; text-align:left; border:1px solid #ddd;'>Variant</th>
                                    </tr>
                                    @foreach($giftItems as $gift)
                                    @php
                                        try {
                                           // $filePath = $gift->product_image ?? '';
                                           $filePath = $gift->product_image;
                                           // $imageUrl = Storage::url($gift->product_image ?? '');
                                            if (!empty($filePath) && Storage::disk('public')->exists($filePath)) {
                                                // Generate full URL for email (absolute)
                                               // $imageUrl = url(Storage::url($filePath));
                                               $imageUrl = public_path(Storage::url($filePath));
                                               
                                            } else {
                                                // Fallback image
                                                $imageUrl = '';
                                            }

                                            $productName = htmlspecialchars($gift->product_name ?? 'N/A', ENT_QUOTES);
                                            $price = floatval($gift->product_price ?? 0);
                                            
                                            $varientSize = "NA";
                                            if($gift->product_varient == 1 && !empty($photo->varient_id)) {
                                                $gvarients = array_filter(explode(",", trim($photo->varient_id)));
                                                $product_variant = \App\Models\ProductVarient::whereIn('id', $gvarients)->first();
                                                if($product_variant) {
                                                    $varientSize = $product_variant->title ?? 'NA';
                                                }
                                            }
                                        } catch (\Exception $e) {
                                            $imageUrl = '';
                                            $productName = 'Error loading product';
                                            $price = 0;
                                            $varientSize = 'NA';
                                        }
                                    @endphp
                                   
                                    <tr>
                                        {{-- <td style='padding:6px; border:1px solid #ddd; text-align:center;'>
                                            @if($imageUrl)
                                            
                                                <img src='{{ $imageUrl }}' alt='{{ $productName }}' style='max-width:40px; height:auto; object-fit:cover; border-radius:4px;'>
                                            @else
                                             {{ dd("no image") }}
                                                <span style='font-size:12px; color:#999;'>No Image</span>
                                            @endif
                                        </td> --}}

                                        
                                        <td style='padding:6px; border:1px solid #ddd;'>{{ $productName }}</td>
                                        <td style='padding:6px; border:1px solid #ddd;'>${{ number_format($price, 2) }}</td>
                                        <td style='padding:6px; border:1px solid #ddd;'>{{ $varientSize }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <!-- Footer -->
        <div style='background-color:#f1f1f1; text-align:center; padding:10px; font-size:12px; color:#777;'>
            Thank you for your purchase!
        </div>
    </div>
</body>
</html>