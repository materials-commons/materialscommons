# Bootstrap 5 Upgrade Execution Plan

This document outlines a pragmatic, low-risk plan to upgrade the application from Bootstrap 4.6 to Bootstrap 5.x while respecting current dependencies such as DataTables and bootstrap-select. It includes preparation, dependency changes, code migration tasks, testing, and rollout guidance.

Last updated: 2025-09-30


## 1) Goals and constraints
- Upgrade styling and JS to Bootstrap 5.x.
- Keep jQuery in the project for libraries that still require it (DataTables, bootstrap-select), but stop relying on Bootstrap’s jQuery plugins (removed in v5).
- Minimize regression risk by migrating incrementally and testing thoroughly.


## 2) Current state (from repository scan)
- Bootstrap 4.6.0 via npm (package.json devDependencies: "bootstrap": ^4.6.0); compiled assets contain "Bootstrap v4.6.2" banner.
- Popper v1 via popper.js (devDependencies: "popper.js": ^1.16.1).
- jQuery present (devDependencies: "jquery": ^3.5.1). Required for DataTables and bootstrap-select.
- bootstrap-select 1.13.18 is installed and used from resources/js/app.js.
- DataTables is bundled as local minified assets under resources/datatables and public/js/css. Some app code initializes DataTables via $(...).DataTable(...).
- Laravel Mix v6 and Webpack v5 are already in use (good for BS5).
- Typical Laravel bootstrap initialization is in resources/js/bootstrap.js, which currently requires popper.js, jQuery, and Bootstrap’s jQuery plugins.


## 3) High-level strategy
1. Prepare a feature branch and pin current production assets for easy rollback.
2. Upgrade core dependencies (Bootstrap 5 and @popperjs/core) while keeping jQuery.
3. Migrate Bootstrap-specific JS calls (e.g., $(...).modal('show')) to the new BS5 JS API; update data-* attributes and class/utility renames.
4. Replace DataTables’ Bootstrap 4 styling with Bootstrap 5 styling packages, keeping its jQuery core.
5. Upgrade bootstrap-select to a Bootstrap 5 compatible release.
6. Address markup changes (forms, input groups, utilities) incrementally, prioritizing high-traffic views.
7. Comprehensive QA and UAT, then staged rollout.


## 4) Pre-migration checklist
- Branching: create feature/bootstrap5-upgrade.
- Freeze current node build: save a copy of built CSS/JS from public/ for rollback.
- Confirm Node LTS (>=16) and npm in CI; Mix 6 + Webpack 5 is fine for BS5.
- Browser support: BS5 drops IE10/11; confirm this is acceptable.
- Inventory templates most likely impacted:
  - Modals, dropdowns, tooltips/popovers, toasts, navbar, forms (including custom forms), input groups, pagination, badges, utilities (ml-/mr- etc.).
  - Livewire vendor views under resources/views/vendor/livewire (bootstrap and simple-bootstrap) for pagination markup.
  - Any code using data-toggle/data-target attributes or Bootstrap’s jQuery plugin APIs.


## 5) Dependency changes
Run these in the feature branch.

- Core Bootstrap/Popper:
  - npm remove bootstrap popper.js
  - npm i -D bootstrap@^5.3.0 @popperjs/core@^2

- Keep jQuery for third-party libs:
  - jQuery remains (no version change required unless you want to update to latest 3.x).

- DataTables switch to Bootstrap 5 styling (choose one approach):
  1) NPM packages (recommended for consistent builds):
     - npm i -D datatables.net datatables.net-bs5
     - Optional add-ons you may use (add their BS5 peers):
       - npm i -D datatables.net-responsive datatables.net-responsive-bs5
       - npm i -D datatables.net-buttons datatables.net-buttons-bs5
       - npm i -D datatables.net-select datatables.net-select-bs5
     - Then import in app JS/CSS:
       - import 'datatables.net-bs5';
       - import 'datatables.net-responsive-bs5'; // if used
       - import 'datatables.net-buttons-bs5';    // if used
       - import 'datatables.net-select-bs5';     // if used
       - Optionally import DataTables CSS (or compile a SCSS that includes it):
         - import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';

  2) CDN/bundled assets:
     - Replace local resources/datatables and public/js/css DataTables files with the Bootstrap 5 builds from https://datatables.net/download/ selecting the Bootstrap 5 styling framework and the extensions you use. Update any blade layouts that include the CSS/JS accordingly.

