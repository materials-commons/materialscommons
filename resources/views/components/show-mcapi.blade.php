<div x-data="initShowApi()">
    <a href="#" class="ml-1" @click="toggleShow()">S</a>
    <div style="display: none" x-show="showCode">
        <pre>
            import materials_commons.api as mcapi
            c = mcapi.Client.("{{auth()->user()->api_token}}", base_url="http://localhost:8000/api")
            proj = c.get_project({{$item->id}})
            proj.pretty_print()
        </pre>
    </div>
    @push('scripts')
        <script>
            function initShowApi() {
                console.log('initShowApi');
                return {
                    showCode: false,
                    toggleShow() {
                        this.showCode = !this.showCode;
                        console.log('this.showCode = ', this.showCode);
                    }
                };
            }
        </script>
    @endpush
</div>

