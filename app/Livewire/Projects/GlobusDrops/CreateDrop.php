<?php

namespace App\Livewire\Projects\GlobusDrops;

use App\Models\GlobusDrop;
use App\Models\Project;
use Livewire\Component;

class CreateDrop extends Component
{
    public Project $project;

    public string $name = '';

    public array $globusUsers = [
        '',
    ];

    protected function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'globusUsers'     => ['required', 'array', 'min:1'],
            'globusUsers.*'   => ['required', 'email', 'distinct'],
        ];
    }

    protected array $messages = [
        'globusUsers.*.required' => 'The Globus user is required.',
        'globusUsers.*.email'    => 'The Globus user must be a valid email address.',
        'globusUsers.*.distinct' => 'Each Globus user must be unique.',
    ];

    public function mount(Project $project): void
    {
        $this->project = $project;
    }

    public function addGlobusUser(): void
    {
        $lastIndex = count($this->globusUsers) - 1;

        $this->validateOnly("globusUsers.{$lastIndex}");

        $this->globusUsers[] = '';
    }

    public function removeGlobusUser(int $index): void
    {
        unset($this->globusUsers[$index]);

        $this->globusUsers = array_values($this->globusUsers);

        if (count($this->globusUsers) === 0) {
            $this->globusUsers[] = '';
        }
    }

    public function updatedGlobusUsers($value, string $key): void
    {
        $this->validateOnly("globusUsers.{$key}");
    }

    public function save()
    {
        $this->validate();

        GlobusDrop::create([
            'name'            => $this->name,
            'globus_accounts' => array_values($this->globusUsers),
            'project_id'      => $this->project->id,
            'owner_id'        => auth()->id(),
        ]);

        return redirect()
            ->route('projects.globus.drops.index', [$this->project])
            ->with('success', 'Drop created.');
    }

    public function render()
    {
        return view('livewire.globus-drops.create-drop');
    }
}
