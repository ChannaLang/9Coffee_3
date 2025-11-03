<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use App\Models\RawMaterial;

class ProductController extends Controller
{
    public function DisplayProducts(){
        $products = Product::select()->orderBy('id','asc')->get();
            return view('admins.allproducts',compact('products'));

    }

    public function CreateProducts(){
            return view('admins.createproducts');
    }

    public function StoreProducts(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products,name|max:100',
            'price' => 'required|numeric',
            'type' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'nullable',
        ]);

        $descriptionPath = public_path('assets/images');

                if (!file_exists($descriptionPath)) {
            mkdir($descriptionPath, 0775, true);}
        $myimage = time() . '_' . $request->image->getClientOriginalName();
        $request->image->move($descriptionPath, $myimage);


        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $myimage,
            'description' => $request->description,
            'type' => $request->type,
            'quantity' => $request->quantity ?? 0,
        ]);

        return Redirect::route('all.products')
            ->with(['success' => "Product created successfully!"]);
    }

    public function DeleteProducts($id){
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        if (File::exists(public_path('assets/images/' . $product->image))) {
            File::delete(public_path('assets/images/' . $product->image));
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Product deleted successfully']);
}


         public function EditProducts($id)
    {
        $product = Product::findOrFail($id);
        return view('admins.edit', compact('product'));
    }

        public function AjaxUpdateProducts(Request $request, $id){
            $product = Product::find($id);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found']);
            }

            $request->validate([
                'name' => 'required|max:100',
                'price' => 'required|numeric',
                'type' => 'required'
            ]);

            $product->name = $request->name;
            $product->price = $request->price;
            $product->type = $request->type;

            $product->save();

            return response()->json(['success' => true, 'message' => 'Product updated successfully']);
    }
        public function addMaterials(Request $request, Product $product){
            $data = $request->validate([
                'materials' => 'required|array',
                'materials.*' => 'numeric|min:0',
            ]);

            $syncData = [];
            foreach ($data['materials'] as $rawId => $qty) {
                if ($qty > 0) {
                    $syncData[$rawId] = ['quantity_required' => $qty];
                }
        }
            $product->rawMaterials()->sync($syncData);

            // ✅ Return JSON for AJAX
            return response()->json([
                'message' => 'Recipe updated successfully!'
            ]);
    }
// Get assigned materials for a product
public function getAssignedMaterials($id)
{
    $product = Product::with('rawMaterials')->findOrFail($id);

    $materials = $product->rawMaterials->map(function($mat) {
        return [
            'name' => $mat->name,
            'quantity_required' => $mat->pivot->quantity_required,
            'unit' => $mat->unit
        ];
    });

    return response()->json($materials);
}


    //     public function assignMaterials(Product $product){
    //         $rawMaterials = \App\Models\RawMaterial::all(); // fetch all raw materials
    //         return view('admins.assign_materials', compact('product', 'rawMaterials'));
    // }



}
