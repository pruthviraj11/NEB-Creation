<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
// use App\Http\Requests\User\UpdateUserProfileRequest;
use Spatie\Permission\Models\Permission;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\OrderDetail;
use App\Models\TempCart;
use App\Models\Photography;
use App\Models\Contact;
use App\Services\RoleService;
use App\Services\UserService;
use App\Services\CategoryService;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ContactController extends Controller
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
        
        return view('content.apps.contact.contact_list');
    }


   public function getAll()
{
    $contact = Contact::where('status','1')->orderBy('id','desc')->get();
    
   

return DataTables::of($contact)
    ->addColumn('name', function ($row) {
        return $row->guest_id;
    })
    ->addColumn('name', function ($row) {
        return $row->name;
    })
    ->addColumn('email', function ($row) {
        return $row->email;
    })
    ->addColumn('message', function ($row) {
        return $row->message;
    })
   
   
    ->addColumn('actions', function ($row) {
        $encryptedId = encrypt($row->id);
       // $updateButton = "<a data-bs-toggle='tooltip' title='Edit' class='btn-sm border-info' href='" . route('app-order-view',$encryptedId) . "'><i class='text-warning' data-feather='eye'></i></a>";
        $deleteButton = "<a data-bs-toggle='tooltip' title='Delete' class='btn-sm border-danger confirm-delete' data-idos='$encryptedId' href='" . route('app-contact-destroy', $encryptedId) . "'><i class='text-danger' data-feather='trash-2'></i></a>";
        //return $updateButton . " " . $deleteButton;
        return $deleteButton;
    })
    ->rawColumns(['name', 'email','message','actions'])
    ->make(true);

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

    public function view($encrypted_id)
    {
        try {
           
            $id = decrypt($encrypted_id);

            $page_data['page_title'] = "Order Details";
            $page_data['form_title'] = "Order Details";

            $order = OrderDetail::where('id',$id)->first();
            $carts = TempCart::where('temp_carts.guest_id', $order->guest_id)
            ->join('photographies', 'temp_carts.photo_id', '=', 'photographies.id')
            ->select('temp_carts.*', 'photographies.title', 'photographies.slug', 'photographies.front_image','photographies.back_image')
            ->get();

           return view('content.apps.photography.order_view',compact('page_data','order','carts'));
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-order-list")->with('error', 'Error while view Order');
        }
    }

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
    public function destroy($encrypted_id)
    {
        try {
            $id = decrypt($encrypted_id);
           // $deleted = $this->categoryService->deleteCategory($id);
            $deleted = Contact::where('id',$id)->delete();
            if (!empty($deleted)) {
                return redirect()->route("app-contact-list")->with('success', 'Category Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Deleting Category');
            }
        } catch (\Exception $error) {
            return redirect()->route("app-contact-list")->with('error', 'Error while editing Category');
        }
    }


    public function remove_files($encrypted_id)
{
    // Your logic to delete the file
    // Example:

   


    $client = Client::findOrFail($encrypted_id);

     $filePath = public_path('clients/' . $client->image);
        $ClientData['image'] = NULL;

    
    
    

    if (File::exists($filePath)) 
    {
        File::delete($filePath);


        
       $updated = $this->categoryService->updateClient($encrypted_id, $ClientData);
        // Optionally update the database if needed

        return redirect()->back()->with('success', 'File deleted successfully.');
    }

    return redirect()->back()->with('error', 'File not found.');
}

    private function generateUniqueSlug($title, $id = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (
        Category::where('slug', $slug)
            ->when($id, function ($query) use ($id) {
            return $query->where('id', '!=', $id);
            })
            ->whereNull('deleted_at') // if soft deletes are used
            ->exists()
        ) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
        }

        return $slug;
    }




}
