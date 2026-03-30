<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            カテゴリ一覧
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4">
       @if(session('success'))
        <div class="mb-4 p-s bg-green-100 text-green-800 rounded">
            {{ session('success')}}
        </div>
       @endif
        <div class="mb-4">
            <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                + カテゴリ作成
            </a>
        </div>

        <div class="bg-white shadow rounded p-4">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">名前</th>
                        <th class="text-left py-2">種別</th>
                        <th class="text-left py-2">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- あとでforelseに直す -->
                    @foreach($categories as $category )
                    <tr class="border-b">
                        <td class="py-2">{{ $category->name }}</td>
                        <td class="py-2">
                            {{ $category->type === 'income' ? '収入' : '支出' }}
                        </td>
                        <!-- 編集リンクと削除ボタン -->
                        <td class="py-2 flex gap-2">

                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>