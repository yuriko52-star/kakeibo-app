<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center mb-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">取引追加</h2>
            <a href="{{ route('categories.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                カテゴリ一覧へ
            </a>
        </div>
    </x-slot>
    
    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-white shadow rounded p-6">
        <form action="{{ route('transactions.store') }}" method="POST" novalidate class="bg-white shadow rounded p-6">
            @csrf
            <div class="mb-4">
                <label class="block mb-1 font-medium">日付</label>
                <input type="date" name="spent_at" value="{{ old('spent_at',date('Y-m-d')) }}" class="w-full border rounded px-3 py-2">
                    @error('spent_at')
                    <div class="text-red-500 text-sm mt-1">{{$message}}</div>
                    @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">種別</label>
                <select name="type"  class="w-full border rounded px-3 py-2">
                    <option value="">選択してください</option>
                    <option value="income" {{ old('type') === 'income' ? 'selected' : '' }} >収入</option>
                    <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}  >支出</option>
                </select>
                @error('type')
                <div class="text-red-500 text-sm mt-1">
                  {{$message}}  
                </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">カテゴリ</label>
                <select name="category_id"  class="w-full border rounded px-3 py-2">
                    <option value="">選択してください</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id}}"data-type="{{ $category->name}}"{{ old('category_id') == $category->id ? 'selected' : '' }}>
                          {{ $category->name }} ({{ $category->type === 'income' ? '収入' : '支出' }})
                        </option>
                    @endforeach
                </select>
                    @error('category_id')
                    <div class="text-red-500 text-sm mt-1">
                    {{$message}}</div>
                    @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">金額</label>
                <input type="number" name="amount" value="{{ old('amount') }}" class="w-full border rounded px-3 py-2">
                @error('amount')
                    <div class="text-red-500 text-sm mt-1">
                     {{ $message }}    
                    </div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">メモ</label>
                <input type="text" name="memo" value="{{ old('memo') }}" class="w-full border rounded px-3 py-2">
                    @error('memo')
                    <div class="text-red-500 text-sm mt-1">
                    {{$message}}
                    </div>
                    @enderror
                
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    登録する
                </button>
                <a href="{{ route('transactions.index') }}" class="bg-gray-300 px-4 py-2 rounded ">
                    戻る
                </a>
            </div>
        </form>
    </div>
</div>
    
</x-app-layout>
