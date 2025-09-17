<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService

{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function create($userData)
    {
        $user = $this->categoryRepository->create($userData);
        return $user;
    }

    public function getCategory()
    {
        $category = $this->categoryRepository->getAllCategory();
        return $category;
    }
    public function getAllCategory()
    {
        $useres = $this->categoryRepository->getAll();
        return $useres;
    }
    public function getClientInfo($id)
    {
        $user = $this->categoryRepository->find($id);
        return $user;
    }
    public function deleteCategory($id)
    {
        $deleted = $this->categoryRepository->delete($id);
        return $deleted;
    }
    public function updateCategory($id, $userData)
    {
        $updated = $this->categoryRepository->update($id, $userData);
        return $updated;
    }

    public function getAllSiteUser()
    {
        $useres = $this->categoryRepository->getAllSiteUser();
        return $useres;
    }
}
