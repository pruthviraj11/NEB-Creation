<?php

namespace App\Services;

use App\Repositories\CreativeRepository;

class CreativeService

{
    protected CreativeRepository $creativeRepository;

    public function __construct(CreativeRepository $creativeRepository)
    {
        $this->creativeRepository = $creativeRepository;
    }
    public function create($userData)
    {
        $user = $this->creativeRepository->create($userData);
        return $user;
    }

    public function getCategory()
    {
        $category = $this->creativeRepository->getAllCategory();
        return $category;
    }
    public function getAllCreatives()
    {
        $useres = $this->creativeRepository->getAll();
        return $useres;
    }
    public function getCreativeInfo($id)
    {
        $user = $this->creativeRepository->find($id);
        return $user;
    }
    public function deleteCreative($id)
    {
        $deleted = $this->creativeRepository->delete($id);
        return $deleted;
    }
    public function updateCreative($id, $userData)
    {
        $updated = $this->creativeRepository->update($id, $userData);
        return $updated;
    }

    public function getAllSiteUser()
    {
        $useres = $this->creativeRepository->getAllSiteUser();
        return $useres;
    }
}
