<?php

namespace Modules\Account\Controllers;

use App\Traits\CheckPermission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Account\Models\Category;

class CategoryController extends Controller
{
    


    public function index()
    {
        
        $categories = Category::paginate(30);

        return view('Account::product.categories.index', compact('categories'));
    }

    public function create()
    {
        


        return view('Account::product.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        

        $request->validate(['name' => 'required']);
        Category::create($request->all());

        return redirect()->route('account.categories.index')->with('success', 'Category Create Successful');
    }

    public function edit(Category $category)
    {
        


        return view('Account::product.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        


        $request->validate(['name' => 'required']);
        $category->update(['name' => $request->name]);

        return redirect()->route('account.categories.index')->with('success', 'Category Update Successful');
    }


    public function destroy($id)
    {
        

        try {
            Category::destroy($id);

            return redirect()->route('account.categories.index')->with('success', 'Category Successfully Deleted!');
        } catch (\Exception $ex) {
            return redirect()->back()->withMessage($ex->getMessage());
        }
    }
}
