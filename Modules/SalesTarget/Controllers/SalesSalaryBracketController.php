<?php

namespace Modules\SalesTarget\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SalesTarget\Models\SalesSalaryBracket;

class SalesSalaryBracketController extends Controller
{
    public function index()
    {
        $brackets = SalesSalaryBracket::orderBy('min_percent')->get();
        return view('sales-salary-brackets.index', compact('brackets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_percent' => 'required|numeric|min:0',
            'max_percent' => 'nullable|numeric|gt:min_percent',
            'payout_percent' => 'required|numeric|min:0|max:100',
        ]);
        SalesSalaryBracket::create($request->all());
        return back()->with('success', 'Bracket যোগ হয়েছে।');
    }

    public function update(Request $request, SalesSalaryBracket $salesSalaryBracket)
    {
        $request->validate([
            'min_percent' => 'required|numeric|min:0',
            'max_percent' => 'nullable|numeric|gt:min_percent',
            'payout_percent' => 'required|numeric|min:0|max:100',
        ]);
        $salesSalaryBracket->update($request->all());
        return back()->with('success', 'Bracket আপডেট হয়েছে।');
    }

    public function destroy(SalesSalaryBracket $salesSalaryBracket)
    {
        $salesSalaryBracket->delete();
        return back()->with('success', 'Bracket মুছে ফেলা হয়েছে।');
    }
}