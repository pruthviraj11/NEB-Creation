<?php
namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\TestimonialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
  protected TestimonialService $testimonialService;

  public function __construct(TestimonialService $testimonialService)
  {
    $this->testimonialService = $testimonialService;
  }

  public function index()
  {
    return view('content.apps.testimonial.testimonial_list');
  }

  public function getAll()
  {
    $testimonials = $this->testimonialService->getAll();

    return DataTables::of($testimonials)
      ->addColumn(
        'profile_pic',
        fn($row) =>
        $row->profile_pic
        ? "<img src='" . asset('uploads/testimonials/' . $row->profile_pic) . "' width='50' height='50' class='rounded-circle'/>"
        : '-'
      )
      ->addColumn('star', fn($row) => str_repeat('⭐', $row->star))
      ->addColumn(
        'status',
        fn($row) =>
        $row->status
        ? '<span class="badge bg-success">Active</span>'
        : '<span class="badge bg-secondary">Inactive</span>'
      )
      ->addColumn('actions', function ($row) {
        $id = encrypt($row->id);
        $edit = "<a class='btn-sm border-warning' href='" . route('app-testimonial-edit', $id) . "'><i class='text-warning' data-feather='edit'></i></a>";
        $delete = "<a class='btn-sm border-danger confirm-delete' href='" . route('app-testimonial-destroy', $id) . "'><i class='text-danger' data-feather='trash-2'></i></a>";
        return $edit . " " . $delete;
      })
      ->rawColumns(['profile_pic', 'status', 'actions'])
      ->make(true);
  }

  public function create()
  {
    $page_data = ['page_title' => 'Testimonial', 'form_title' => 'Add New Testimonial'];
    $testimonial = '';
    return view('content.apps.testimonial.testimonial_create-edit', compact('page_data', 'testimonial'));
  }

  public function store(Request $request)
  {
    // dd($request->all());
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'designation' => 'nullable|string|max:255',
      'message' => 'required|string',
      'star' => 'required|integer|min:1|max:5',
      'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);


    $data = $validated;
    if ($request->hasFile('profile_pic')) {
      $file = $request->file('profile_pic');
      $filename = time() . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('uploads/testimonials'), $filename);
      $data['profile_pic'] = $filename;
    }

    dd($data);
    $this->testimonialService->create($data);
    return redirect()->route('app-testimonial-list')->with('success', 'Testimonial Added Successfully');
  }

  public function edit($encrypted_id)
  {
    $id = decrypt($encrypted_id);
    $testimonial = $this->testimonialService->find($id);
    $page_data = ['page_title' => 'Testimonial', 'form_title' => 'Edit Testimonial'];

    return view('content.apps.testimonial.testimonial_create-edit', compact('page_data', 'testimonial'));
  }

  public function update(Request $request, $encrypted_id)
  {
    $id = decrypt($encrypted_id);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'designation' => 'nullable|string|max:255',
      'message' => 'required|string',
      'star' => 'required|integer|min:1|max:5',
      'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $data = $validated;

    $testimonial = $this->testimonialService->find($id);

    if ($request->hasFile('profile_pic')) {
      if ($testimonial->profile_pic && File::exists(public_path('uploads/testimonials/' . $testimonial->profile_pic))) {
        File::delete(public_path('uploads/testimonials/' . $testimonial->profile_pic));
      }

      $file = $request->file('profile_pic');
      $filename = time() . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('uploads/testimonials'), $filename);
      $data['profile_pic'] = $filename;
    }

    $this->testimonialService->update($id, $data);
    return redirect()->route('app-testimonial-list')->with('success', 'Testimonial Updated Successfully');
  }

  public function destroy($encrypted_id)
  {
    $id = decrypt($encrypted_id);
    $this->testimonialService->delete($id);
    return redirect()->route('app-testimonial-list')->with('success', 'Testimonial Deleted Successfully');
  }
}
