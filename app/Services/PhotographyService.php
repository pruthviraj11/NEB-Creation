<?php

namespace App\Services;

use App\Repositories\PhotographyRepository
;

class PhotographyService

{
    protected PhotographyRepository $photographyRepository;

    public function __construct(PhotographyRepository $photographyRepository)
    {
        $this->photographyRepository = $photographyRepository;
    }
    public function create($userData)
    {
        $user = $this->photographyRepository->create($userData);
        return $user;
    }
    public function getAllPhotos()
    {
        $useres = $this->photographyRepository->getAll();
        return $useres;
    }
    public function getphoto($id)
    {
        $user = $this->photographyRepository->find($id);
        return $user;
    }
    public function deletePhoto($id)
    {
        $deleted = $this->photographyRepository->delete($id);
        return $deleted;
    }
    public function updatePhoto($id, $userData)
    {
        $updated = $this->photographyRepository->update($id, $userData);
        return $updated;
    }

    public function getAllSiteUser()
    {
        $useres = $this->photographyRepository->getAllSiteUser();
        return $useres;
    }
}
