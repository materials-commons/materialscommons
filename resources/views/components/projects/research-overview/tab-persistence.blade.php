@props([
    'project',
    'tabKey',
])

@push('scripts')
    <script>
        (function () {
            const TAB_KEY = @json($tabKey);
            const tabsSelector = '#project-dashboard-tabs [data-bs-toggle="pill"]';

            function showProjectDashboardTab(target) {
                const tabEl = document.querySelector('#project-dashboard-tabs [data-bs-target="' + target + '"]');

                if (!tabEl) {
                    return;
                }

                bootstrap.Tab.getOrCreateInstance(tabEl).show();
            }

            document.querySelectorAll(tabsSelector).forEach(btn => {
                btn.addEventListener('shown.bs.tab', function () {
                    localStorage.setItem(TAB_KEY, this.getAttribute('data-bs-target'));

                    if (window.Plotly) {
                        document.querySelectorAll('.js-plotly-plot').forEach(div => Plotly.Plots.resize(div));
                    }
                });
            });

            document.querySelectorAll('.js-project-dashboard-show-tab').forEach(btn => {
                btn.addEventListener('click', function () {
                    const target = this.getAttribute('data-tab-target');

                    if (!target) {
                        return;
                    }

                    showProjectDashboardTab(target);
                });
            });

            const savedTab = localStorage.getItem(TAB_KEY);

            if (savedTab) {
                showProjectDashboardTab(savedTab);
            }
        })();
    </script>
@endpush
