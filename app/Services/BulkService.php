<?php

namespace App\Services;

use App\Repositories\BulkRepository;

class BulkService

{
    protected BulkRepository $bulkRepository;

    public function __construct(BulkRepository $bulkRepository)
    {
        $this->bulkRepository = $bulkRepository;
    }
    public function create($userData)
    {
        $user = $this->bulkRepository->create($userData);
        return $user;
    }

    public function getCategory()
    {
        $category = $this->bulkRepository->getAllCategory();
        return $category;
    }
    public function getAllBulks()
    {
        $useres = $this->bulkRepository->getAll();
        return $useres;
    }
    public function getBulkInfo($id)
    {
        $user = $this->bulkRepository->find($id);
        return $user;
    }
    public function deleteBulk($id)
    {
        $deleted = $this->bulkRepository->delete($id);
        return $deleted;
    }
    public function updateBulk($id, $userData)
    {
        $updated = $this->bulkRepository->update($id, $userData);
        return $updated;
    }

    public function getAllSiteUser()
    {
        $useres = $this->bulkRepository->getAllSiteUser();
        return $useres;
    }
}
