<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug-email/{guestId}', function($guestId) {
    try {
        // Get the same data as the Controller
        $orderDetail = \App\Models\OrderDetail::where('guest_id', $guestId)->first();
        
        $photos = \App\Models\TempCart::where('temp_carts.guest_id', $guestId)
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
            $order = $photos->first();
            $order->fname = $orderDetail->fname;
            $order->lname = $orderDetail->lname;
            $order->order_type = $orderDetail->order_type;
            $order->transaction_id = $orderDetail->transaction_id;
            $order->total_amount = $orderDetail->total_amount;
            $order->order_status = $orderDetail->order_status;
            $order->product_total = $orderDetail->product_total ?? null;
            $order->delivery_charge = $orderDetail->delivery_charge ?? null;
            $order->tax_rate = $orderDetail->tax_rate ?? null;
            $order->tax_amount = $orderDetail->tax_amount ?? null;
            $order->total_before_discount = $orderDetail->total_before_discount ?? null;
            $order->discount_amount = $orderDetail->discount_amount ?? null;
            $order->promo_code = $orderDetail->promo_code ?? null;

            // Debug: Show what we have for each photo
            $debug = '';
            foreach($photos as $index => $photo) {
                $debug .= "Item " . ($index + 1) . ": " . $photo->title . "\n";
                $debug .= "  - Creative: " . $photo->is_creative_art . " (" . $photo->creative_info . ")\n";
                $debug .= "  - Bulk: " . $photo->is_bulk_purchase . " (" . $photo->bulk_info . ")\n";
                $debug .= "  - Canvas: " . $photo->is_canvas . "\n";
                $debug .= "  - Gift: " . $photo->is_gift_product . "\n\n";
            }
            
            // Return the actual rendered email template
            return view('emails.order-details', compact('order', 'photos'))->with('debug', $debug);
        }
        
        return 'No data found for guest ID: ' . $guestId;
        
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . '<br>Trace: ' . $e->getTraceAsString();
    }
})->name('debug-email');