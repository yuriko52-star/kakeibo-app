<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * 取引一覧　Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', auth()->id());
        if($request->filled('type')) {
            $query->where('type',$request->type);
        }
        if($request->filled('month')) {
            $query->whereMonth('spent_at', $request->month);
        }
        $transactions = $query->latest('spent_at')->paginate(5);
        $totalQuery = Transaction::where('user_id', auth()->id());
        if($request->filled('month')) {
            $totalQuery->whereMonth('spent_at',$request->month);
        }

        $categoryTotals = (clone $totalQuery)
            ->where('type', 'expense')
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $totalExpense = (clone $totalQuery)
            ->where('type','expense')
            ->sum('amount');
        $totalIncome = (clone $totalQuery)
            ->where('type', 'income')
            ->sum('amount');
        $balance = $totalIncome - $totalExpense;
        
          return view('transactions.index',compact(
                'categoryTotals',
                'transactions',
                'totalExpense',
                'totalIncome',
                'balance',
            ));
    }


    /**
     *  登録画面　Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->id())
            ->orderByRaw("
                CASE
                WHEN type = 'expense' THEN 1
                WHEN type = 'income' THEN 2
                END
                ")
            ->orderBy('name')
            ->get();
            return view('transactions.create',compact('categories'));
        /*$categories = Category::where('user_id',auth()->id())
         ->orderByRaw("
         CASE
         WHEN type = 'expense' THEN 1
         WHEN type = 'income' THEN 2
         END
         ")
        //  expense を先income を後に固定する
         ->orderBy('name')
         ->get();
         */
         
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
       ],[
            'amount.integer' => '数値で入力してください',
            'amount.min' => '1以上の数字で入力してください',
       ]);
        
       $category = Category::findOrFail($request->category_id);
         // チェック1.他人のカテゴリを使えないようにする
        if($category->user_id !== auth()->id()) {
            abort(403);
        }
         // チェック2. カテゴリの種別と取引の種別が一致しているか
        if($category->type !== $request->type) {
            return back()->withErrors([
                'category_id' => 'カテゴリの種類と取引の種類が一致していません',
            ])->withInput();
        }
        Transaction::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'type' =>$request->type,
            'amount' => $request->amount,
            'memo' => $request->memo,
            'spent_at' => $request->spent_at,
        ]);
        return redirect()->route('transactions.index')->with('success','取引を登録しました');
        
    }

    /**
     * 詳細画面　Display the specified resource.
     */
    /*public function show(Transaction $transaction)
    {
        if($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        return view('')
    }
    /**
     * 編集画面　Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        if($transaction->user_id !== auth()->id()) {
            abort(403);
        }
        $categories = Category::where('user_id',auth()->id())
        ->orderByRaw("
        CASE
        WHEN type = 'expense' THEN 1
        WHEN type = 'income' THEN 2
        END")
        ->orderBy('name')
        ->get();
        return view('transactions.edit',compact('transaction', 'categories'));
        /*$categories = Category::where('user_id', auth()->id())
            ->orderByRaw("
            CASE
            WHEN type = 'expense' THEN 1
            WHEN type = 'income' THEN 2
            END")
            ->orderBy('name')
            ->get();
        */
            
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
       ],[
            'amount.integer' => '数値で入力してください',
            'amount.min' => '1以上の数字で入力してください',
        ]);
        $category = Category::findOrFail($request->category_id);
        if($category->user_id !== auth()->id()) {
            abort(403);
        }
            // ↑　他人のカテゴリを使えないようにする
        if($category->type !== $request->type)
            {
                return back()->withErrors([
                    'category_id' => 'カテゴリの種別と取引の種類が一致していません',
                ])->withInput();
            }
          // ↑　カテゴリ種別と取引種別の一致確認
          $transaction->update([
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'memo' => $request->memo,
            'spent_at' => $request->spent_at,
          ]);
          return redirect()->route('transactions.index')->with('success','取引を更新しました'); 
    }

    /**
     * 削除　Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
       if($transaction->user_id !== auth()->id()) {
        abort(403);
        }
        // dd(request()->method(), request()->all());
        $transaction->delete();
        
        return redirect()->route('transactions.index')->with('success','取引を削除しました'); 
    }
}