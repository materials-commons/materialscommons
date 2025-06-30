# API

- Use single API routes don't use ApiResource for routing.
- Responses should use Resource classes.
- Controllers should use form requests for validation.
- Controllers should be in App\Http\Controllers\Api\<ModelName> where ModelName is pluralized and should be named to described what the controller does, such as CreateActivityApiController
- Controller names should end with ApiController
