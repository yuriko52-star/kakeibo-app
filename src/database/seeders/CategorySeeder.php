<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // テストユーザー取得
        $user = User::first() ?? User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
         // 支出カテゴリ
         $expenseCategories = [
            '食費',
            '日用品',
            '光熱費',
            '通信費',
            '家賃',
            '医療費',
            '教育費',
            '娯楽',
            '交通費',
            'その他',
         ];

         foreach($expenseCategories as $name) {
            Category::firstOrCreate([
                'user_id' => $user->id,
                'name' => $name,
                'type' => 'expense',
            ]);
         }
         // 収入カテゴリ
         $incomeCategories = [
            '給料',
            '副収入',
            'ボーナス',
         ];
         foreach($incomeCategories as $name) {
            Category::firstOrCreate([
                'user_id' => $user->id,
                'name' => $name,
                'type' => 'income',
            ]);
         }
     }
}
