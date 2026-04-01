<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center mb-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">取引一覧</h2>
            <a href="{{ route('categories.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                カテゴリ一覧へ
            </a>
        </div>
            
    </x-slot>
    <div class="py-8 max-w-4xl mx-auto px-4">
       @if(session('success'))
        <div class="mb-4 p-s bg-green-100 text-green-800 rounded">
            {{ session('success')}}
        </div>
       @endif
        <div class="mb-4">
            <a href="{{ route('transactions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + 取引を追加
            </a>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
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
                        <td class="px-4 py-3">{{ $transaction->spent_at }}</td>
                        <td class="px-4 py-3">{{
                            $transaction->type === 'income' ? '収入' : '支出' }}</td>
                        <td class="px-4 py-3">{{ $transaction->category->name }}</td>
                        <td class="px-4 py-3">{{ number_format($transaction->amount) }}</td>
                        <td class="px-4 py-3">{{ $transaction->memo}}</td>
                        <td class="px-4 py-3 flex gap-3">
                            <a href="{{ route('transactions.edit', $transaction) }}" class="text-blue-600 hover:underline">編集</a>

                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('削除してもよろしいですか？');">
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
    </div>   
</x-app-layout>