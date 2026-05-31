<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function createUser()
    {
        User::insert([
            'id' => 1,
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'postal_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::find(1);
    }

    public function test_購入するボタンを押すと購入が完了する()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $page = $this->actingAs($user)->get('/purchase/' . $item->id);
        $page->assertStatus(200);

        $response = $this->actingAs($user)->post('/purchase/' . $item->id, [
            'postal_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]);

        $response->assertRedirect('/');
    }

    public function test_購入した商品は商品一覧画面でSoldと表示される()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $page = $this->actingAs($user)->get('/purchase/' . $item->id);
        $page->assertStatus(200);

        $this->actingAs($user)->post('/purchase/' . $item->id, [
            'postal_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        Auth::logout();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('Sold');
    }

    public function test_購入した商品がプロフィール購入一覧に表示される()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $page = $this->actingAs($user)->get('/purchase/' . $item->id);
        $page->assertStatus(200);

        $this->actingAs($user)->post('/purchase/' . $item->id, [
            'postal_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'payment_method' => 'card',
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
    }

    public function test_選択した支払い方法が購入画面に反映される()
    {
        $user = $this->createUser();

        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $response = $this->actingAs($user)
            ->get('/purchase/' . $item->id . '?payment_method=card');

        $response->assertStatus(200);

        $response->assertSee('カード支払い');
    }
}