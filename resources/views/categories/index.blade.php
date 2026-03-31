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
                    
                    @forelse ($categories as $category )
                    <tr class="border-b">
                        <td class="py-2">{{ $category->name }}</td>
                        <td class="py-2">
                            {{ $category->type === 'income' ? '収入' : '支出' }}
                        </td>
                        
                        <td class="py-2 flex gap-2">
                            <a href="{{route('categories.edit', $category)  }}" class="text-blue-600 hover:underline">編集</a>
                            <form action="{{ route('categories.destroy', $category)  }}" method="POST" onsubmit="return confirm('削除してもよろしいですか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"class="text-red-600 hover:underline">
                                    削除
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 text-gray-500">
                            カテゴリがまだありません。
                        </td>
                    </tr>

                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-app-layout>