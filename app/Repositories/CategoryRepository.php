<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Category;


class CategoryRepository
{
    public function find($id)
    {
        return Category::find($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }
    public function getAllCategory()
    {
        $category = Category::where('parent_id', NULL)->get();
        return $category;
    }



    public function update($id, array $data)
    {
        return Category::where('id', $id)->update($data);
    }

    public function delete($id)
{
    $category = Category::find($id);
    $parentId = $category->parent_id;
    if($parentId != '') 
    {
        return Category::where('id', $id)->delete();
    }
    else
    {
        return Category::where('id', $id)->orWhere('parent_id',$id)->delete();
    }

    
}

    public function getAll()
    {
        //return Category::where('parent_id', NULL)->get();
        return Category::with('parentCategory')->get();
    }

   
}
