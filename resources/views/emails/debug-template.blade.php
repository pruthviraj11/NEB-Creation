<!DOCTYPE html>
<html>
<head>
    <title>Debug Email Issues</title>
    <style>
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc; }
        .item { margin: 20px 0; padding: 15px; border: 2px solid #17365d; }
    </style>
</head>
<body>
    <h1>Email Template Debug</h1>
    
    @if(isset($photos) && $photos->count() > 0)
        <div class="debug">
            <strong>Total Photos Found:</strong> {{ $photos->count() }}
        </div>
        
        @foreach($photos as $index => $photo)
            <div class="item">
                <h3>Item {{ $index + 1 }}: {{ $photo->title }}</h3>
                
                <div class="debug">
                    <strong>Raw Data:</strong><br>
                    - Creative Art: "{{ $photo->is_creative_art }}"<br>
                    - Creative Info: "{{ $photo->creative_info }}"<br>
                    - Bulk Purchase: "{{ $photo->is_bulk_purchase }}"<br>
                    - Bulk Info: "{{ $photo->bulk_info }}"<br>
                    - Extra Bulk: "{{ $photo->extra_bulk }}"<br>
                    - Canvas: "{{ $photo->is_canvas }}"<br>
                    - Gift Product: "{{ $photo->is_gift_product }}"<br>
                    - Gift Product ID: "{{ $photo->gift_product_id ?? 'null' }}"<br>
                    - Varient ID: "{{ $photo->varient_id ?? 'null' }}"
                </div>

                {{-- Test Creative Arts --}}
                @if($photo->is_creative_art == "yes" && !empty($photo->creative_info))
                    <h4 style="color: green;">✅ Creative Arts Processing</h4>
                    @php
                        $cdata = explode(",", $photo->creative_info);
                        $creativeItems = \App\Models\CreativeArt::whereIn('id', $cdata)->get();
                    @endphp
                    <p>Creative IDs: {{ implode(', ', $cdata) }}</p>
                    <p>Items found: {{ $creativeItems->count() }}</p>
                    @foreach($creativeItems as $creative)
                        <li>{{ $creative->title }} - ${{ $creative->price }}</li>
                    @endforeach
                @else
                    <h4 style="color: red;">❌ Creative Arts Skipped</h4>
                    <p>Condition: is_creative_art == "yes" ({{ $photo->is_creative_art == "yes" ? 'true' : 'false' }}) && !empty(creative_info) ({{ !empty($photo->creative_info) ? 'true' : 'false' }})</p>
                @endif

                {{-- Test Bulk Purchase --}}
                @if($photo->is_bulk_purchase == "yes" && !empty($photo->bulk_info))
                    <h4 style="color: green;">✅ Bulk Purchase Processing</h4>
                    @php
                        $cartexplode = array_map('trim', explode(",", $photo->extra_bulk ?? ''));
                        $bulkdata = explode(",", $photo->bulk_info);
                        $bulkItems = \App\Models\BulkPurchase::whereIn('id', $bulkdata)->get();
                    @endphp
                    <p>Bulk IDs: {{ implode(', ', $bulkdata) }}</p>
                    <p>Quantities: {{ implode(', ', $cartexplode) }}</p>
                    <p>Items found: {{ $bulkItems->count() }}</p>
                    @foreach($bulkItems as $bulkIndex => $bulk)
                        @php
                            $quantity = $cartexplode[$bulkIndex] ?? 1;
                            $totalPrice = $bulk->price * $quantity;
                        @endphp
                        <li>{{ $bulk->title }} - ${{ $totalPrice }} ({{ $quantity }} × ${{ $bulk->price }})</li>
                    @endforeach
                @else
                    <h4 style="color: red;">❌ Bulk Purchase Skipped</h4>
                    <p>Condition: is_bulk_purchase == "yes" ({{ $photo->is_bulk_purchase == "yes" ? 'true' : 'false' }}) && !empty(bulk_info) ({{ !empty($photo->bulk_info) ? 'true' : 'false' }})</p>
                @endif

                {{-- Test Canvas --}}
                @if($photo->is_canvas == "yes")
                    <h4 style="color: green;">✅ Canvas Processing</h4>
                @else
                    <h4 style="color: red;">❌ Canvas Skipped</h4>
                    <p>Condition: is_canvas == "yes" ({{ $photo->is_canvas == "yes" ? 'true' : 'false' }})</p>
                @endif

                {{-- Test Gift Products --}}
                @if($photo->is_gift_product == "yes" && !empty($photo->gift_product_id))
                    <h4 style="color: green;">✅ Gift Products Processing</h4>
                    @php
                        $giftdata = explode(",", $photo->gift_product_id);
                        $giftItems = \App\Models\GiftProduct::whereIn('id', $giftdata)->get();
                    @endphp
                    <p>Gift IDs: {{ implode(', ', $giftdata) }}</p>
                    <p>Items found: {{ $giftItems->count() }}</p>
                @else
                    <h4 style="color: red;">❌ Gift Products Skipped</h4>
                    <p>Condition: is_gift_product == "yes" ({{ $photo->is_gift_product == "yes" ? 'true' : 'false' }}) && !empty(gift_product_id) ({{ !empty($photo->gift_product_id) ? 'true' : 'false' }})</p>
                @endif
            </div>
        @endforeach
    @else
        <div class="debug" style="color: red;">
            <strong>No photos found!</strong>
        </div>
    @endif
</body>
</html>