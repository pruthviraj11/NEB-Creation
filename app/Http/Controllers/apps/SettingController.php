<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Setting\SettingRequest;
// use App\Http\Requests\User\UpdateUserProfileRequest;
use Spatie\Permission\Models\Permission;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\TempCart;
use App\Models\Photography;
use App\Models\Contact;
use App\Models\Setting;

use App\Services\RoleService;
use App\Services\UserService;
use App\Services\CategoryService;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SettingController extends Controller
{
   
    protected CategoryService $categoryService;

    

    public function __construct(CategoryService $categoryService)
    {
        // $this->userService = $userService;
        // $this->roleService = $roleService;

        $this->categoryService = $categoryService;
       
        // $this->middleware('permission:client-list|client-create|client-edit|client-delete', ['only' => ['index', 'show']]);
        // $this->middleware('permission:client-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:client-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:client-delete', ['only' => ['destroy']]);

        // Permission::create(['name' => 'category-list', 'guard_name' => 'web', 'module_name' => 'Category']);
        // Permission::create(['name' => 'category-create', 'guard_name' => 'web', 'module_name' => 'Category']);
        // Permission::create(['name' => 'category-edit', 'guard_name' => 'web', 'module_name' => 'Category']);
        // Permission::create(['name' => 'category-delete', 'guard_name' => 'web', 'module_name' => 'Category']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        
            $page_data['page_title'] = "Setting";
            $page_data['form_title'] = "Setting";

            $setting = Setting::first();
          
           return view('content.apps.setting.setting_view',compact('page_data','setting'));
       
        
        
       
    }


   


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
   

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    // public function store(CreateCategoryRequest $request)
    // {
    //     try {
           
    //         $subcategory = $request->category_id;
    //         $categoryId = $subcategory !== '' ? $subcategory : null;

    //         $slug = $this->generateUniqueSlug($request->get('category'));

    //         $categoryData['category'] = $request->category;
    //         $categoryData['parent_id'] = $categoryId;
    //         $categoryData['status'] = $request->get('status') == 'on' ? true : false;
           
            
           
    //         $category = $this->categoryService->create($categoryData);
           
       
    //         if (!empty($category)) {
    //             return redirect()->route("app-category-list")->with('success', 'Category Added Successfully');
    //         } else {
    //             return redirect()->back()->with('error', 'Error while Adding Category');
    //         }
    //     } catch (\Exception $error) {
    //         dd($error->getMessage());
    //         return redirect()->route("app-category-list")->with('error', 'Error while adding Category');
    //     }
    // }



    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    // public function edit($encrypted_id)
    // {
    //     try {
    //         $id = decrypt($encrypted_id);
    //         $category = $this->categoryService->getClientInfo($id);
    //          $categories = $this->categoryService->getCategory();


    //         $page_data['page_title'] = "Category";
    //         $page_data['form_title'] = "Edit Category";

           

           
    //         return view('/content/apps/category/category_create-edit', compact('page_data', 'category','categories'));
    //     } catch (\Exception $error) {
    //         dd($error->getMessage());
    //         return redirect()->route("app-category-list")->with('error', 'Error while editing Slider');
    //     }
    // }



    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param $encrypted_id
     * @return \Illuminate\Http\RedirectResponse
     */


    
    /**
     * Remove the specified resource from storage.
     *
     * @param $encrypted_id
     * @return \Illuminate\Http\RedirectResponse
     */


    public function update(SettingRequest $request)
    {
        try {
            $setting = Setting::first();

            if ($setting) {
                // update existing row
                $setting->update([
                    'admin_email'    => $request->get('admin_email'),
                    'partner_email'  => $request->get('partner_email'),
                    'printify_email' => $request->get('printify_email'),
                ]);
            } else {
                // insert new row
                Setting::create([
                    'admin_email'    => $request->get('admin_email'),
                    'partner_email'  => $request->get('partner_email'),
                    'printify_email' => $request->get('printify_email'),
                ]);
            }

            return redirect()->back()->with('success', 'Settings saved successfully.');
        } catch (\Exception $error) {
            return redirect()->back()->with('error', 'Error saving settings: ' . $error->getMessage());
        }
    }


    



}
