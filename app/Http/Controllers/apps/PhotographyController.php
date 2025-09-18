<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;

use App\Http\Requests\Photography\CreatePhotographyRequest;
use App\Http\Requests\Photography\UpdatePhotographyRequest;
// use App\Http\Requests\User\UpdateUserProfileRequest;
// use Intervention\Image\Image;
use Spatie\Permission\Models\Permission;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\Photography;

use App\Services\RoleService;
use App\Services\UserService;
use App\Services\PhotographyService;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Intervention\Image\Facades\Image;
use Intervention\Image\Laravel\Facades\Image;

class PhotographyController extends Controller
{
    protected UserService $userService;
    protected RoleService $roleService;

    protected PhotographyService $photographyService;

    

    public function __construct(PhotographyService $photographyService)
    {
        // $this->userService = $userService;
        // $this->roleService = $roleService;

        $this->photographyService = $photographyService;
       
        // $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index', 'show']]);
        // $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:category-delete', ['only' => ['destroy']]);

        // Permission::create(['name' => 'category-list', 'guard_name' => 'web', 'module_name' => 'Blog Category']);
        // Permission::create(['name' => 'category-create', 'guard_name' => 'web', 'module_name' => 'Blog Category']);
        // Permission::create(['name' => 'category-edit', 'guard_name' => 'web', 'module_name' => 'Blog Category']);
        // Permission::create(['name' => 'category-delete', 'guard_name' => 'web', 'module_name' => 'Blog Category']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
       
        return view('content.apps.photography.photography_list');
    }


   public function getAll()
{
    $photos = $this->photographyService->getAllPhotos();


    return DataTables::of($photos)
        ->addColumn('back_image', function ($row) {
        if (!empty($row->back_image) && Storage::disk('public')->exists($row->back_image)) {
            $url = Storage::url($row->back_image);
        } else {
            $url = asset('no_image/no_image.png');
        }
        return '<img src="' . $url . '" alt="Image" width="60" height="60">';
      })
        ->addColumn('title', function ($row) {
            return $row->title;
        })

        ->addColumn('price', function ($row) {
            return $row->price;
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

            $updateButton = "<a data-bs-toggle='tooltip' title='Edit' class='btn-sm border-warning' href='" . route('app-photography-edit', $encryptedId) . "'><i class='text-warning' data-feather='edit'></i></a>";

            $deleteButton = "<a data-bs-toggle='tooltip' title='Delete' class='btn-sm border-danger confirm-delete' data-idos='$encryptedId' href='" . route('app-photography-destroy', $encryptedId) . "'><i class='text-danger' data-feather='trash-2'></i></a>";

            return $updateButton . " " . $deleteButton;
        })
        ->rawColumns(['back_image','title','price', 'status', 'actions'])
        ->make(true);
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $page_data['page_title'] = "Photography";
        $page_data['form_title'] = "Add New Photography";
        $categories = Category::whereNull('parent_id')->get();
        $parentCat = '';
        $photograpghy = '';
      
        return view('.content.apps.photography.photography_create-edit', compact('page_data', 'categories','parentCat','photograpghy'));
    }

