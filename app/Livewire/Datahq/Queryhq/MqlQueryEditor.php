<?php

namespace App\Livewire\Datahq\Queryhq;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class MqlQueryEditor extends Component
{
    public $query = '';
    public $results = [];

    public function clearResults()
    {
        $this->results = [];
    }

    public function executeQuery()
    {
        if (empty(trim($this->query))) {
            return;
        }

        try {
            $resp = Http::post("http://localhost:8561/mql", [
                'query' => $this->query
            ]);
            if ($resp->failed()) {
                $this->results[] = [
                    'query'     => $this->query,
                    'timestamp' => now()->format('Y-m-d H:i:s'),
                    'error'     => $resp->body(),
                ];
            } else {
                $this->results[] = [
                    'query'     => $this->query,
                    'timestamp' => now()->format('Y-m-d H:i:s'),
                    'data'      => $resp->body(),
                ];
            }
        } catch (\Exception $e) {
            $this->results[] = [
                'query'     => $this->query,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'error'     => $e->getMessage(),
            ];
        }

        $this->dispatch('scroll-to-bottom');
    }

    public function executeAndClearQuery()
    {
        $this->executeQuery();
        $this->query = '';
    }

    public function loadQuery($query)
    {
        $this->query = $query;
    }

    public function render()
    {
        return view('livewire.datahq.queryhq.mql-query-editor');
    }
}
