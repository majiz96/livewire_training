<?php

namespace App\Livewire;

use App\Models\category;
use App\Models\Position;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Positions extends Component
{
    #[Validate('required|min:3|string')]
    public string $title;
    #[Validate('required|min:3|string')]
    public string $description;
    #[Validate('required|integer|min:0|max:4')]
    public int $access = 0;

    public $editing = null;
    public $modalShow = false;
    public int $modalId;
    public string $modalTitle;

    public function edit($id)
    {
        $position = Position::findOrFail($id);
        $this->editing = $position->id;
        $this->title = $position->title;
        $this->description = $position->description;
        $this->access = $position->access;
    }
    public function cancel()
    {
        $this->editing = null;
        $this->reset(['title', 'description', 'access']);
    }

    public function save()
    {
        if($this->editing)
        {
        $this->validate();

        $category = Position::find($this->editing)->update($this->pull(['title', 'description', 'access']));
        $this->editing = null;
        }
        else
        {
            $this->validate();
            $data = Position::create($this->pull());
        }
    }
    public function question($id)
    {
        $this->modalShow = true;

        $modal = Position::findOrFail($id);
        $this->modalId = $modal->id;
        $this->modalTitle = $modal->title;
    }
    public function delete($id)
    {
    $position = Position::findOrFail($id);
    $position->delete();
    $this->modalShow = false;
    }

    public function render()
    {
        return view('livewire.positions',['positions'=>Position::orderBy('title')->get()]);
    }
}
