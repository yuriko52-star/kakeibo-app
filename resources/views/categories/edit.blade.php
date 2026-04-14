<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カテゴリ編集
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-white shadow rounded p-6">
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf   
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1 font-medium">カテゴリ名</label>
                    <input type="text" name="name" value="{{ old('name',$category->name) }}" class="w-full border rounded px-3 py-2">
                   @error('name')
                    <div class="text-red-500 text-sm mt-1">{{$message}}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">種別</label>
                    <select name="type" class="w-full border rounded px-3 py-2">
                        <option value="">選択してください</option>
                        <option value="income"{{ old('type',$category->type) === 'income' ? 'selected' : '' }}>収入</option>
                        <option value="expense"{{ old('type',$category->type) === 'expense' ? 'selected' : '' }}>支出</option>
                    </select>


                @error('type')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    更新
                    </button>

                    <a href="{{ route('categories.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">戻る</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>