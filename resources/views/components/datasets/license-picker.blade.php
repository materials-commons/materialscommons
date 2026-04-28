@php
    $licenses = [
        [
            'value'   => 'CC BY 4.0',
            'label'   => 'Creative Commons Attribution 4.0 (CC BY 4.0)',
            'badge'   => 'CC BY 4.0',
            'summary' => 'Others may share, adapt, and build upon your work for any purpose, including commercially, as long as they credit you. Widely used in open research — recommended for most datasets.',
            'url'     => 'https://creativecommons.org/licenses/by/4.0/',
        ],
        [
            'value'   => 'CC0 1.0',
            'label'   => 'Creative Commons Zero — No Rights Reserved (CC0)',
            'badge'   => 'CC0',
            'summary' => 'Dedicates your work to the public domain. No restrictions whatsoever. Anyone can copy, modify, and distribute your data without asking permission or giving credit.',
            'url'     => 'https://creativecommons.org/publicdomain/zero/1.0/',
        ],
        [
            'value'   => 'Public Domain Dedication and License (PDDL)',
            'label'   => 'Public Domain Dedication and License (PDDL)',
            'badge'   => 'PDDL',
            'summary' => 'Open Data Commons equivalent of CC0. Releases the database into the public domain with no restrictions.',
            'url'     => 'https://opendatacommons.org/licenses/pddl/',
        ],
        [
            'value'   => 'Attribution License (ODC-By)',
            'label'   => 'Open Data Commons Attribution (ODC-By)',
            'badge'   => 'ODC-By',
            'summary' => 'Others may use, share, and adapt the database as long as they credit you. The Open Data Commons equivalent of CC BY.',
            'url'     => 'https://opendatacommons.org/licenses/by/',
        ],
        [
            'value'   => 'Open Database License (ODC-ODbL)',
            'label'   => 'Open Database License (ODC-ODbL)',
            'badge'   => 'ODbL',
            'summary' => 'Use and share with attribution; any derivative databases must be released under the same license (share-alike). Restricts commercial lock-in.',
            'url'     => 'https://opendatacommons.org/licenses/odbl/',
        ],
        [
            'value'   => 'No License',
            'label'   => 'No License (All Rights Reserved)',
            'badge'   => null,
            'summary' => 'No permissions are granted. Others need explicit permission before they may use, share, or modify your data. Not recommended for open science.',
            'url'     => null,
        ],
        [
            'value'   => 'Custom',
            'label'   => 'Custom License…',
            'badge'   => null,
            'summary' => 'Enter your own license text below.',
            'url'     => null,
        ],
    ];

    // Determine the current selection (fall back to CC BY 4.0 if blank/null)
    $resolvedLicense = $currentLicense ?? 'CC BY 4.0';
    if (blank($resolvedLicense) || $resolvedLicense === 'No License' && is_null($currentLicense)) {
        $resolvedLicense = 'CC BY 4.0';
    }

    // Detect custom: value not in known list → treat as Custom with that text pre-filled
    $knownValues    = collect($licenses)->pluck('value')->all();
    $isCustom       = !in_array($resolvedLicense, $knownValues);
    $customText     = $isCustom ? $resolvedLicense : ($currentLicense === 'Custom' ? '' : '');
    $selectValue    = $isCustom ? 'Custom' : $resolvedLicense;
@endphp

<div class="mb-3 col-8" x-data="initLicensePicker()">

    <div class="d-flex justify-content-between align-items-center mb-1">
        <label for="ds-license" class="mb-0">License</label>
    </div>

    <select id="ds-license" class="form-select mb-2"
            x-model="selected"
            @change="onSelect()">
        @foreach($licenses as $lic)
            <option value="{{ $lic['value'] }}"
                {{ $selectValue === $lic['value'] ? 'selected' : '' }}>
                {{ $lic['label'] }}
            </option>
        @endforeach
    </select>

    {{-- Hidden input actually submitted (holds custom text when Custom is chosen) --}}
    <input type="hidden" name="license" x-bind:value="licenseValue">

    {{-- Summary card --}}
    <template x-if="currentMeta">
        <div class="d-flex align-items-start gap-2 px-3 py-2 rounded mb-2"
             style="background:#f0f7ff; border-left:3px solid #0d6efd; font-size:.82rem;">
            <i class="fas fa-info-circle text-primary mt-1 flex-shrink-0" style="font-size:.8rem;"></i>
            <div>
                <span x-text="currentMeta.summary" class="text-secondary"></span>
                <template x-if="currentMeta.url">
                    <a :href="currentMeta.url" target="_blank" rel="noopener"
                       class="ms-1 text-decoration-none" style="font-size:.75rem; white-space:nowrap;">
                        Read license <i class="fas fa-external-link-alt" style="font-size:.65rem;"></i>
                    </a>
                </template>
            </div>
        </div>
    </template>

    {{-- Custom license textarea --}}
    <div x-show="selected === 'Custom'" style="display:none;">
        <textarea class="form-control" rows="3"
                  placeholder="Enter your license text…"
                  @input="licenseValue = $event.target.value"
                  x-ref="customTextarea">{{ $customText }}</textarea>
        <div class="form-text">This text will be stored as the dataset license.</div>
    </div>

</div>

@push('scripts')
    <script>
        (function () {
            const _licenses = @json($licenses);
            const _initialSelected = @js($selectValue);
            const _initialCustom   = @js($customText);

            function initLicensePicker() {
                const metaMap = {};
                _licenses.forEach(l => metaMap[l.value] = l);

                return {
                    selected:     _initialSelected,
                    licenseValue: _initialSelected === 'Custom' ? _initialCustom : _initialSelected,
                    currentMeta:  metaMap[_initialSelected] || null,

                    onSelect() {
                        this.currentMeta = metaMap[this.selected] || null;
                        if (this.selected === 'Custom') {
                            this.licenseValue = this.$refs.customTextarea
                                ? this.$refs.customTextarea.value
                                : '';
                            this.$nextTick(() => this.$refs.customTextarea?.focus());
                        } else {
                            this.licenseValue = this.selected;
                        }
                    },
                };
            }

            window.initLicensePicker = initLicensePicker;
        })();
    </script>
@endpush
