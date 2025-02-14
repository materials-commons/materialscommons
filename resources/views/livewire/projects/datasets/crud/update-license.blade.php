<div>
    <label for="license">Choose A License</label>
    <div class="form-group">
        <div class="col-12">
            <x-datasets.license-card>
                <x-slot:summary>
                    <div>
                        No License
                        <a @class([
                                "float-right cursor-pointer card-link",
                                "grey-8" => $license !== 'No License'
                           ])
                           wire:click.prevent="setLicense('No License')"
                           href="#">
                            <i class="fa fas fa-check"></i>
                        </a>
                    </div>
                    <p class="card-text">
                        Your dataset will have no license associated with it. Users are free
                        to do anything they want with it and don't have to give credit or attribution.
                    </p>
                </x-slot:summary>
            </x-datasets.license-card>
        </div>
        <div class="col-12">
            <x-datasets.license-card>
                <x-slot:summary>
                    <div>
                        Open Data Commons Database License (ODbL)
                        <a href="https://opendatacommons.org/licenses/odbl/summary/" target="_blank">
                            <i class="fa fas fa-external-link-alt"></i>
                        </a>
                        <a @class([
                                "float-right cursor-pointer card-link",
                                "grey-8" => $license !== 'Open Database License (ODC-ODbL)'
                           ])
                           wire:click.prevent="setLicense('Open Database License (ODC-ODbL)')"
                           href="#">
                            <i class="fa fas fa-check"></i>
                        </a>
                    </div>
                    <p class="card-text">
                        This license ensures openness and collaboration by requiring that derivative works follow the
                        same open licensing.
                    </p>
                </x-slot:summary>
            </x-datasets.license-card>
        </div>
        <div class="col-12">
            <x-datasets.license-card>
                <x-slot:summary>
                    <div>
                        Open Data Commons Attribution License (ODC-By)
                        <a href="https://opendatacommons.org/licenses/by/summary/" target="_blank">
                            <i class="fa fas fa-external-link-alt"></i>
                        </a>
                        <a @class([
                                "float-right cursor-pointer card-link",
                                "grey-8" => $license !== 'Attribution License (ODC-By)'
                           ])
                           wire:click.prevent="setLicense('Attribution License (ODC-By)')"
                           href="#"
                           class="float-right cursor-pointer card-link grey-8">
                            <i class="fa fas fa-check"></i>
                        </a>
                    </div>
                    <p class="card-text">
                        This license is more permissive, requiring only attribution, without imposing the sharing of the
                        derived data or requiring openness of derivatives.
                    </p>
                </x-slot:summary>
            </x-datasets.license-card>
        </div>
        <div class="col-12">
            <x-datasets.license-card>
                <x-slot:summary>
                    <div>
                        Open Data Commons Public Domain Dedication and License (PDDL)
                        <a href="https://opendatacommons.org/licenses/pddl/summary/" target="_blank">
                            <i class="fa fas fa-external-link-alt"></i>
                        </a>
                        <a @class([
                                "float-right cursor-pointer card-link",
                                "grey-8" => $license !== 'Public Domain Dedication and License (PDDL)'
                           ])
                           wire:click.prevent="setLicense('Public Domain Dedication and License (PDDL)')"
                           href="#"
                           class="float-right cursor-pointer card-link grey-8">
                            <i class="fa fas fa-check"></i>
                        </a>
                    </div>
                    <p class="card-text">
                        The terms under the PDDL (Public Domain Dedication and License) provide complete freedom to
                        use the data. No conditions or restrictions are placed on the use of the data.
                    </p>
                </x-slot:summary>
            </x-datasets.license-card>
        </div>
    </div>
</div>
