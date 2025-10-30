<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Food;
use App\Models\Category;

class Categories extends Component
{

    #[Validate('required|string|min:2')]
    public string $name;

    public $editing = null;
    public $highlightId = null;

    public function edit($id)
    {
        $cat = Category::findOrFail($id);
        $this->editing = $cat->id;
        $this->name = $cat->name;
    }

    public function cancel()
    {
        $this->editing = null;
        $this->reset(['name']);
    }

    public function save()
    {

    if (!$this->editing) {
        $this->validate();
        $category = Category::create($this->pull(['name']));
    }
    else{
        $this->validate();
        $category = Category::find($this->editing)->update($this->pull(['name']));
        $this->editing = null;
    }
    }

    public function delete($id)
    {
        if(Gate::denies('full-access')){
            abort(403,'شما مجاز به حذف دسته ها نیستید.');
        }
        Category::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.categories', ['categories' => Category::all()]);
    }
}
