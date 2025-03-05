<?php

namespace Tests\Feature;

use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ItemCRUDTest extends TestCase
{
    use RefreshDatabase;

    public function test_getting_items(): void
    {
        Item::factory()->amazon()->count(3)->create();
        Item::factory()->zid()->count(4)->create();
        Item::factory()->steam()->count(1)->create();

        $response = $this->getJson('api/v1/items');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('items')->etc();
            $json->has('items.0', function (AssertableJson $json) {
                $json
                    ->whereType('id', 'integer')
                    ->whereType('name', 'string')
                    ->whereType('url', 'string')
                    ->whereType('price', 'string')
                    ->whereType('description', 'string')
                ;
            });
        });
    }

    public function test_getting_single_item(): void
    {
        $attributes = [
            'name' => 'Test item',
            'price' => 12300.45,
            'url' => 'https://example.zid.store/a3fc9978-51b9-334e-bc79-c4607c4e988e',
            'description' => 'Test description',
        ];

        $item = Item::factory()->create($attributes);

        $response = $this->getJson('api/v1/items/' . $item->id);

        $response->assertStatus(200);

        $responseItem = $response->json()['item'];

        $this->assertSame($item->id, $responseItem['id']);
        $this->assertSame($attributes['name'], $responseItem['name']);
        $this->assertSame('12 300.45', $responseItem['price']);
        $this->assertSame($attributes['url'], $responseItem['url']);
        $this->assertSame($attributes['description'], $responseItem['description']);
    }

    public function test_creating_new_item_with_valid_data(): void
    {
        $response = $this->postJson('api/v1/items', [
            'name' => 'New item',
            'price' => 12345,
            'url' => 'https://store.example.com/my-product',
            'description' => 'Test **item** description',
        ]);

        $this->assertSame('New item', $response->json()['item']['name']);

        $this->assertDatabaseHas(Item::class, [
            'name' => 'New item',
            'price' => 12345,
            'url' => 'https://store.example.com/my-product',
            'description' => "<p>Test <strong>item</strong> description</p>\n",
        ]);
    }

    public function test_creating_new_item_with_invalid_data(): void
    {
        $response = $this->postJson('api/v1/items', [
            'name' => 'New item',
            'price' => 'string',
            'url' => 'invalid url',
            'description' => 'Test item description',
        ]);

        $response->assertStatus(422);
    }

    public function test_updating_item_with_valid_data(): void
    {
        $item = Item::factory()->create();

        $response = $this->putJson('api/v1/items/ ' . $item->id, [
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'https://store.example.com/my-other-product',
            'description' => 'Test _item_ description',
        ]);

        $this->assertSame('Updated title', $response->json()['item']['name']);
        $this->assertSame(
            '<p>Test <em>item</em> description</p>',
            $response->json()['item']['description']
        );

        $this->assertDatabaseHas(Item::class, [
            'id' => $item->id,
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'https://store.example.com/my-other-product',
            'description' => "<p>Test <em>item</em> description</p>\n",
        ]);
    }

    public function test_updating_item_with_invalid_data(): void
    {
        $item = Item::factory()->create();

        $response = $this->putJson('api/v1/items/ ' . $item->id, [
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'invalid url',
            'description' => 'Test item description',
        ]);

        $response->assertStatus(422);
    }
    public function test_get_wishlist_statistics(): void
    {
        DB::table('items')->insert([
            ['name' => 'New item 1', 'price' => 100, 'url' => 'example.com','description' => 'Test description 1', 'created_at' => Carbon::now()],
            ['name' => 'New item 2', 'price' => 200, 'url' => 'example.com','description' => 'Test description 2', 'created_at' => Carbon::now()],
            ['name' => 'New item 3', 'price' => 300, 'url' => 'example.com','description' => 'Test description 3', 'created_at' => Carbon::now()],
        ]);

        $response = $this->getJson('/api/v1/wishlist/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_items',
                'average_price',
                'website_highest_total_price',
                'total_price_this_month'
            ])
            ->assertJson([
                'total_items' => 3,
                'average_price' => 200.0,
                'website_highest_total_price' => 'example.com',
                'total_price_this_month' => 600.0,
            ]);
    }
}
