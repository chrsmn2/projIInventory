<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Supplier;
use App\Models\Departement;

class AjaxController extends Controller
{
    public function checkName(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'name' => 'required|string',
            'id'   => 'nullable|integer',
        ]);

        $type = $request->input('type');
        $name = $request->input('name');
        $id   = $request->input('id');

        $map = [
            'item'        => ['model' => Item::class,       'column' => 'item_name',        'label' => 'Item'],
            'category'    => ['model' => Category::class,   'column' => 'category_name',    'label' => 'Category'],
            'unit'        => ['model' => Unit::class,       'column' => 'unit_name',        'label' => 'Unit'],
            'vendor'      => ['model' => Supplier::class,   'column' => 'supplier_name',    'label' => 'Vendor'],
            'supplier'    => ['model' => Supplier::class,   'column' => 'supplier_name',    'label' => 'Vendor'],
            'departement' => ['model' => Departement::class,'column' => 'departement_name', 'label' => 'Departement'],
            // add others if needed
        ];

        if (! isset($map[$type])) {
            return response()->json(['exists' => false, 'message' => 'Invalid type'], Response::HTTP_BAD_REQUEST);
        }

        $conf = $map[$type];
        $model = $conf['model'];
        $column = $conf['column'];
        $label = $conf['label'];

        $query = $model::where($column, $name);
        if ($id) $query->where('id', '!=', $id);

        $exists = $query->exists();

        $message = $exists
            ? "{$label} name already exists. Please choose a different name"
            : "{$label} name available";

        return response()->json([
            'exists' => $exists,
            'message' => $message,
        ]);
    }
}