- bootstrap-select (BS5-compatible):
  - Upgrade to >= 1.14.0-beta3 (or the latest 1.14.x release that explicitly supports Bootstrap 5):
    - npm i -D bootstrap-select@^1.14.0-beta3
  - Keep jQuery; bootstrap-select still depends on it.
  - Ensure CSS is included: import 'bootstrap-select/dist/css/bootstrap-select.min.css';

- Optional: evaluate bootstrap-steps (dependency listed). Verify compatibility with Bootstrap 5 or replace with a small custom CSS.


## 6) Build pipeline updates (Laravel Mix)
- resources/js/bootstrap.js:
  - Remove Popper v1 and Bootstrap v4 references. Example:
    - Before:
      - window.Popper = require('popper.js').default;
      - window.$ = window.jQuery = require('jquery');
      - require('bootstrap');
    - After (BS5):
      - import 'bootstrap'; // BS5’s JS requires @popperjs/core under the hood
      - window.$ = window.jQuery = require('jquery'); // keep for DataTables/bootstrap-select
      - // No need to expose Popper globally; BS5 imports it from @popperjs/core

- SCSS:
  - If you import Bootstrap SCSS, keep using: @import "~bootstrap/scss/bootstrap"; (or the new Sass module @use syntax if desired). Remove any overrides that rely on removed variables or mixins and update accordingly.


## 7) Application code migration (JS and markup)
Address the following in templates and scripts. These are the most common breaking changes moving from 4.x to 5.x.

- Data attributes:
  - data-toggle => data-bs-toggle
  - data-target => data-bs-target
  - data-dismiss => data-bs-dismiss
  - data-spy => removed

- JS APIs (no jQuery plugins in BS5):
  - Modals: $("#m").modal('show') => new bootstrap.Modal(document.getElementById('m')).show()
  - Collapse: $("#c").collapse('show') => new bootstrap.Collapse(document.getElementById('c'))
  - Dropdowns: $("#d").dropdown('toggle') => new bootstrap.Dropdown(document.getElementById('d')).toggle()
  - Tooltip/Popover: initialize via JS:
    - [...document.querySelectorAll('[data-bs-toggle="tooltip"]')].forEach(el => new bootstrap.Tooltip(el))

- Utility classes renamed:
  - ml-*, mr-* => ms-*, me-* (start/end instead of left/right)
  - pl-*, pr-* => ps-*, pe-*
  - text-left/right => text-start/end
  - float-left/right => float-start/end
  - rounded-right/left => rounded-end/start

- Forms & input groups:
  - Custom form controls merged into core. Replace .custom-select, .custom-file, etc., with .form-select, .form-control and new markup.
  - .input-group-append/.input-group-prepend removed; use .input-group > .input-group-text with proper ordering.
  - .form-group was de-emphasized; use spacing utilities (e.g., .mb-3) or keep semantic wrappers.

- Components removed/changed:
  - .badge-pill => .rounded-pill on .badge
  - .btn-block => .w-100
  - .form-inline => use utilities (row/col/align-items-center etc.)
  - .media component removed => replicate via utilities
  - .jumbotron removed => use utilities or cards

- Accessibility helpers:
  - .sr-only => .visually-hidden (or .visually-hidden-focusable)

- Pagination/Livewire:
  - Update Livewire’s pagination views to Bootstrap 5 markup. Livewire provides a bootstrap-5 theme; consider publishing or switching to it instead of maintaining custom BS4-based views under resources/views/vendor/livewire.


