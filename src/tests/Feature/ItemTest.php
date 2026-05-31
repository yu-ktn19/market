<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;
use Database\Seeders\ItemsTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemTest extends TestCase
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

    public function test_全商品を取得できる()
    {
        $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('HDD');
        $response->assertSee('メイクセット');
    }

    public function test_購入済み商品はSoldと表示される()
    {
        $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        Item::where('name', '腕時計')->update([
            'is_sold' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('腕時計');
        $response->assertDontSee('HDD');
    }

    public function test_商品名で部分一致検索ができる()
    {
        $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $response = $this->get('/?keyword=腕');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertDontSee('HDD');
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist&keyword=腕');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('value="腕"', false);
        $response->assertDontSee('HDD');
    }

    public function test_商品詳細ページに必要な情報が表示される()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメントです',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('腕時計');
        $response->assertSee('Rolax');
        $response->assertSee('15,000');
        $response->assertSee('スタイリッシュなデザインのメンズ腕時計');
        $response->assertSee('良好');
        $response->assertSee('テストユーザー');
        $response->assertSee('テストコメントです');
        $response->assertSee('1');
    }

    public function test_複数選択されたカテゴリが表示されている()
    {
        $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $category1 = Category::create([
            'name' => 'ファッション',
        ]);

        $category2 = Category::create([
            'name' => 'メンズ',
        ]);

        $item->categories()->attach([
            $category1->id,
            $category2->id,
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);
        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
    }

    public function test_いいねするといいね数が増える()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $this->assertEquals(0, Like::where('item_id', $item->id)->count());

        $page = $this->actingAs($user)->get('/item/' . $item->id);
        $page->assertStatus(200);

        $this->actingAs($user)->post('/item/' . $item->id . '/like');

        $this->assertEquals(1, Like::where('item_id', $item->id)->count());
    }

    public function test_いいね解除できる()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $page = $this->actingAs($user)->get('/item/' . $item->id);
        $page->assertStatus(200);

        $response = $this->actingAs($user)->delete('/item/' . $item->id . '/like');

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response->assertRedirect();
    }

    public function test_いいね解除するといいね数が減る()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        Like::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->assertEquals(1, Like::where('item_id', $item->id)->count());

        $page = $this->actingAs($user)->get('/item/' . $item->id);
        $page->assertStatus(200);

        $this->actingAs($user)->delete('/item/' . $item->id . '/like');

        $this->assertEquals(0, Like::where('item_id', $item->id)->count());
    }

    public function test_ログイン済みユーザーはコメントを送信できる()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $page = $this->actingAs($user)->get('/item/' . $item->id);
        $page->assertStatus(200);

        $response = $this->actingAs($user)
            ->post('/item/' . $item->id . '/comment', [
                'content' => 'テストコメント',
            ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);

        $response->assertRedirect();
    }

    public function test_未ログインユーザーはコメントを送信できない()
    {
        $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();
        
        $page = $this->get('/item/' . $item->id);
        $page->assertStatus(200);  

        $response = $this->post('/item/' . $item->id . '/comment', [
            'content' => 'テストコメント',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_コメント未入力の場合バリデーションエラー()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $response = $this->actingAs($user)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'content' => '',
            ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_コメントが255文字を超える場合バリデーションエラー()
    {
        $user = $this->createUser();
        $this->seed(ItemsTableSeeder::class);

        $item = Item::where('name', '腕時計')->first();

        $response = $this->actingAs($user)
            ->from('/item/' . $item->id)
            ->post('/item/' . $item->id . '/comment', [
                'content' => str_repeat('a', 256),
            ]);

        $response->assertSessionHasErrors('content');
    }
}