<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;

use App\Http\Requests\Creative\CreateGiftProductRequest;
use App\Http\Requests\Creative\UpdateGiftProductRequest;
// use App\Http\Requests\User\UpdateUserProfileRequest;
use Spatie\Permission\Models\Permission;

use App\Models\Role;
use App\Models\User;
use App\Models\Category;
use App\Models\ProductVarient;
use App\Models\GiftProduct;
use App\Models\ProductVarientPrice;
use App\Services\RoleService;
use App\Services\UserService;
use App\Services\VarientService;

use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GiftProductController extends Controller
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

        return view('content.apps.creative.gift_list');
    }


   public function getAll()
{
    $gift = GiftProduct::where('status','1')->get();

return DataTables::of(source: $gift)
    ->addColumn('product_image', function ($row) {
        if (!empty($row->product_image) && Storage::disk('public')->exists($row->product_image)) {
            $url = Storage::url($row->product_image);
        } else {
            $url = asset('no_image/no_image.png');
        }
        return '<img src="' . $url . '" alt="Image" width="60" height="60">';
      })
->addColumn('product_name', function ($row) {
        return $row->product_name;
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
        $updateButton = "<a data-bs-toggle='tooltip' title='Edit' class='btn-sm border-warning' href='" . route('app-gift_product-edit', $encryptedId) . "'><i class='text-warning' data-feather='edit'></i></a>";
        $deleteButton = "<a data-bs-toggle='tooltip' title='Delete' class='btn-sm border-danger confirm-delete' data-idos='$encryptedId' href='" . route('app-gift_product-destroy', $encryptedId) . "'><i class='text-danger' data-feather='trash-2'></i></a>";
        return $updateButton . " " . $deleteButton;
    })
    ->rawColumns(['product_image','category', 'status', 'actions'])
    ->make(true);

}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $page_data['page_title'] = "Gift Product";
        $page_data['form_title'] = "Add New Gift Product";
        $varients = ProductVarient::where('status',1)->get();
        $gift = null;
    $product_varients = collect();




        return view('.content.apps.creative.gift_create-edit', compact('page_data', 'varients','gift','product_varients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGiftProductRequest $request)
{
    try {

        $giftPrice = ($request->is_varient == 0) ? $request->price : null;


        $giftData = [
            'product_name'    => $request->product_name,
            'product_price'   => $giftPrice,
            'product_varient' => $request->is_varient,
            'status'          => $request->get('status') == 'on' ? true : false,
        ];


        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos/original', $photoName);
            $giftData['product_image'] = 'photos/original/' . $photoName;
        }


        $gift_product = $this->varientService->gift_create($giftData);


        if ($request->is_varient == 1) {
            $varients = $request->varients;
            $varient_prices = $request->varient_price;

            if (!empty($varients) && !empty($varient_prices)) {
                foreach ($varients as $index => $variantId) {
                    $price = $varient_prices[$index] ?? 0;


                    ProductVarientPrice::create([
                        'gift_product_id' => $gift_product->id,
                        'gift_varient_id' => $variantId,
                        'price'           => $price,
                    ]);
                }
            }
        }

        // 6️⃣ Success message
        return redirect()->route("app-gift_product-list")->with('success', 'Gift Product Added Successfully');

    } catch (\Exception $error) {
        dd($error->getMessage());
        return redirect()->route("app-gift_product-list")->with('error', 'Error while adding Gift Product');
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
           $varients = ProductVarient::where('status',1)->get();
           $gift = GiftProduct::where('id',$id)->first();
           $product_varients = ProductVarientPrice::where('gift_product_id',$id)->get();

            $page_data['page_title'] = "Gift Product";
            $page_data['form_title'] = "Edit Gift Product";




            return view('/content/apps/creative/gift_create-edit', compact('page_data', 'varients','gift','product_varients'));
        } catch (\Exception $error) {
            dd($error->getMessage());
            return redirect()->route("app-gift_product-list")->with('error', 'Error while editing Bulk Data');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateGiftProductRequest $request
     * @param $encrypted_id
     * @return \Illuminate\Http\RedirectResponse
     */


   public function update(UpdateGiftProductRequest $request, $encrypted_id)
{
    try {
        $id = decrypt($encrypted_id);

        $giftPrice = ($request->is_varient == 0) ? $request->price : null;

        $giftData = [
            'product_name'    => $request->product_name,
            'product_price'   => $giftPrice,
            'product_varient' => $request->is_varient,
            'status'          => $request->get('status') == 'on',
        ];

        // Handle image update
        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos/original', $photoName);
            $giftData['product_image'] = 'photos/original/' . $photoName;
        }

        // Update main gift record
        $gift_product = $this->varientService->updateGift($id, $giftData);

        // Handle variant records if applicable
        if ($request->is_varient == 1) {
            $variantRowIds = $request->variant_row_id ?? []; // existing IDs
            $variants = $request->varients ?? [];
            $prices = $request->varient_price ?? [];
            $deletedVariantIds = $request->deleted_variant_ids ?? [];

            // 1️⃣ Delete removed variants
            if (!empty($deletedVariantIds)) {
                ProductVarientPrice::whereIn('id', $deletedVariantIds)->delete();
            }

            // 2️⃣ Loop through all variants (existing + new)
            foreach ($variants as $index => $variantId) {
                $price = $prices[$index] ?? 0;
                $variantRowId = $variantRowIds[$index] ?? null;

                if ($variantRowId) {
                    // ✅ Update existing record
                    ProductVarientPrice::where('id', $variantRowId)
                        ->update([
                            'gift_varient_id' => $variantId,
                            'price'           => $price,
                        ]);
                } else {
                    // ✅ Insert new record
                    ProductVarientPrice::create([
                        'gift_product_id' => $id,
                        'gift_varient_id' => $variantId,
                        'price'           => $price,
                    ]);
                }
            }
        } else {
            // If product no longer has variants, delete all variant prices
            ProductVarientPrice::where('gift_product_id', $id)->delete();
        }

        return redirect()->route("app-gift_product-list")
            ->with('success', 'Gift Product Updated Successfully');
    } catch (\Exception $error) {
        dd($error->getMessage());
        return redirect()->route("app-gift_product-list")
            ->with('error', 'Error while editing Gift Product');
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
            $deleted = $this->varientService->deleteGift($id);
            if (!empty($deleted)) {
                return redirect()->route("app-gift_product-list")->with('success', 'Gift Product Deleted Successfully');
            } else {
                return redirect()->back()->with('error', 'Error while Deleting Gift Product');
            }
        } catch (\Exception $error) {
            return redirect()->route("app-gift_product-list")->with('error', 'Error while editing Gift Product');
        }
    }


    public function remove_files($encrypted_id)
{
    
    $photo = GiftProduct::findOrFail($encrypted_id);

    $filePath = $photo->product_image;

    if ($filePath && Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($filePath);

        $photo->update([
            'product_image' => null,
        ]);

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