    public function getParentCategory($catId)
  {
    $parentCategories = Category::where('parent_id', $catId)->get();
    return response()->json($parentCategories);
  }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePhotographyRequest $request)
    {
        try {
             $slug = $this->generateUniqueSlug($request->get('title'));

            $subcategory = $request->parent_category;
            $ParentcategoryId = $subcategory != '' ? $subcategory : NULL;

            $Photography['title'] = $request->get('title');
            $Photography['slug'] = $slug;
            $Photography['category_id'] = $request->get('category');
            $Photography['parent_id'] = $ParentcategoryId;
            $Photography['price'] = $request->get('price');
            $Photography['discount_price'] = $request->get('discount_price');
            $Photography['description'] = $request->get('description');
            $Photography['short_description'] = $request->get('short_description');
            $Photography['is_home'] = $request->get('is_home');
            $Photography['status'] = $request->get('status') == 'on' ? true : false;


        //     if ($request->hasFile('image')) {
        //     $photo = $request->file('image');
        //     $photoName = time() . '.' . $photo->getClientOriginalExtension();
        //     $photo->storeAs('public/photos/original', $photoName);
        //     $Photography['back_image'] = 'photos/original/' . $photoName;

        //    // Prepare watermark filename
        //     $watermarkedName = time() . '_watermarked.' . $photo->getClientOriginalExtension();

        //     // Create image instance
        //     $image = Image::make($photo->getRealPath());

        //     // Add watermark text
        //     $image->text('NEB Creation', $image->width() - 150, $image->height() - 30, function($font) {
                
        //         $font->size(24);
        //         $font->color([255, 255, 255, 0.7]); // white with transparency
        //         $font->align('center');
        //         $font->valign('bottom');
        //     });

        //     // Save watermarked image to same structure as original
        //     $image->save(storage_path('public/photos/watermark/' . $watermarkedName));
        //     $Photography['front_image'] = 'photos/watermark/' . $watermarkedName;



            if ($request->hasFile('image')) {
                $photo = $request->file('image');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $watermarkedName = time() . '_watermarked.' . $photo->getClientOriginalExtension();
                
                // Store original image
                $photo->storeAs('public/photos/original', $photoName);
                $Photography['back_image'] = 'photos/original/' . $photoName;

              
                $image = Image::read($photo->getRealPath()); // In v3 use read()

                //$imageLogo = asset('home/logo/neb_logo.png');
                
                $watermark = Image::read(public_path('home/logo/neb.png'));
                
                 // Optional: resize watermark relative to main image
                $watermark->resize(100, 100);

             $image->place($watermark, 'center');

             
                // $image->text('NEB Creation', $image->width() / 2, $image->height() / 2, function ($font) {
                    
                //     $font->filename(public_path('fonts/arialbd.ttf')); 
                //     $font->size(750);
                //     $font->color('rgba(255, 255, 255, 0.5)'); 
                //     $font->align('center'); 
                //     $font->valign('middle'); 
                // });

             
                $path = storage_path('app/public/photos/watermark/'. $watermarkedName);
                $image->save($path);

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $Photography['front_image'] = 'photos/watermark/' . $watermarkedName;
            }
        


    
            $add_photos = $this->photographyService->create($Photography);
           
       
            if (!empty($add_photos)) {
                return redirect()->route("app-photography-list")->with('success', 'Photography Added Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Adding Category');
            }
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-photography-list")->with('error', 'Error while adding Category');
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
            $photograpghy = $this->photographyService->getphoto($id);
           
            $page_data['page_title'] = "Photography";
            $page_data['form_title'] = "Edit Photography";

            $categories = Category::whereNull('parent_id')->get();

            // $parentCat = $photograpghy->parent_id != ''
            // ? Category::where('id',$photograpghy->parent_id)->first()
            // : '';

            $parentCat = $photograpghy->parent_id != ''
        ? Category::where('parent_id', Category::where('id', $photograpghy->parent_id)->first()->parent_id)->get()
        : '';
           

           // $photoUrl = $category ? asset('blog_category/' . $category->image) : '';

           
            return view('/content/apps/photography/photography_create-edit', compact('page_data', 'categories','photograpghy','parentCat'));
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-photography-list")->with('error', 'Error while editing Category');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePhotographyRequest $request
     * @param $encrypted_id
     * @return \Illuminate\Http\RedirectResponse
     */


    public function remove_files($encrypted_id, $file_type)
  {
    $photo = Photography::findOrFail($encrypted_id);
    $PhotoData = [];

    if ($file_type == "banner") {
      $filePath = $photo->back_image;
      $PhotoData['back_image'] = NULL;

      $waterPath = $photo->front_image;
      $PhotoData['front_image'] = NULL;
    }

   
    if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
      Storage::delete($filePath);
      Storage::delete($waterPath);
      $this->photographyService->updatePhoto($encrypted_id, $PhotoData);
      return redirect()->back()->with('success', 'File deleted successfully.');
    }

    return redirect()->back()->with('error', 'File not found.');
  }

    public function update(UpdatePhotographyRequest $request, $encrypted_id)

    {
        try {
            // dd($request->all());
            $id = decrypt($encrypted_id);
            // $userData['username'] = $request->get('username');
            
           $slug = $this->generateUniqueSlug($request->get('title'));

           $subcategory = $request->parent_category;
            $ParentcategoryId = $subcategory != '' ? $subcategory : NULL;

            $Photography['title'] = $request->get('title');
            $Photography['slug'] = $slug;
            $Photography['category_id'] = $request->get('category');
            $Photography['parent_id'] = $ParentcategoryId;
            $Photography['price'] = $request->get('price');
            $Photography['discount_price'] = $request->get('discount_price');
            $Photography['description'] = $request->get('description');
            $Photography['short_description'] = $request->get('short_description');
            $Photography['is_home'] = $request->get('is_home');
            $Photography['status'] = $request->get('status') == 'on' ? true : false;


            // if ($request->hasFile('image')) {
            // $photo = $request->file('image');
            // $photoName = time() . '.' . $photo->getClientOriginalExtension();
            // $photo->storeAs('public/photos/original', $photoName);
            // $Photography['back_image'] = 'photos/original/' . $photoName;
            // }

            if ($request->hasFile('image')) {
                $photo = $request->file('image');
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $watermarkedName = time() . '_watermarked.' . $photo->getClientOriginalExtension();
                
                // Store original image
                $photo->storeAs('public/photos/original', $photoName);
                $Photography['back_image'] = 'photos/original/' . $photoName;

              
                $image = Image::read($photo->getRealPath()); // In v3 use read()
                
                $watermark = Image::read(public_path('home/logo/neb.png'));
                 $watermark->resize(100, 100);
                 $image->place($watermark, 'center');
                // $image->text('NEB Creation', $image->width() / 2, $image->height() / 2, function ($font) {
                    
                //     $font->filename(public_path('fonts/arialbd.ttf')); 
                //     $font->size(750);
                //     $font->color('rgba(255, 255, 255, 0.5)'); 
                //     $font->align('center'); 
                //     $font->valign('middle'); 
                // });

             
                $path = storage_path('app/public/photos/watermark/'. $watermarkedName);
                $image->save($path);

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $Photography['front_image'] = 'photos/watermark/' . $watermarkedName;
            }
           
            $updated = $this->photographyService->updatePhoto($id, $Photography);
          



            if (!empty($updated)) {
                return redirect()->route("app-photography-list")->with('success', 'Category Updated Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Updating Category');
            }
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-photography-list")->with('error', 'Error while editing Category');
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
            $deleted = $this->photographyService->deletePhoto($id);
            if (!empty($deleted)) {
                return redirect()->route("app-photography-list")->with('success', 'Photo Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Deleting Category');
            }
        } catch (\Exception $error) {
            return redirect()->route("app-photography-list")->with('error', 'Error while editing Photo');
        }
    }


    private function generateUniqueSlug($title, $id = null)
  {
    $slug = Str::slug($title);
    $originalSlug = $slug;
    $counter = 1;

    while (
      Photography::where('slug', $slug)
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
