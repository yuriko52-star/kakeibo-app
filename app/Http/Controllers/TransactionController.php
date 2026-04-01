<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * 取引一覧　Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with('category')
        ->where('user_id', auth()->id())
        ->latest('spent_at')
        ->get();
        return view('transactions.index',compact('transactions'));
    }

    /**
     *  登録画面　Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->id())
         ->orderBy('type')
         ->orderBy('name')
         ->get();
         return view('transactions.create', compact('categories'));
    }

    /**
     * 登録処理　Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|integer|min:1',
            'memo' => 'nullable|string|max:255',
            'spent_at' => 'required|date',
        ]);
        $category = Category::findOrFail($request->category_id);
        // 他人のカテゴリを使えないようにする
        if($category->user_id !== auth()->id()) {
            abort(403);
        }

        // カテゴリの種別と取引の種別が一致しているか
        if($category->type !== $request->type) {
            return back()->withErrors([
                'category_id' => 'カテゴリの種別と取引の種別が一致していません。'
            ])->withInput();
        }

        Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'memo' => $request->memo,
            'spent_at' => $request->spent_at,
        ]);
        return redirect()->route('transactions.index')->with('success','取引を登録しました。');
    }

    /**
     * 詳細画面　Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        if($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        return view('transactions.show',compact('transaction'));
    }

    /**
     * 編集画面　Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        if($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        $categories = Category::where('user_id', auth()->id())
            ->orderBy('type')
            ->orderBy('name')
            ->get();
            return view('transactions.edit',compact('transaction','categories'));
    }

    /**
     * 更新処理　Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        if($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|integer|min:1',
            'memo' => 'nullable|string|max:255',
            'spent_at' => 'required|date',
        ]);
        $category = Category::findOrFail($request->category_id);

        // 他人のカテゴリを使えないようにする
        if($category->user_id !== auth()->id()) {
            abort(403);
        }
        // カテゴリ種別と取引種別の一致確認
        if($category->type !== $request->type) {
            return back()->withErrors([
                'category_id' => 'カテゴリの種別と取引の種別が一致していません。'
            ])->withInput();
        }
        $transaction->update([
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'memo' => $request->memo,
            'spent_at' => $request->spent_at,
        ]);
        return redirect()->route('transactions.index')->with('success','取引を更新しました。');
    }

    /**
     * 削除　Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        if($transaction->user_id !== 
            auth()->id()) {
            abort(403);
        }
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success','取引を削除しました。');
    }
}