# Tests

All use cases should be tested to have as much code coverage as possible.

## Testing Approach
- Organize tests to mirror the application structure.
- Use descriptive test names that explain what is being tested.
- Use factories and seeders for test data.
- Use database transactions for test isolation.

## Test Types
- Unit Tests: Test individual classes and methods in isolation.
- Feature Tests: Test the integration of multiple components.

## Test Coverage
- Aim for high test coverage, especially for critical paths.
- Test edge cases and error conditions.
- Test both happy and unhappy paths.
- Test authorization and validation.

## Component Testing
- Test each component in isolation.
- Test component state and actions.
- Use Livewire's testing utilities for component testing.
- Test edge cases and error conditions.
- Tests must use PHPUnit style.
- Tests need to run `RefreshDatabase`.
- Don't use multiple $response variables when you can chain methods.
- Don't use local variables if not needed.
