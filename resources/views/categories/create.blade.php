<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カテゴリ作成
        </h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto px-4">
        <div class="bg-white shadow rounded p-6">
            <form action="{{route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-medium">カテゴリ名</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2">
                    <!-- あとでバリデーションをつける -->
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">種別</label>
                    <select name="type"  class="w-full borer rounded px-3 py-2">
                        <option value="">選択してください</option>
                        <option value="income"{{ old('type')=== 'income' ? 'selected' : '' }}>収入</option>
                        <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>支出</option>
                    </select>
                    <!-- validation -->
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        保存
                    </button>

                    <a href="{{route('categories.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">戻る</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>