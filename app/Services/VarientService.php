<?php

namespace App\Services;

use App\Repositories\VarientRepository;

class VarientService

{
    protected VarientRepository $varientRepository;

    public function __construct(VarientRepository $varientRepository)
    {
        $this->varientRepository = $varientRepository;
    }
    public function create($userData)
    {
        $user = $this->varientRepository->create($userData);
        return $user;
    }
    public function gift_create($userData)
    {
        $user = $this->varientRepository->gift_create($userData);
        return $user;
    }



    public function getCategory()
    {
        $category = $this->varientRepository->getAllCategory();
        return $category;
    }
    public function getAllVarient()
    {
        $useres = $this->varientRepository->getAll();
        return $useres;
    }
    public function getvarientInfo($id)
    {
        $user = $this->varientRepository->find($id);
        return $user;
    }
    public function deleteVarient($id)
    {
        $deleted = $this->varientRepository->delete($id);
        return $deleted;
    }
    public function updateVarient($id, $userData)
    {
        $updated = $this->varientRepository->update($id, $userData);
        return $updated;
    }

    public function getAllSiteUser()
    {
        $useres = $this->varientRepository->getAllSiteUser();
        return $useres;
    }

    public function deleteGift($id)
    {
        $deleted = $this->varientRepository->delete_gift($id);
        return $deleted;
    }


    public function updateGift($id, $userData)
    {
        $updated = $this->varientRepository->update_gift($id, $userData);
        return $updated;
    }
}
