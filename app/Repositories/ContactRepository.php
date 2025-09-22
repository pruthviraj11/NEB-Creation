<?php

namespace App\Repositories;


use App\Models\Contact;



class ContactRepository
{
   

    public function create(array $data)
    {
        return Contact::create($data);
    }

    


    

    
    // public function delete($id)
    // {
    //     return Contact::where('id', $id)->delete();
    // }
    // public function getAll()
    // {
    //     // $role = auth()->user()->getRoleNames()->first();
    //     // return User::whereNotIn('id', User::role('User')->pluck('id'))->whereNull('deleted_at')->get();
    //     return Contact::all();
    // }




    

   
}
