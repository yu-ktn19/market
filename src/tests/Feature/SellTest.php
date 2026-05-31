<?php

namespace Tests\Feature;


use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellTest extends TestCase
{
    use RefreshDatabase;

    private function createUser()
    {
        User::insert([
            'id' => 1,
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::find(1);
    }

    public function test_商品出品画面にて必要な情報が保存できる()
    {
        Storage::fake('public');

        $user = $this->createUser();

        $category = Category::create([
            'name' => 'ファッション',
        ]);

        $page = $this->actingAs($user)->get('/sell');

        $page->assertStatus(200);
        $page->assertSee('商品の出品');

        $response = $this->actingAs($user)->post('/sell', [
            'category_id' => [$category->id],
            'condition' => '良好',
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'image' => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg')
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 1000,
            'condition' => '良好',
            'is_sold' => false,
        ]);

        $item = Item::where('name', 'テスト商品')->first();

        $this->assertDatabaseHas('category_items', [
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        Storage::disk('public')->assertExists(str_replace('storage/', '', $item->image_path));
    }
}
