<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Category;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ItemImportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function import_page_is_accessible()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.items.import.show'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.items.import');
    }

    /** @test */
    public function can_download_template()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.items.download-template'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition');
    }

    /** @test */
    public function import_requires_file()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.items.import'), []);

        $response->assertSessionHasErrors('file');
    }

    /** @test */
    public function import_validates_file_type()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.items.import'), [
                'file' => UploadedFile::fake()->create('items.txt', 100),
            ]);

        $response->assertSessionHasErrors('file');
    }

    /** @test */
    public function can_import_items_from_excel()
    {
        Excel::fake();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.items.import'), [
                'file' => UploadedFile::fake()->createWithContent('items.xlsx', 'fake content'),
            ]);

        Excel::assertImported('items.xlsx');
    }

    /** @test */
    public function import_creates_new_categories()
    {
        // Setup
        $this->assertDatabaseMissing('categories', ['name' => 'Electronics']);

        // Create Excel with new category
        $import = new \App\Imports\ItemsImport();

        // Mock data
        $row = [
            'item_name' => 'Test Item',
            'category_name' => 'Electronics',
            'category_description' => 'Electronic equipment',
            'unit_name' => 'Piece',
            'unit_description' => 'Individual',
            'condition' => 'good',
            'min_stock' => 5,
            'description' => 'Test description',
        ];

        $import->model($row);

        // Verify category created
        $this->assertDatabaseHas('categories', ['name' => 'Electronics']);
    }

    /** @test */
    public function import_creates_new_units()
    {
        // Setup
        $this->assertDatabaseMissing('units', ['name' => 'Piece']);

        // Create Excel with new unit
        $import = new \App\Imports\ItemsImport();

        // Mock data
        $row = [
            'item_name' => 'Test Item',
            'category_name' => 'Electronics',
            'unit_name' => 'Piece',
            'unit_description' => 'Individual',
            'condition' => 'good',
            'min_stock' => 5,
            'description' => 'Test description',
        ];

        $import->model($row);

        // Verify unit created
        $this->assertDatabaseHas('units', ['name' => 'Piece']);
    }

    /** @test */
    public function import_generates_unique_item_codes()
    {
        $category = Category::create(['code' => 'ELE', 'name' => 'Electronics']);
        $unit = Unit::create(['code' => 'PC', 'name' => 'Piece']);

        Item::create([
            'item_code' => 'ELE-ITM-001',
            'item_name' => 'Item 1',
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'condition' => 'good',
            'min_stock' => 5,
            'stock' => 0,
        ]);

        // Import new item with same category
        $import = new \App\Imports\ItemsImport();

        $row = [
            'item_name' => 'Item 2',
            'category_name' => 'Electronics',
            'unit_name' => 'Piece',
            'condition' => 'good',
            'min_stock' => 5,
        ];

        $import->model($row);

        // Verify second item has different code
        $this->assertDatabaseHas('items', ['item_code' => 'ELE-ITM-002']);
    }

    /** @test */
    public function import_rejects_duplicate_item_names()
    {
        $category = Category::create(['code' => 'ELE', 'name' => 'Electronics']);
        $unit = Unit::create(['code' => 'PC', 'name' => 'Piece']);

        Item::create([
            'item_code' => 'ELE-ITM-001',
            'item_name' => 'Duplicate Item',
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'condition' => 'good',
            'min_stock' => 5,
            'stock' => 0,
        ]);

        // Try to import duplicate
        $import = new \App\Imports\ItemsImport();

        $row = [
            'item_name' => 'Duplicate Item',
            'category_name' => 'Electronics',
            'unit_name' => 'Piece',
            'condition' => 'good',
            'min_stock' => 5,
        ];

        $result = $import->model($row);

        // Should return null on error
        $this->assertNull($result);

        // Errors should be recorded
        $errors = $import->getErrors();
        $this->assertNotEmpty($errors);
    }

    /** @test */
    public function import_validates_condition_field()
    {
        $import = new \App\Imports\ItemsImport();

        $row = [
            'item_name' => 'Test Item',
            'category_name' => 'Electronics',
            'unit_name' => 'Piece',
            'condition' => 'invalid_condition',
            'min_stock' => 5,
        ];

        $result = $import->model($row);

        // Should return null on error
        $this->assertNull($result);

        // Errors should be recorded
        $errors = $import->getErrors();
        $this->assertNotEmpty($errors);
    }
}
