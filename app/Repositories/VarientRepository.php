<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Category;
use App\Models\CreativeArt;
use App\Models\BulkPurchase;
use App\Models\ProductVarient;
use App\Models\GiftProduct;


class VarientRepository
{
    public function find($id)
    {
        return ProductVarient::find($id);
    }

    public function create(array $data)
    {
        return ProductVarient::create($data);
    }

     public function gift_create(array $data)
    {
        return GiftProduct::create($data);
    }






    public function update($id, array $data)
    {
        return ProductVarient::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return ProductVarient::where('id', $id)->delete();
    }

    public function delete_gift($id)
{
    return GiftProduct::where('id', $id)->delete();
}



    public function getAll()
    {
        //return Category::where('parent_id', NULL)->get();
        return ProductVarient::all();
    }


    public function update_gift($id, array $data)
    {
        return GiftProduct::where('id', $id)->update($data);
    }





}
