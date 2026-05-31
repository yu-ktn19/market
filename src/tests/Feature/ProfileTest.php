<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Address;
use App\Models\Purchase;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private function createUser()
    {
        User::insert([
            'id' => 1,
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'profile_image' => 'profile_images/test.jpg',
            'postal_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return User::find(1);
    }

    public function test_いいねした商品だけが表示される()
    {
        $user = $this->createUser();

        $this->seed(ItemsTableSeeder::class);

        $likedItem = Item::where('name', '腕時計')->first();
        $notLikedItem = Item::where('name', 'HDD')->first();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('HDD');
    }

    public function test_マイリストでも購入済み商品はSoldと表示される()
    {
        $user = $this->createUser();

        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->update([
            'is_sold' => true,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('Sold');
    }

    public function test_未認証の場合はマイリストに何も表示されない()
    {
        $this->createUser();

        $this->seed(ItemsTableSeeder::class);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('腕時計');
        $response->assertDontSee('HDD');
        $response->assertDontSee('メイクセット');
    }

    public function test_プロフィールページで必要な情報が表示される()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $sellItem = Item::where('name', '腕時計')->first();

        $buyItem = Item::where('name', 'HDD')->first();

        $address = Address::create([
            'user_id' => $user->id,
            'postal_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
            'address_id' => $address->id,
            'payment_method' => 'card',
            'stripe_session_id' => null,
            'purchased_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/mypage');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('腕時計');
        $response->assertSee('profile_images/test.jpg');
    }

    public function test_プロフィール購入一覧に購入した商品が表示される()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', 'HDD')->first();

        $address = Address::create([
            'user_id' => $user->id,
            'postal_code' => '1234567',
            'address' => '東京都渋谷区',
            'building' => 'テストビル',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method' => 'card',
            'stripe_session_id' => null,
            'purchased_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/mypage?page=buy');

        $response->assertStatus(200);
        $response->assertSee('HDD');
    }

    public function test_プロフィール編集画面に初期値が表示される()
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('1234567');
        $response->assertSee('東京都渋谷区');
        $response->assertSee('テストビル');
    }
}
