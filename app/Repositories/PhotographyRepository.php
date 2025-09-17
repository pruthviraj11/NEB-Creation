<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Category;
use App\Models\Photography;



class PhotographyRepository
{
    public function find($id)
    {
        return Photography::find($id);
    }

    public function create(array $data)
    {
        return Photography::create($data);
    }

    public function update($id, array $data)
    {
        return Photography::where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return Photography::where('id', $id)->delete();
    }
    public function getAll()
    {
        // $role = auth()->user()->getRoleNames()->first();
        // return User::whereNotIn('id', User::role('User')->pluck('id'))->whereNull('deleted_at')->get();
        return Photography::all();
    }

   
}
