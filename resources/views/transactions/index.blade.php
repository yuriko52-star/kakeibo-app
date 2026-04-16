<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center mb-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">取引一覧</h2>
            <a href="{{ route('categories.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                カテゴリ一覧へ
            </a>
        </div>
            
    </x-slot>
<div class="flex flex-col items-center">
    <div class="flex mt-4 mx-auto items-center">
    
    <form action="{{route('transactions.index')}}" class="m-6 flex gap-4" method="GET">
        <select name="type" id="" class="text-gray-500 border text-left rounded pr-7 ">
            <option value="">すべて</option>
            <option value="income" {{request('type')=== 'income' ? 'selected' : '' }}>収入</option>
            <option value="expense" {{request('type')=== 'expense' ? 'selected' : '' }}>支出</option>
        </select>

        <input type="number" name="month" min="1" max="12" 
        value="{{ request('month') }}"
        placeholder="月（1～12)" class="border rounded px-3 py-2">
        <button class="bg-blue-500 text-white px-4 py-2 rounded">検索
        </button> 
    </form>
    </div>
    <div class="flex flex-col md:flex-row mb-4 p-4 bg-gray-100 rounded mx-auto">
        <h2 class="font-bold md:mr-4">
         {{ request('month') ? request('month').'月' : '今月'}}の収支  
        </h2>
        <div class="flex flex-wrap gap-4">
            <p>収入合計:<span class="text-green-600 font-semibold mr-2">
                {{ number_format($totalIncome) }}円
                </span>
            </p>
            <p>支出合計:<span class="text-red-600 font-semibold mr-2">
                {{ number_format($totalExpense) }}円
            </span></p>
            <p>残高:<span>{{ number_format($balance) }}円</span>
            </p>
        </div>
    </div>    
</div>
<div class="md:max-w-xl  mx-4 bg-white p-4 border rouded shadow md:mx-auto">
    <h3 class="font-bold mb-2">カテゴリ別支出</h3>
    @forelse($categoryTotals as $item)
        <div class="flex justify-between border-b py-1">
            <span>{{$item->category->name}}</span>
            <span class="text-red-500">
                {{ number_format($item->total) }}円
            </span>
        </div>
        @empty
            <p class="text-gray-500">データがありません</p>
        @endforelse
</div>


    <div class="py-8 max-w-6xl mx-auto px-4">
        @if(session('success'))
        <div class="mb-4 p-s bg-green-100 text-green-800 rounded">
         {{ session('success') }} 
        </div>
        @endif
        <div class="mb-4">
            <a href="{{ route('transactions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + 取引を追加
            </a>
        </div>
        <div class="md:hidden space-y-4">
            @foreach($transactions as $transaction)
                <div class="bg-white p-4 rouded shadow">
                    <p><strong>日付:</strong>{{ $transaction->spent_at }}</p>
                    <p><strong>種別:</strong>{{ $transaction->type === 'income' ? '収入' : '支出'}}</p>
                    <p><strong>カテゴリ:</strong>{{ $transaction->category->name }}</p>
                    <p><strong>金額:</strong>{{ number_format($transaction->amount) }}円</p>
                    <p><strong>メモ:</strong>{{ $transaction->memo }}</p>
                    <div class="flex gap-4 mt-2">
                        <a href="{{ route('transactions.edit', $transaction) }}" class="text-blue-600">編集</a>

                        <form action="{{ route('transactions.destroy',$transaction) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">削除</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="hidden md:block bg-white shadow rounded overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">日付</th>
                        <th class="px-4 py-3 text-left">種別</th>
                        <th class="px-4 py-3 text-left">カテゴリ</th>
                        <th class="px-4 py-3 text-left">金額</th>
                        <th class="px-4 py-3 text-left">メモ</th>
                        <th class="px-4 py-3 text-left">操作</th>
                    </tr>
                </thead>

                <tbody>
                 @forelse($transactions as $transaction)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $transaction->spent_at}}</td>
                        <td class="px-4 py-3">
                            {{ $transaction->type === 'income' ? '収入' : '支出' }}</td>
                        <td class="px-4 py-3">{{ $transaction->category->name }}</td>
                        <td class="px-4 py-3">{{ number_format($transaction->amount) }}円</td>
                        <td class="px-4 py-3">{{ $transaction->memo }}</td>
                        <td class="px-4 py-3 flex gap-3">
                            <a href="{{ route('transactions.edit',$transaction) }}" class="text-blue-600 hover:underline">編集</a>

                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            
                                <button type="submit" class="text-red-600 hover:underline">削除</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 text-gray-500">
                            取引がまだありません。
                        </td>
                        
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- ページネーション -->
        <div class="mt-4">
            {{ $transactions->links()}} 
        </div>
    </div>   
</x-app-layout>