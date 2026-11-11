<?php

namespace App\Livewire\Accounts;

use App\Models\UserApiToken;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ApiTokens extends Component
{
    private const DEFAULT_TOKEN_NAME = 'default';

    public string $newTokenName = '';

    public ?string $plainDefaultToken = null;

    /**
     * @var array<int, string>
     */
    public array $plainAdditionalTokens = [];

    public function getDefaultTokenProperty(): ?UserApiToken
    {
        return auth()
            ->user()
            ->apiTokens()
            ->where('name', self::DEFAULT_TOKEN_NAME)
            ->first();
    }

    public function getAdditionalTokensProperty(): Collection
    {
        return auth()
            ->user()
            ->apiTokens()
            ->where('name', '!=', self::DEFAULT_TOKEN_NAME)
            ->orderBy('name')
            ->orderBy('created_at')
            ->get();
    }

    public function generateDefaultToken(): void
    {
        $token = $this->makePlainToken();

        auth()->user()->apiTokens()->create([
            'name'  => self::DEFAULT_TOKEN_NAME,
            'token' => $token,
        ]);

        $this->plainDefaultToken = $token;

        session()->flash('api-token-message', 'Default API token generated. Copy it now because it will only be shown once.');
    }

    public function regenerateDefaultToken(): void
    {
        $token = $this->makePlainToken();

        $defaultToken = $this->defaultToken;

        if ($defaultToken) {
            $defaultToken->update([
                'token' => $token,
            ]);
        } else {
            auth()->user()->apiTokens()->create([
                'name'  => self::DEFAULT_TOKEN_NAME,
                'token' => $token,
            ]);
        }

        $this->plainDefaultToken = $token;

        session()->flash('api-token-message', 'Default API token regenerated. Existing clients using the old token will stop working.');
    }

    public function createAdditionalToken(): void
    {
        $validated = $this->validate([
            'newTokenName' => [
                'required',
                'string',
                'max:255',
                'not_in:' . self::DEFAULT_TOKEN_NAME,
                Rule::unique('user_api_tokens', 'name')
                    ->where('owner_id', Auth::id()),
            ],
        ], [
            'newTokenName.not_in' => 'The name default is reserved for your default API token.',
            'newTokenName.unique' => 'You already have an API token with this name.',
        ]);

        $token = $this->makePlainToken();

        $apiToken = auth()->user()->apiTokens()->create([
            'name'  => $validated['newTokenName'],
            'token' => $token,
        ]);

        $this->plainAdditionalTokens[$apiToken->id] = $token;
        $this->newTokenName = '';

        session()->flash('api-token-message', 'Additional API token generated. Copy it now because it will only be shown once.');
    }

    public function regenerateAdditionalToken(int $tokenId): void
    {
        $apiToken = $this->findAdditionalTokenForCurrentUser($tokenId);

        $token = $this->makePlainToken();

        $apiToken->update([
            'token' => $token,
        ]);

        $this->plainAdditionalTokens[$apiToken->id] = $token;

        session()->flash('api-token-message', "Token '{$apiToken->name}' regenerated. Existing clients using the old token will stop working.");
    }

    public function deleteAdditionalToken(int $tokenId): void
    {
        $apiToken = $this->findAdditionalTokenForCurrentUser($tokenId);

        unset($this->plainAdditionalTokens[$apiToken->id]);

        $apiToken->delete();

        session()->flash('api-token-message', "Token '{$apiToken->name}' deleted.");
    }

    private function findAdditionalTokenForCurrentUser(int $tokenId): UserApiToken
    {
        return auth()
            ->user()
            ->apiTokens()
            ->whereKey($tokenId)
            ->where('name', '!=', self::DEFAULT_TOKEN_NAME)
            ->firstOrFail();
    }

    private function makePlainToken(): string
    {
        return Str::random(80);
    }

    public function render(): View
    {
        return view('livewire.accounts.api-tokens', [
            'defaultToken'      => $this->defaultToken,
            'additionalTokens'  => $this->additionalTokens,
        ]);
    }
}
