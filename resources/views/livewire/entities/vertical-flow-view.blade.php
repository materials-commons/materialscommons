<div>
    {{-- Selection Controls --}}
    <div class="mb-3 d-flex align-items-center">
        @if(count($selectedEntityIds) > 0)
            <button wire:click="viewFlow" class="btn btn-primary">
                <i class="fas fa-sitemap mr-1"></i> View Selected Samples Flow
            </button>
            <span class="ml-3 text-muted">{{ count($selectedEntityIds) }} sample(s) selected</span>
        @endif
    </div>

    {{-- Inline Vertical Flow View --}}
    @if($showFlow)
        <div class="vertical-flow-view"
             x-data="{ show: @entangle('showFlow') }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Sample Activity Flow</h5>
                <button wire:click="closeFlow" class="btn btn-sm btn-secondary">
                    <i class="fas fa-times mr-1"></i> Close
                </button>
            </div>

            <div class="vertical-flow-grid">
                @foreach($selectedEntityIds as $entityId)
                    @php
                        $entity = $entities->firstWhere('id', $entityId);
                        $entityActivities = $this->getActivitiesForEntity($entityId);
                    @endphp

                    @if($entity)
                        <div class="sample-column">
                            <div class="sample-header">
                                {{ $entity->name }}
                            </div>

                            @if(empty($entityActivities))
                                <div class="no-activities">
                                    No activities
                                </div>
                            @else
                                @foreach($entityActivities as $index => $activityName)
                                    @if($index > 0)
                                        <div class="flow-connector"></div>
                                    @endif
                                    <div class="activity-box">
                                        <div class="activity-name">{{ $activityName }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            <hr class="my-4">
        </div>
    @endif

    <style>
        /* Vertical Flow View Styles */
        .vertical-flow-view {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .vertical-flow-grid {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(250px, 1fr);
            gap: 30px;
            padding: 20px;
            overflow-x: auto;
            background: white;
            border-radius: 6px;
        }

        .sample-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 200px;
        }

        .sample-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-size: 1rem;
        }

        .flow-connector {
            width: 3px;
            height: 25px;
            background: linear-gradient(to bottom, #667eea, #764ba2);
            margin: 0;
            position: relative;
        }

        .flow-connector::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 8px solid #764ba2;
        }

        .activity-box {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            margin: 5px 0;
        }

        .activity-box:hover {
            border-color: #667eea;
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        .activity-name {
            font-weight: 500;
            color: #2d3748;
            font-size: 0.9rem;
            text-align: center;
        }

        .no-activities {
            color: #a0aec0;
            font-style: italic;
            padding: 20px;
            text-align: center;
            background: #f7fafc;
            border: 2px dashed #cbd5e0;
            border-radius: 6px;
            width: 100%;
        }

        .entity-checkbox {
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        #selectAll {
            cursor: pointer;
            width: 18px;
            height: 18px;
        }
    </style>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Handle checkbox changes
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('entity-checkbox')) {
                    const entityId = parseInt(e.target.value);
                    @this.toggleEntity(entityId);
                }
            });

            // Handle select all
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const allCheckboxes = document.querySelectorAll('.entity-checkbox');
                    allCheckboxes.forEach(cb => cb.checked = this.checked);

                    if (this.checked) {
                        const entityIds = Array.from(allCheckboxes).map(cb => parseInt(cb.value));
                        @this.selectAll(entityIds);
                    } else {
                        @this.deselectAll();
                    }
                });
            }
        });
    </script>
</div>
