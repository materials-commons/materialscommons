<?php

namespace App\Actions\Workflows;

class ParseWorkflowAction
{
    public function __invoke($workflow)
    {
        $lines = preg_split('/$\R?^/m', $workflow);
        $operations = [];
        $branches = [];
        $steps = [];
        foreach ($lines as $line) {
            $lineType = $this->getTypeOfLine($line);
            if ($lineType === null) {
                continue;
            }

            switch ($lineType['type']) {
                case 'operation':
                    array_push($operations, substr($line, 0, $lineType['position']));
                    break;
                case 'condition':
                    array_push($branches, substr($line, 0, $lineType['position']));
                    break;
                case 'step':
                    array_push($steps, trim($line));
                    break;
                default:
                    break;
            }
        }

        return ['operations' => $operations, 'branches' => $branches, 'steps' => $steps];
    }

    private function getTypeOfLine($line)
    {
        $pos = strpos($line, "=>operation:");
        if ($this->found($pos)) {
            return $this->makeLineType('operation', $pos);
        }

        $pos = strpos($line, '=>condition:');
        if ($this->found($pos)) {
            return $this->makeLineType('condition', $pos);
        }

        $pos = strpos($line, '->');
        if ($this->found($pos)) {
            return $this->makeLineType('step', $pos);
        }

        return null;
    }

    private function found($pos)
    {
        return $pos !== false;
    }

    private function makeLineType($type, $pos)
    {
        return ['type' => $type, 'position' => $pos];
    }
}