## 8) Search-and-update checklist (repo-wide)
Perform targeted searches and update occurrences:
- data-toggle=, data-target=, data-dismiss=
- $(...).modal(, $(...).collapse(, $(...).dropdown(, $(...).tooltip(, $(...).popover(
- class utilities: ml-|mr-|pl-|pr-|text-left|text-right|float-left|float-right|badge-pill|btn-block|sr-only
- BS4 form classes: custom-select, custom-file, input-group-append, input-group-prepend, form-inline
- Livewire bootstrap pagination view templates


## 9) DataTables integration updates
- If switching to npm packages:
  - Ensure imports are in the right order (jQuery first). Example in resources/js/app.js:
    - window.$ = window.jQuery = require('jquery');
    - import 'bootstrap';
    - import 'datatables.net-bs5';
    - import 'datatables.net-responsive-bs5'; // if used
    - import 'datatables.net-buttons-bs5';    // if used
    - import 'datatables.net-select-bs5';     // if used
  - Replace CSS link to Bootstrap 4 DataTables skin with the BS5 equivalent (dataTables.bootstrap5.min.css).

- If staying with downloaded minified files:
  - Rebuild a new bundle on https://datatables.net/download/ selecting Bootstrap 5 styling, then replace resources/datatables and public/* assets; update blade includes.

- No change needed to DataTables API usage ($(selector).DataTable(...))—it remains jQuery-based.


## 10) bootstrap-select updates
- Upgrade to >= 1.14.0-beta3 or latest 1.14.x that lists Bootstrap 5 support.
- Keep usage of $(...).selectpicker(...). Ensure CSS is loaded and test dropdown alignment with BS5 (Popper v2 changes positioning) and z-index within modals.
- If issues persist, consider alternatives like Choices.js or Tom Select, but that’s not required if 1.14.x works.


## 11) Testing plan
- Unit/integration tests (PHP) won’t catch front-end regressions. Add or expand basic JS/HTML checks if feasible.
- Manual QA checklist (critical surfaces):
  - Global navbars, dropdown menus, sticky headers
  - Modals (open/close, focus trap, scroll lock)
  - Tooltips/Popovers
  - Forms: validation states, file inputs, selects (native and selectpicker)
  - Input groups and icons
  - Collapses/accordions
  - Toasts (if used)
  - Pagination (including Livewire pagination)
  - Tables: DataTables sorting, filtering, responsive mode, buttons/exports
  - Layout utilities: spacing, floats, text alignment, badges
  - Z-index stacking in modals with dropdowns/selects

- Cross-browser smoke (Chrome, Firefox, Safari, Edge) and responsive breakpoints.


## 12) Rollout plan and fallback
- Stage behind a feature branch; deploy to a test/staging environment.
- If feasible, implement a build-time flag to generate both BS4 and BS5 bundles during transition for side-by-side comparison.
- Maintain a simple revert commit that pins back to the previous package.json and public assets copy.


## 13) Work breakdown (suggested order)
1. Bump dependencies and fix build (Bootstrap 5, @popperjs/core, bootstrap-select 1.14.x, DataTables BS5 styling). Ensure the dev build compiles. 
2. Replace data-* attributes and jQuery plugin calls for Bootstrap components.
3. Update utilities and common layout classes (ms/me/ps/pe/text-start/end/etc.).
4. Migrate forms and input groups.
5. Update pagination and Livewire bootstrap views to BS5.
6. Run targeted QA; fix regressions.
7. UAT with stakeholders; deploy.


## 14) Code snippets and examples
- Modal init (BS4 -> BS5):
  - Before: $('#myModal').modal('show')
  - After:
    const modal = new bootstrap.Modal(document.getElementById('myModal'));
    modal.show();

- Dropdown via data API (attribute rename):
  - Before: data-toggle="dropdown"
  - After:  data-bs-toggle="dropdown"

- Input group (append/prepend removal):
  - Before:
    <div class="input-group">
      <div class="input-group-prepend"><span class="input-group-text">@</span></div>
      <input class="form-control" ...>
    </div>
  - After:
    <div class="input-group">
      <span class="input-group-text">@</span>
      <input class="form-control" ...>
    </div>

- Utilities rename:
  - Before: <div class="text-right pr-2 mr-3"></div>
  - After:  <div class="text-end pe-2 me-3"></div>

- bootstrap-select include (example):
  import 'bootstrap-select/dist/css/bootstrap-select.min.css';
  import 'bootstrap-select';
  $('.selectpicker').selectpicker();

- DataTables (BS5 via npm):
  import 'datatables.net-bs5';
  import 'datatables.net-responsive-bs5';
  $("#table").DataTable({ responsive: true });


## 15) Acceptance criteria
- All Bootstrap assets upgraded to v5.x; no references to popper.js v1 or Bootstrap 4 assets remain in the build.
- DataTables tables render with Bootstrap 5 styling and remain functional.
- bootstrap-select works and matches Bootstrap 5 look and feel.
- All modals, dropdowns, tooltips/popovers, toasts, and collapses function using BS5 APIs.
- No critical visual regressions across key views; smoke tests pass in target browsers.


## 16) Post-upgrade cleanup
- Remove unused legacy CSS overrides that patched BS4-only behaviors.
- Remove any temporary compatibility shims.
- Document the new conventions (utilities, forms) for future development.
