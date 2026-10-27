<?php

namespace App\Livewire\Projects\GlobusDrops;

use App\Actions\Globus\EndpointAclRule;
use App\Actions\Globus\GlobusApi;
use App\Actions\Globus\GlobusUrl;
use App\Mail\Globus\DropAvailableForUseMail;
use App\Models\GlobusDrop;
use App\Models\Project;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class CreateDrop extends Component
{
    public Project $project;

    private $globusApi;
    private $endpointId;

    public string $name = '';
    public string $message = '';

    public array $globusUsers = [
        '',
    ];

    public function __construct()
    {
        $this->globusApi = GlobusApi::createGlobusApi();
        $this->endpointId = config('globus.endpoint');
    }

    protected function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'message'       => ['nullable', 'string'],
            'globusUsers'   => ['required', 'array', 'min:1'],
            'globusUsers.*' => [
                'bail', 'required', 'email', 'distinct',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (!$this->isValidGlobusIdentity($value)) {
                        $fail('The Globus user must be a valid and active Globus identity.');
                    }
                }
            ],
        ];
    }

    protected array $messages = [
        'globusUsers.*.required' => 'The Globus user is required.',
        'globusUsers.*.email'    => 'The Globus user must be a valid email address.',
        'globusUsers.*.distinct' => 'Each Globus user must be unique.',
    ];

    private function isValidGlobusIdentity(string $email): bool
    {
        try {
            $response = $this->globusApi->getIdentities([$email]);
        } catch (\Throwable $e) {
            return false;
        }

        if (isset($response['identities']) && count($response['identities']) > 0) {
            $status = $response['identities'][0]['status'];
            if ($status === 'used') {
                return true;
            }
        }
        return false;
    }

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

    public function save()
    {
        $this->validate();

        $drop = GlobusDrop::create([
            'name'            => $this->name,
            'message'         => $this->message,
            'globus_accounts' => array_values($this->globusUsers),
            'project_id'      => $this->project->id,
            'owner_id'        => auth()->id(),
        ]);

        $this->createDropInGlobus($drop);

        return redirect()
            ->route('projects.globus.drops.index', [$this->project])
            ->with('success', 'Drop created.');
    }

    private function createDropInGlobus(GlobusDrop $drop): void
    {
        $path = Storage::disk('mcfs')->path("__globus_uploads/drops/{$drop->id}/{$drop->name}");
        $globusPath = "/__globus_uploads/drops/{$drop->id}/{$drop->name}/";
        if (!is_dir($path)) {
            $old = umask(0);
            mkdir($path, 0777, true);
            umask($old);
        }

        $drop->update([
            'globus_path'        => $globusPath,
            'globus_endpoint_id' => $this->endpointId,
            'globus_url'         => GlobusUrl::globusUploadUrl($this->endpointId, $globusPath),
            'status'             => 'available',
        ]);

        $ownerOfDrop = $drop->owner;

        // Set ACL for the owner.
        $this->setAclOnPathForUser($globusPath, $ownerOfDrop->email);

        foreach ($drop->globus_accounts as $globusAccount) {
            $this->setAclOnPathForUser($globusPath, $globusAccount);
        }

        // Send email to all the users for the drop
        $dropEmail = new DropAvailableForUseMail($drop);
        Mail::to($drop->globus_accounts)->queue($dropEmail->onQueue('globus'));
    }

    private function setAclOnPathForUser($globusPath, $email)
    {
        $globusUserId = $this->getGlobusIdentity($email);
        $this->setAclOnPath($globusPath, $globusUserId);

        $globusUserId = $this->getGlobusIdentity($email."@accounts.google.com");
        $this->setAclOnPath($globusPath, $globusUserId);
    }

    private function getGlobusIdentity($globusEmail)
    {
        $resp = $this->globusApi->getIdentities([$globusEmail]);
        return $resp["identities"][0]["id"];
    }

    private function setAclOnPath($globusPath, $globusUserId)
    {
        $endpointAclRule = new EndpointAclRule($globusUserId, $globusPath, "rw", $this->endpointId);
        $resp = $this->globusApi->addEndpointAclRule($endpointAclRule);
        return $resp["access_id"];
    }

    public function render()
    {
        return view('livewire.projects.globus-drops.create-drop');
    }
}
