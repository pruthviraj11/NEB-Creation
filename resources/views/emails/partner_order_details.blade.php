<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
</head>
<body style="font-family: Arial, sans-serif; margin:0; padding:0; background-color:#f4f4f4;">
    <div style="background-color:#ffffff; max-width:650px; margin:20px auto; border:1px solid #dddddd; box-shadow:0 2px 6px rgba(0,0,0,0.1);">

        <!-- Header -->
        <div style="background-color:#17365d; color:white; text-align:center; padding:20px 15px;">
            <h2 style="margin:0; font-size:24px;">Order Details</h2>
        </div>

        <!-- Body -->
        <div style="padding:25px; color:#333;">
            <p style="font-size:15px; line-height:1.6; margin:0 0 15px;">
                Dear <strong>Jess</strong>,
            </p>
            <p style="font-size:15px; line-height:1.6;">
                You have received a new order from <strong>NEB Creations</strong>. Please find the order details below.
            </p>

            <!-- Address Section -->
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

            <!-- Product Details -->
            <h3 style="margin-top:30px; font-size:18px; color:#17365d; border-bottom:2px solid #17365d; padding-bottom:6px;">Product Details</h3>

            <table style="width:100%; border-collapse:collapse; margin-top:10px; font-size:14px;">
                <thead>
                    <tr style="background-color:#f1f1f1;">
                        <th style="padding:10px; border:1px solid #ccc; text-align:left;">#</th>
                        <th style="padding:10px; border:1px solid #ccc; text-align:left;">Products</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($photos as $index => $photo)
                    <tr>
                        <td style="padding:10px; border:1px solid #ccc; vertical-align:top;">{{ $index + 1 }}</td>
                        <td style="padding:10px; border:1px solid #ccc; vertical-align:top;">
                            {{-- <strong style="font-size:15px;">{{ $photo->title }}</strong> --}}

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
                                    <div style="margin-top:10px;">
                                        <p style="margin:0 0 5px; font-weight:bold; color:#17365d;">Creative Arts:</p>
                                        <table style="width:95%; border-collapse:collapse; margin-left:10px; font-size:13px;">
                                            <tr style="background-color:#fafafa;">
                                                <th style="padding:6px; border:1px solid #ddd; text-align:left;">Title</th>
                                            </tr>
                                            @foreach($creativeItems as $creative)
                                            <tr>
                                                <td style="padding:6px; border:1px solid #ddd;">{{ $creative->title ?? 'N/A' }}</td>
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
                                    <div style="margin-top:10px;">
                                        <p style="margin:0 0 5px; font-weight:bold; color:#17365d;">Bulk Purchase:</p>
                                        <table style="width:95%; border-collapse:collapse; margin-left:10px; font-size:13px;">
                                            <tr style="background-color:#fafafa;">
                                                <th style="padding:6px; border:1px solid #ddd; text-align:left;">Title</th>
                                                <th style="padding:6px; border:1px solid #ddd; text-align:left;">Quantity</th>
                                            </tr>
                                            @foreach($bulkItems as $bulkIndex => $bulk)
                                            @php
                                                $quantity = intval($cartexplode[$bulkIndex] ?? 1);
                                            @endphp
                                            <tr>
                                                <td style="padding:6px; border:1px solid #ddd;">{{ $bulk->title ?? 'N/A' }}</td>
                                                <td style="padding:6px; border:1px solid #ddd;">{{ $quantity }}</td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @endif
                            @endif

                            {{-- Canvas --}}
                            @if($photo->is_canvas === "yes")
                            <div style="margin-top:10px;">
                                <p style="margin:0 0 5px; font-weight:bold; color:#17365d;">Canvas:</p>
                                <table style="width:95%; border-collapse:collapse; margin-left:10px; font-size:13px;">
                                    <tr style="background-color:#fafafa;">
                                        <td style="padding:6px; border:1px solid #ddd;">Buy 2 16x20's $195 Get 1 Free</td>
                                    </tr>
                                </table>
                            </div>
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

