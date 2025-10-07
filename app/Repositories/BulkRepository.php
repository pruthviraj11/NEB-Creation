<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Category;
use App\Models\CreativeArt;
use App\Models\BulkPurchase;


class BulkRepository
{
    public function find($id)
    {
        return BulkPurchase::find($id);
    }

    public function create(array $data)
    {
        return BulkPurchase::create($data);
    }
  



    public function update($id, array $data)
    {
        return BulkPurchase::where('id', $id)->update($data);
    }

    public function delete($id)
{

    return BulkPurchase::where('id', $id)->delete();
   
    
}

    public function getAll()
    {
        //return Category::where('parent_id', NULL)->get();
        return BulkPurchase::all();
    }

   
}
