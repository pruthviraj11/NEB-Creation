<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;

use App\Http\Requests\Creative\CreateCreativeRequest;
use App\Http\Requests\Creative\UpdateCreativeRequest;
// use App\Http\Requests\User\UpdateUserProfileRequest;
use Spatie\Permission\Models\Permission;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Services\RoleService;
use App\Services\UserService;
use App\Services\CreativeService;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CreativeController extends Controller
{
   

    protected CreativeService $creativeService;

    

    public function __construct(CreativeService $creativeService)
    {
        // $this->userService = $userService;
        // $this->roleService = $roleService;

        $this->creativeService = $creativeService;
       
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
        
        return view('content.apps.creative.creative_list');
    }


   public function getAll()
{
    $creative = $this->creativeService->getAllCreatives();

return DataTables::of(source: $creative)
    ->addColumn('title', function ($row) {
        return $row->title;
    })
    ->addColumn('price', function ($row) {
        return $row->price ? $row->price : '-';
    })
    ->addColumn('status', function ($row) {
        if ($row->status == 'active' || $row->status == 1) {
            return '<span class="badge bg-success">Active</span>';
        } else {
            return '<span class="badge bg-secondary">Inactive</span>';
        }
    })
    ->addColumn('actions', function ($row) {
        $encryptedId = encrypt($row->id);
        $updateButton = "<a data-bs-toggle='tooltip' title='Edit' class='btn-sm border-warning' href='" . route('app-creative-edit', $encryptedId) . "'><i class='text-warning' data-feather='edit'></i></a>";
        $deleteButton = "<a data-bs-toggle='tooltip' title='Delete' class='btn-sm border-danger confirm-delete' data-idos='$encryptedId' href='" . route('app-creative-destroy', $encryptedId) . "'><i class='text-danger' data-feather='trash-2'></i></a>";
        return $updateButton . " " . $deleteButton;
    })
    ->rawColumns(['category', 'status', 'actions'])
    ->make(true);

}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $page_data['page_title'] = "Creative";
        $page_data['form_title'] = "Add New Creative Art";
        $creative = '';


        
        return view('.content.apps.creative.create_create-edit', compact('page_data', 'creative'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCreativeRequest $request)
    {
        try {
           
          

            $creativeData['title'] = $request->title;
            $creativeData['price'] = $request->price;
            $creativeData['status'] = $request->get('status') == 'on' ? true : false;
           
            
           
            $category = $this->creativeService->create($creativeData);
           
       
            if (!empty($category)) {
                return redirect()->route("app-creative-list")->with('success', 'Creative Added Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Adding Creative');
            }
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-creative-list")->with('error', 'Error while adding Creative');
        }
    }



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
    public function edit($encrypted_id)
    {
        try {
            $id = decrypt($encrypted_id);
            $creative = $this->creativeService->getCreativeInfo($id);
      
            $page_data['page_title'] = "Creative";
            $page_data['form_title'] = "Edit Creative Arts";

           

           
            return view('/content/apps/creative/create_create-edit', compact('page_data', 'creative'));
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-category-list")->with('error', 'Error while editing Slider');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param $encrypted_id
     * @return \Illuminate\Http\RedirectResponse
     */


    public function update(UpdateCreativeRequest $request, $encrypted_id)

    {
        try {
            // dd($request->all());
            $id = decrypt($encrypted_id);
            // $userData['username'] = $request->get('username');
            
            $creativeData['title'] = $request->title;
            $creativeData['price'] = $request->price;
            $creativeData['status'] = $request->get('status') == 'on' ? true : false;

            $updated = $this->creativeService->updateCreative($id, $creativeData);
          



            if (!empty($updated)) {
                return redirect()->route("app-creative-list")->with('success', 'Creative Updated Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Updating Creative');
            }
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-creative-list")->with('error', 'Error while editing Creative');
        }
    }

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
            $deleted = $this->creativeService->deleteCreative($id);
            if (!empty($deleted)) {
                return redirect()->route("app-creative-list")->with('success', 'Creative Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Deleting Creative');
            }
        } catch (\Exception $error) {
            return redirect()->route("app-creative-list")->with('error', 'Error while editing Creative');
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


        
       $updated = $this->creativeService->updateClient($encrypted_id, $ClientData);
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
