<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;

use App\Http\Requests\Creative\CreateVarientRequest;
use App\Http\Requests\Creative\UpdateVarientRequest;
// use App\Http\Requests\User\UpdateUserProfileRequest;
use Spatie\Permission\Models\Permission;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Services\RoleService;
use App\Services\UserService;
use App\Services\VarientService;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VarientController extends Controller
{
   
    protected VarientService $varientService;

    

    public function __construct(VarientService $varientService)
    {
        // $this->userService = $userService;
        // $this->roleService = $roleService;

        $this->varientService = $varientService;
       
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
        
        return view('content.apps.creative.varient_list');
    }


   public function getAll()
{
    $bulk = $this->varientService->getAllVarient();

return DataTables::of(source: $bulk)
    ->addColumn('title', function ($row) {
        return $row->title;
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
        $updateButton = "<a data-bs-toggle='tooltip' title='Edit' class='btn-sm border-warning' href='" . route('app-varient-edit', $encryptedId) . "'><i class='text-warning' data-feather='edit'></i></a>";
        $deleteButton = "<a data-bs-toggle='tooltip' title='Delete' class='btn-sm border-danger confirm-delete' data-idos='$encryptedId' href='" . route('app-varient-destroy', $encryptedId) . "'><i class='text-danger' data-feather='trash-2'></i></a>";
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
        $page_data['page_title'] = "Varient";
        $page_data['form_title'] = "Add New Varient";
        $varient = '';


        
        return view('.content.apps.creative.varient_create-edit', compact('page_data', 'varient'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateVarientRequest $request)
    {
        try {
      
            $varientData['title'] = $request->size;
            $varientData['status'] = $request->get('status') == 'on' ? true : false;
           
            $varient = $this->varientService->create($varientData);
           
       
            if (!empty($varient)) {
                return redirect()->route("app-varient-list")->with('success', 'Varient Added Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Adding Varient');
            }
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-varient-list")->with('error', 'Error while adding Varient');
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
            $varient = $this->varientService->getvarientInfo($id);
      
            $page_data['page_title'] = "Varient";
            $page_data['form_title'] = "Edit Varient";

           

           
            return view('/content/apps/creative/varient_create-edit', compact('page_data', 'varient'));
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-bulk-list")->with('error', 'Error while editing Bulk Data');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateVarientRequest $request
     * @param $encrypted_id
     * @return \Illuminate\Http\RedirectResponse
     */


    public function update(UpdateVarientRequest $request, $encrypted_id)

    {
        try {
            // dd($request->all());
            $id = decrypt($encrypted_id);
            // $userData['username'] = $request->get('username');
            
            $varientData['title'] = $request->size;
            $varientData['status'] = $request->get('status') == 'on' ? true : false;

            $updated = $this->varientService->updateVarient($id, $varientData);
          



            if (!empty($updated)) {
                return redirect()->route("app-varient-list")->with('success', 'Varient Updated Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Updating Varient');
            }
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-varient-list")->with('error', 'Error while editing Varient');
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
            $deleted = $this->varientService->deleteVarient($id);
            if (!empty($deleted)) {
                return redirect()->route("app-varient-list")->with('success', 'Varient Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Deleting Varient');
            }
        } catch (\Exception $error) {
            return redirect()->route("app-varient-list")->with('error', 'Error while editing Varient');
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


        
       $updated = $this->varientService->updateClient($encrypted_id, $ClientData);
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
