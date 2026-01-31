<?php

namespace Modules\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Account\Models\Unit;

class UnitController extends Controller
{
    


    public function index()
    {
        
        $units = Unit::paginate(30);

        return view('Account::product.units.index', compact('units'));
    }

    public function create()
    {
        

        return view('Account::product.units.create');
    }

    public function store(Request $request): RedirectResponse
    {
        

        $request->validate([ 'name' => 'required']);

        Unit::create($request->all());

        return redirect()->route('account.units.index')->with('success', 'Unit Create Successful');
    }

    public function edit(Unit $unit)
    {
        


        return view('Account::product.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit): RedirectResponse
    {
        

        $request->validate([ 'name' => 'required']);

        $unit->update(['name' => $request->name]);

        return redirect()->route('account.units.index')->with('success', 'Unit Update Successful');
    }


    public function destroy($id)
    {
        

        try {
            Unit::destroy($id);

            return redirect()->route('account.units.index')->with('success', 'Unit Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
