<?php

namespace App\Services;

use App\Repositories\ContactRepository;

class ContactService

{
    protected ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }
    public function create($userData)
    {
        $user = $this->contactRepository->create($userData);
        return $user;
    }

    public function create_contact($userData)
    {
        $user = $this->contactRepository->create_contact($userData);
        return $user;
    }



    // // public function getAllServices()
    // // {
    // //     $useres = $this->contactRepository->getAll();
    // //     return $useres;
    // // }
   
    // public function deleteContact($id)
    // {
    //     $deleted = $this->contactRepository->delete($id);
    //     return $deleted;
    // }

    // public function deletePropertyContact($id)
    // {
    //     $deleted = $this->contactRepository->deletePropertyContact($id);
    //     return $deleted;
    // }
   
}
