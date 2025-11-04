<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\RawMaterial;
use App\Models\Product\Order;


class RawMaterialController extends Controller
{
    // Show the form to create a new raw material

    public function list() {
        return response()->json(RawMaterial::all(['id','name','quantity','unit']));
    }


    // Store the new raw material in the database
public function store(Request $request)
{
    $request->validate([
        'id' => 'required|integer|unique:raw_materials,id',
        'name' => 'required|string|max:100',
        'unit' => 'required|string|max:10',
    ]);

    $material = RawMaterial::create([
        'id' => $request->id,
        'name' => $request->name,
        'quantity' => $request->quantity ?? 0,
        'unit' => $request->unit,
    ]);

    // Return JSON instead of redirect
    return response()->json($material);
}




    // Show all raw materials
    public function index()
    {
        $rawMaterials = RawMaterial::orderBy('id', 'asc')->get();
        return view('admins.stock', compact('rawMaterials'));
    }



    // Place an order using raw materials
    public function orderProduct(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        foreach ($product->rawMaterials as $material) {
            if ($material->quantity < ($material->pivot->quantity_required * $quantity)) {
                return back()->with('error', $material->name . ' is not enough!');
            }
        }

        foreach ($product->rawMaterials as $material) {
            $material->quantity -= $material->pivot->quantity_required * $quantity;
            $material->save();
        }

        Order::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price * $quantity,
            'status' => 'Pending'
        ]);

        return back()->with('success', 'Order placed and stock updated!');
    }

// Show raw material stock
public function viewRawMaterials()
{
    $rawMaterials = \App\Models\RawMaterial::orderBy('id', 'asc')->get();
    return view('admins.stock', compact('rawMaterials'));
}



// Update raw material quantity
public function updateRawMaterial(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'unit' => 'required|string|max:10',
        'new_id' => 'nullable|integer|unique:raw_materials,id',
    ]);

    $material = RawMaterial::findOrFail($id);

    // Update name and unit
    $material->name = $request->name;
    $material->unit = $request->unit;

    // Update ID if provided and unique
    if ($request->has('new_id')) {
        $material->id = $request->new_id;
    }

    $material->save();

    return response()->json($material);
}

public function destroy($id)
{
    $material = RawMaterial::findOrFail($id);

    if ($material->quantity > 0) {
        return redirect()->back()->with('delete', 'Cannot delete a material that still has stock!');
    }

    $material->delete();

    return redirect()->back()->with('success', 'Material deleted successfully!');
}
public function addStock(Request $request, $id)
{
    $material = RawMaterial::findOrFail($id);

    // convert to base unit if needed
    $qty = floatval($request->quantity);

    $material->quantity += $qty;
    $material->save();

    return redirect()->back()->with('success', 'Stock added successfully!');
}

public function reduceStock(Request $request, $id)
{
    $material = RawMaterial::findOrFail($id);

    $qty = floatval($request->quantity);

    if ($qty > $material->quantity) {
        return redirect()->back()->with('error', 'Not enough stock to reduce!');
    }

    $material->quantity -= $qty;
    $material->save();

    return redirect()->back()->with('success', 'Stock reduced successfully!');
}




}
