<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Category;
use App\Models\CreativeArt;


class CreativeRepository
{
    public function find($id)
    {
        return CreativeArt::find($id);
    }

    public function create(array $data)
    {
        return CreativeArt::create($data);
    }
    public function getAllCategory()
    {
        $category = Category::where('parent_id', NULL)->get();
        return $category;
    }



    public function update($id, array $data)
    {
        return CreativeArt::where('id', $id)->update($data);
    }

    public function delete($id)
{

    return CreativeArt::where('id', $id)->delete();
   
    
}

    public function getAll()
    {
        //return Category::where('parent_id', NULL)->get();
        return CreativeArt::all();
    }

   
}
