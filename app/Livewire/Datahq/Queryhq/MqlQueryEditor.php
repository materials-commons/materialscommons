<?php

namespace App\Livewire\Datahq\Queryhq;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class MqlQueryEditor extends Component
{
    public $query = '';
    public $results = [];

    public function executeQuery()
    {
        if (empty(trim($this->query))) {
            return;
        }

        // Your query execution logic here
        // Example:
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
                    'data'      => $resp->json(),
                ];
            }
//            // Execute query and get results
//            $result = [
//                'query'     => $this->query,
//                'timestamp' => now()->format('Y-m-d H:i:s'),
//                'data'      => [], // Your actual query results
//            ];
//
//            $this->results[] = $result;
        } catch (\Exception $e) {
            $this->results[] = [
                'query'     => $this->query,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'error'     => $e->getMessage(),
            ];
        }
    }

    public function render()
    {
        return view('livewire.datahq.queryhq.mql-query-editor');
    }
}
