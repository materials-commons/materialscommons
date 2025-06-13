# Tour Tests

This directory contains tests for the tour functionality in the application.

## Test Files

### TourButtonTest.php

Tests for the "Start Tour" button in the help dialog and its functionality:

- `help_dialog_contains_start_tour_button`: Verifies that the help dialog contains a "Start Tour" button.
- `dashboard_page_loads_tour_service`: Verifies that the tour service is loaded on the dashboard page.
- `project_page_loads_tour_service`: Verifies that the tour service is loaded on the project page.
- `tour_service_correctly_identifies_dashboard_tour`: Verifies that the tour service correctly identifies the dashboard tour.
- `tour_service_correctly_identifies_project_tour`: Verifies that the tour service correctly identifies the project tour.
- `start_tour_button_calls_tour_service`: Verifies that the start tour button calls the tour service.

### TourServiceTest.php

Tests for the tour service functionality:

- `tour_service_has_state_management_functions`: Verifies that the tour service has state management functions.
- `tour_service_uses_database_for_state`: Verifies that the tour service uses the database for state management.
- `tour_service_has_tour_definitions`: Verifies that the tour service has tour definitions for dashboard, project, publishing, and etl.
- `tour_service_has_route_mapping_function`: Verifies that the tour service has a route mapping function.
- `tour_service_uses_shepherd_js`: Verifies that the tour service uses Shepherd.js.
- `tour_service_has_start_tour_function`: Verifies that the tour service has a startTour function.
- `tour_service_initializes_shepherd_tour`: Verifies that the tour service initializes a Shepherd tour.

## Running the Tests

To run the tests, use the following command:

```bash
php artisan test tests/Feature/Http/Controllers/Web/Tours
```

This will run all the tests in the Tours directory.

## Test Implementation

The tests use Laravel's testing framework to simulate a user visiting different pages and then assert that the expected elements and functionality are present in the response. The tests verify that:

1. The "Start Tour" button exists in the help dialog
2. The tour service is loaded on different pages
3. The tour service correctly identifies the appropriate tour for each route
4. The tour service has the necessary state management functions
5. The tour service uses the database for state management
6. The tour service has tour definitions for different pages
7. The tour service uses Shepherd.js for the tour UI
8. The tour service initializes a Shepherd tour when the startTour function is called

### TourStateControllerTest.php

Tests for the TourStateController API endpoints:

- `it_returns_empty_state_for_new_user`: Verifies that a new user gets an empty state.
- `it_returns_tour_state_for_user`: Verifies that a user with existing tour state gets the correct state.
- `it_returns_specific_tour_state`: Verifies that a specific tour state can be retrieved by tour name.
- `it_creates_tour_state`: Verifies that a tour state can be created.
- `it_completes_step`: Verifies that a step can be marked as completed.
- `it_completes_tour`: Verifies that a tour can be marked as completed.
- `it_resets_tour`: Verifies that a specific tour can be reset.
- `it_resets_all_tours`: Verifies that all tours can be reset.

To run these tests, use the following command:

```bash
php artisan test tests/Feature/Api/TourStateControllerTest.php
```
