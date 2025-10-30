<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Food;
use App\Models\Category;


class Foods extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = "bootstrap";

    #[Validate('required')]
    public int $cat_id;

    #[Validate('required|string|min:3')]
    public string $name;

    #[Validate('required|max:200')]
    public string $description;

    #[Validate('required|integer|min:35000')]
    public int $price;

    #[Validate('nullable|max:2048')]
    public $image = null;

    #[Validate('nullable|max:40')]
    public $discount;

    public $editing = null;

    public $highlightedId = null;
    public $selectedCategories = [];

    public $modalName;
    public $modalId;
    public $modalImg;

    public $modalPrice;

    public $confirmDelete = false;
    public $discShow = false;
    public $sortColumn;
    public $sortDirection;

    public function edit($id)
    {

        $food = Food::findOrFail($id);
        $this->editing = $food->id;
        $this->name = $food->name;
        $this->description = $food->description;
        $this->cat_id = $food->cat_id;
        $this->price = $food->price;
        $this->image = $food->image;

    }

    public function cancel()
    {
        $this->reset(['name', 'description', 'price', 'image','cat_id']);
        $this->editing = null;

        $this->resetValidation();
    }


    public function save()
    {
        $this->validate();

        if ($this->image && !is_string($this->image)) {
            $path = $this->image->store('foods', 'public');
            $imagePath = '/storage/' . $path;
        }
        else
        {
            $imagePath = null;

        }

        if ($this->editing) {

            $data = $this->pull(['cat_id', 'name', 'description', 'price']);
            $data['image'] = $imagePath ?? $this->image;

            $food = Food::find($this->editing)->update($data);

            $this->editing = null;

            $this->reset(['name', 'description', 'price', 'image']);



        } else {

            $data = $this->pull(['cat_id', 'name', 'description', 'price']);
            $data['image'] = $imagePath;

            $food=Food::create($data);

            $this->highlightedId = $food->id;
            $this->reset(['name', 'description', 'price', 'image']);
        }

    }
    public function activeDiscount($id)
    {
        $food = Food::findOrFail($id);

        $this->discount = $food->discount ?? 0;
        $this->modalPrice = $food->price;
        $this->modalName = $food->name;
        $this->modalId = $food->id;
        $this->modalImg = $food->image;

        $this->discShow = true;

    }

    public function setDiscount($id)
    {

        $this->discShow = false;

        $food = Food::findOrFail($id);
        $food->update(['discount' => $this->discount]);
    }

    public function question($id)
    {
        $food = Food::findOrFail($id);

        $this->modalName = $food->name;
        $this->modalId = $food->id;
        $this->modalImg = $food->image;
        $this->confirmDelete = true;

    }

    public function delete($id)
    {
        $food = Food::findOrFail($id);
        if ($food->image) {
            $filePath = public_path($food->image);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $food->delete();
        $this->confirmDelete = false;

    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
        $this->highlightedId = '';

    }
    public function catReset()
    {
        $this->selectedCategories = [];

    }

    public function sortColUpdate($col)
    {
        $this->sortColumn = $col;

    }
    public function sortDirUpdate($dir)
    {
        $this->sortDirection = $dir;

    }

    public function render()
    {
        if(!$this->sortColumn || !$this->sortDirection){
            $this->sortColumn = 'name';
            $this->sortDirection = 'asc';
        }

        $query = Food::orderBy($this->sortColumn, $this->sortDirection);

        if (!empty($this->selectedCategories)) {
            $query->whereIn('cat_id', $this->selectedCategories);
        }

        return view('livewire.foods', [
            'foods' => $query->paginate(8),
            'categories' => Category::all(),
        ]);

    }

}
