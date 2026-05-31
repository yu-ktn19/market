<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
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

    public function test_住所変更画面で登録した住所が購入画面に反映される()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $page = $this->actingAs($user)->get('/purchase/address/' . $item->id);
        $page->assertStatus(200); 

        $response = $this->actingAs($user)->post('/purchase/address/' . $item->id, [
            'postal_code' => '9999999',
            'address' => '大阪府大阪市',
            'building' => 'テストマンション',
        ]);

        $purchasePage = $this->actingAs($user)->get('/purchase/' . $item->id . '?postal_code=9999999&address=' . urlencode('大阪府大阪市') . '&building=' . urlencode('テストマンション'));

        $purchasePage->assertSee('9999999');
        $purchasePage->assertSee('大阪府大阪市');
        $purchasePage->assertSee('テストマンション');
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $page = $this->actingAs($user)->get('/purchase/address/' . $item->id);
        $page->assertStatus(200);

        $this->actingAs($user)->post('/purchase/' . $item->id, [
            'postal_code' => '9999999',
            'address' => '大阪府大阪市',
            'building' => 'テストマンション',
            'payment_method' => 'card',
        ]);

        $address = Address::where('postal_code', '9999999')->first();

        $this->assertNotNull($address);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);
    }
}
