---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#Projects


<!-- START_d1a366aa47ee59c96780bfe89ca95bdd -->
## CreateProject

Create a new project for the given user.

> Example request:

```bash
curl -X POST "http://localhost/api/projects" \
    -H "Content-Type: application/json" \
    -d '{"name":"facere","description":"aliquid","is_active":false}'

```

```javascript
const url = new URL("http://localhost/api/projects");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "name": "facere",
    "description": "aliquid",
    "is_active": false
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | The name of the project - must be unique for the user
    description | string |  optional  | A description of the project
    is_active | boolean |  optional  | Whether this is an active project (default is true)

<!-- END_d1a366aa47ee59c96780bfe89ca95bdd -->

#general


<!-- START_5d781a2c47e87615bf9a0ec748efe781 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/entities" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entities");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/entities`


<!-- END_5d781a2c47e87615bf9a0ec748efe781 -->

<!-- START_4fb8c250b9ca9f8efea0cfd42817b474 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/entities" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entities");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/entities`


<!-- END_4fb8c250b9ca9f8efea0cfd42817b474 -->

<!-- START_429743db8561a8f910fb889467cd528b -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/entities/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entities/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/entities/{entity}`


<!-- END_429743db8561a8f910fb889467cd528b -->

<!-- START_0de6511c8378645fc2379e7e250aed53 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/entities/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entities/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/entities/{entity}`

`PATCH api/projects/{project}/entities/{entity}`


<!-- END_0de6511c8378645fc2379e7e250aed53 -->

<!-- START_8e6de699913aa93e06cc65854709b038 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/entities/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entities/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/entities/{entity}`


<!-- END_8e6de699913aa93e06cc65854709b038 -->

<!-- START_4b460bf50b98157b626c181a94e74c33 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/actions" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/actions`


<!-- END_4b460bf50b98157b626c181a94e74c33 -->

<!-- START_2daf0bebb57b973689ce227afeeecbfc -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/actions" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/actions`


<!-- END_2daf0bebb57b973689ce227afeeecbfc -->

<!-- START_bd905c54d7fd5db7ad90631e4d0cce70 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/actions/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/actions/{action}`


<!-- END_bd905c54d7fd5db7ad90631e4d0cce70 -->

<!-- START_4a39f5768d6c1e487693bd122f305087 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/actions/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/actions/{action}`

`PATCH api/projects/{project}/actions/{action}`


<!-- END_4a39f5768d6c1e487693bd122f305087 -->

<!-- START_54668d71b1e8530486ba4acbf0af22d2 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/actions/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/actions/{action}`


<!-- END_54668d71b1e8530486ba4acbf0af22d2 -->

<!-- START_cf68e0d4f4b2ac99525169c76961f6d8 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/actions/1/entity-state" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/entity-state");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/actions/{action}/entity-state`


<!-- END_cf68e0d4f4b2ac99525169c76961f6d8 -->

<!-- START_e23d7c50462699a0975f770dbc153065 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/actions/1/entity-state" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/entity-state");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/actions/{action}/entity-state`


<!-- END_e23d7c50462699a0975f770dbc153065 -->

<!-- START_cf3fb60f73dbd45b16388008e6f59578 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/actions/1/entity-state/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/entity-state/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/actions/{action}/entity-state/{entity_state}`


<!-- END_cf3fb60f73dbd45b16388008e6f59578 -->

<!-- START_9d0dd212ac10058b834f2d8aa3b9ec1c -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/actions/1/entity-state/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/entity-state/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/actions/{action}/entity-state/{entity_state}`

`PATCH api/projects/{project}/actions/{action}/entity-state/{entity_state}`


<!-- END_9d0dd212ac10058b834f2d8aa3b9ec1c -->

<!-- START_88ac2d1dbd1b9b90913ea259de6dae0c -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/actions/1/entity-state/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/entity-state/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/actions/{action}/entity-state/{entity_state}`


<!-- END_88ac2d1dbd1b9b90913ea259de6dae0c -->

<!-- START_47001f1e01929a61b231dcba54505af4 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/actions/1/files" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/actions/{action}/files`


<!-- END_47001f1e01929a61b231dcba54505af4 -->

<!-- START_8b5bf2135bbad64ec34ecfee8126a47c -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/actions/1/files" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/actions/{action}/files`


<!-- END_8b5bf2135bbad64ec34ecfee8126a47c -->

<!-- START_52e5816e7c5600e3940bcbf7d84ff9d4 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/actions/1/files/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/actions/{action}/files/{file}`


<!-- END_52e5816e7c5600e3940bcbf7d84ff9d4 -->

<!-- START_c025cd6058c718f83337f00fd1906caf -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/actions/1/files/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/actions/{action}/files/{file}`

`PATCH api/projects/{project}/actions/{action}/files/{file}`


<!-- END_c025cd6058c718f83337f00fd1906caf -->

<!-- START_4813cfe314c6b1c8f5ef3c7849ac08e8 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/actions/1/files/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/actions/{action}/files/{file}`


<!-- END_4813cfe314c6b1c8f5ef3c7849ac08e8 -->

<!-- START_ced386d280246c0f5375eca0eff3cf96 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/actions/1/attributes" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/actions/{action}/attributes`


<!-- END_ced386d280246c0f5375eca0eff3cf96 -->

<!-- START_631ccd999fd3cd517498d48cfcee1e0a -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/actions/1/attributes" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/actions/{action}/attributes`


<!-- END_631ccd999fd3cd517498d48cfcee1e0a -->

<!-- START_5434a5fa43bb21108835e771ed6d61ff -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/actions/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/actions/{action}/attributes/{attribute}`


<!-- END_5434a5fa43bb21108835e771ed6d61ff -->

<!-- START_44798b8dc88414b1ddda09e12c4e7f15 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/actions/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/actions/{action}/attributes/{attribute}`

`PATCH api/projects/{project}/actions/{action}/attributes/{attribute}`


<!-- END_44798b8dc88414b1ddda09e12c4e7f15 -->

<!-- START_e378bca78edaf63eaa3097182fe1df22 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/actions/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/actions/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/actions/{action}/attributes/{attribute}`


<!-- END_e378bca78edaf63eaa3097182fe1df22 -->

<!-- START_de8a3ef48b22c6cf7ee0d4b90a04847b -->
## List files for entity state.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/entity-state/1/files" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/entity-state/{entity_state}/files`


<!-- END_de8a3ef48b22c6cf7ee0d4b90a04847b -->

<!-- START_f2ca6857e9e89558d31a2ff84bcb287f -->
## Add a file to the entity state

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/entity-state/1/files" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/entity-state/{entity_state}/files`


<!-- END_f2ca6857e9e89558d31a2ff84bcb287f -->

<!-- START_40f8d12a913aec1f5250791ddf4bdcb5 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/entity-state/1/files/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/entity-state/{entity_state}/files/{file}`


<!-- END_40f8d12a913aec1f5250791ddf4bdcb5 -->

<!-- START_5d652a5c6a9d91e1e50b036e80099839 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/entity-state/1/files/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/entity-state/{entity_state}/files/{file}`

`PATCH api/projects/{project}/entity-state/{entity_state}/files/{file}`


<!-- END_5d652a5c6a9d91e1e50b036e80099839 -->

<!-- START_527909cdcd9db46f2e95347a1e5e7a9e -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/entity-state/1/files/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/entity-state/{entity_state}/files/{file}`


<!-- END_527909cdcd9db46f2e95347a1e5e7a9e -->

<!-- START_3f49783269d5cf82b3fddc8edee47136 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/entity-state/1/attributes" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/entity-state/{entity_state}/attributes`


<!-- END_3f49783269d5cf82b3fddc8edee47136 -->

<!-- START_c8dbd5db9ea793c6cc1cfe643aeea4f1 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/entity-state/1/attributes" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/entity-state/{entity_state}/attributes`


<!-- END_c8dbd5db9ea793c6cc1cfe643aeea4f1 -->

<!-- START_bf5b412d5ddd833c4c755df1bc1a5287 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/entity-state/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/entity-state/{entity_state}/attributes/{attribute}`


<!-- END_bf5b412d5ddd833c4c755df1bc1a5287 -->

<!-- START_51a2563676b51672daf0007385588c5e -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/entity-state/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/entity-state/{entity_state}/attributes/{attribute}`

`PATCH api/projects/{project}/entity-state/{entity_state}/attributes/{attribute}`


<!-- END_51a2563676b51672daf0007385588c5e -->

<!-- START_d38d0bf8562dda34571988ea5c0513cb -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/entity-state/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/entity-state/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/entity-state/{entity_state}/attributes/{attribute}`


<!-- END_d38d0bf8562dda34571988ea5c0513cb -->

<!-- START_56dbc4bb2074a72e080b5ef3f813368b -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/attributes" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/attributes`


<!-- END_56dbc4bb2074a72e080b5ef3f813368b -->

<!-- START_019146af91b922d15a528bd0dcb632d6 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/attributes" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/attributes`


<!-- END_019146af91b922d15a528bd0dcb632d6 -->

<!-- START_6095f9a7dc3cb8a2160cc6fe1d8d119b -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/attributes/{attribute}`


<!-- END_6095f9a7dc3cb8a2160cc6fe1d8d119b -->

<!-- START_7de8b6214eff153fd3192d88964f4156 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/attributes/{attribute}`

`PATCH api/projects/{project}/attributes/{attribute}`


<!-- END_7de8b6214eff153fd3192d88964f4156 -->

<!-- START_e205cf8e945195715dfb53150c4b5646 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/attributes/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/attributes/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/attributes/{attribute}`


<!-- END_e205cf8e945195715dfb53150c4b5646 -->

<!-- START_bba78be3dcdaa13e11310ba42f2e43b4 -->
## Display a listing of the resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/values" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/values");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/values`


<!-- END_bba78be3dcdaa13e11310ba42f2e43b4 -->

<!-- START_182b2a3f90de9e99843fb1f22148b239 -->
## Store a newly created resource in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/projects/1/values" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/values");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/projects/{project}/values`


<!-- END_182b2a3f90de9e99843fb1f22148b239 -->

<!-- START_047447837015383eee73f40732258608 -->
## Display the specified resource.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1/values/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/values/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}/values/{value}`


<!-- END_047447837015383eee73f40732258608 -->

<!-- START_bfec1b1ad3cf3faffb4454988d56bd97 -->
## Update the specified resource in storage.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1/values/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/values/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}/values/{value}`

`PATCH api/projects/{project}/values/{value}`


<!-- END_bfec1b1ad3cf3faffb4454988d56bd97 -->

<!-- START_a201c75220411f08b348e016ac3c2f26 -->
## Remove the specified resource from storage.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1/values/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1/values/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}/values/{value}`


<!-- END_a201c75220411f08b348e016ac3c2f26 -->

<!-- START_ae20a6beba7f4129ed09833ac1728b99 -->
## Create an activity in a project.

> Example request:

```bash
curl -X POST "http://localhost/api/activities" 
```

```javascript
const url = new URL("http://localhost/api/activities");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities`


<!-- END_ae20a6beba7f4129ed09833ac1728b99 -->

<!-- START_14f3012e108ab6c46fd813d0751ace4b -->
## Handle the incoming request.

> Example request:

```bash
curl -X PUT "http://localhost/api/activities/1" 
```

```javascript
const url = new URL("http://localhost/api/activities/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/activities/{activity}`


<!-- END_14f3012e108ab6c46fd813d0751ace4b -->

<!-- START_e30c1ca5bbcb967bf61cf6c39f7acfa4 -->
## Handle the incoming request.

> Example request:

```bash
curl -X DELETE "http://localhost/api/activities/1" 
```

```javascript
const url = new URL("http://localhost/api/activities/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/activities/{activity}`


<!-- END_e30c1ca5bbcb967bf61cf6c39f7acfa4 -->

<!-- START_c80b1e4f2293abbba46e63e089e9da48 -->
## Handle the incoming request.

> Example request:

```bash
curl -X GET -G "http://localhost/api/activities/1" 
```

```javascript
const url = new URL("http://localhost/api/activities/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/activities/{activity}`


<!-- END_c80b1e4f2293abbba46e63e089e9da48 -->

<!-- START_bf96d76e65dfc4163a7e13a4a7f1b874 -->
## api/activities/{activity}/add-files
> Example request:

```bash
curl -X POST "http://localhost/api/activities/1/add-files" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/add-files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities/{activity}/add-files`


<!-- END_bf96d76e65dfc4163a7e13a4a7f1b874 -->

<!-- START_dc4e58e1226bc6b1df9c4764947e822e -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/activities/1/deletes-files" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/deletes-files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities/{activity}/deletes-files`


<!-- END_dc4e58e1226bc6b1df9c4764947e822e -->

<!-- START_62c0c2d72fb8e6637eec7a9035fa8320 -->
## Handle the incoming request.

> Example request:

```bash
curl -X PUT "http://localhost/api/activities/1/update-files" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/update-files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/activities/{activity}/update-files`


<!-- END_62c0c2d72fb8e6637eec7a9035fa8320 -->

<!-- START_eb02798bca5d98458b841561c4870dbd -->
## Handle the incoming request.

> Example request:

```bash
curl -X GET -G "http://localhost/api/activities/1/files" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/activities/{activity}/files`


<!-- END_eb02798bca5d98458b841561c4870dbd -->

<!-- START_98ed5e1d37687c86311de98a218b35af -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/activities/1/add-attributes" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/add-attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities/{activity}/add-attributes`


<!-- END_98ed5e1d37687c86311de98a218b35af -->

<!-- START_f2015bbd700f523fdbf7a98f611a771d -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/activities/1/add-attribute" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/add-attribute");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities/{activity}/add-attribute`


<!-- END_f2015bbd700f523fdbf7a98f611a771d -->

<!-- START_ef9e27b30caa2fa2f4977de9faebec61 -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/activities/1/delete-attributes" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/delete-attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities/{activity}/delete-attributes`


<!-- END_ef9e27b30caa2fa2f4977de9faebec61 -->

<!-- START_1d5a2713e97f03ae0e82fbc304751719 -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/activities/1/delete-attribute/1" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/delete-attribute/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities/{activity}/delete-attribute/{attribute}`


<!-- END_1d5a2713e97f03ae0e82fbc304751719 -->

<!-- START_399f0023e23050eb25c70a92c3cfff53 -->
## Handle the incoming request.

> Example request:

```bash
curl -X PUT "http://localhost/api/activities/1/update-attributes" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/update-attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/activities/{activity}/update-attributes`


<!-- END_399f0023e23050eb25c70a92c3cfff53 -->

<!-- START_878efbd87606c48f946097aeff813d97 -->
## Handle the incoming request.

> Example request:

```bash
curl -X PUT "http://localhost/api/activities/1/update-attribute/1" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/update-attribute/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/activities/{activity}/update-attribute/{attribute}`


<!-- END_878efbd87606c48f946097aeff813d97 -->

<!-- START_13597537eeea430dcb633b791aff7574 -->
## Handle the incoming request.

> Example request:

```bash
curl -X GET -G "http://localhost/api/activities/1/attributes" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/attributes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/activities/{activity}/attributes`


<!-- END_13597537eeea430dcb633b791aff7574 -->

<!-- START_46e322eb505d553fb2a6af2d0995b9ed -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/activities/1/add-entity-states" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/add-entity-states");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities/{activity}/add-entity-states`


<!-- END_46e322eb505d553fb2a6af2d0995b9ed -->

<!-- START_02a9220fba47d11522d0aa449a141674 -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/activities/1/delete-entity-states" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/delete-entity-states");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/activities/{activity}/delete-entity-states`


<!-- END_02a9220fba47d11522d0aa449a141674 -->

<!-- START_199d9fa6499861e2c83c67d6029f2a1a -->
## Handle the incoming request.

> Example request:

```bash
curl -X PUT "http://localhost/api/activities/1/update-entity-states" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/update-entity-states");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/activities/{activity}/update-entity-states`


<!-- END_199d9fa6499861e2c83c67d6029f2a1a -->

<!-- START_6c342702bbe9fe779e93d949ee539ec2 -->
## Handle the incoming request.

> Example request:

```bash
curl -X GET -G "http://localhost/api/activities/1/entity-states" 
```

```javascript
const url = new URL("http://localhost/api/activities/1/entity-states");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/activities/{activity}/entity-states`


<!-- END_6c342702bbe9fe779e93d949ee539ec2 -->

<!-- START_8b1fcb68a56215cf6324831869893c99 -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/entities" 
```

```javascript
const url = new URL("http://localhost/api/entities");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/entities`


<!-- END_8b1fcb68a56215cf6324831869893c99 -->

<!-- START_88719b501344d3ef557208b0e7da198c -->
## Handle the incoming request.

> Example request:

```bash
curl -X PUT "http://localhost/api/entities/1" 
```

```javascript
const url = new URL("http://localhost/api/entities/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/entities/{entity}`


<!-- END_88719b501344d3ef557208b0e7da198c -->

<!-- START_fd5524b478b77f2061809754c9d51393 -->
## Handle the incoming request.

> Example request:

```bash
curl -X DELETE "http://localhost/api/entitites/1" 
```

```javascript
const url = new URL("http://localhost/api/entitites/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/entitites/{entity}`


<!-- END_fd5524b478b77f2061809754c9d51393 -->

<!-- START_c8a6cd9605affdc093e041ad7405390c -->
## Handle the incoming request.

> Example request:

```bash
curl -X GET -G "http://localhost/api/entities/1" 
```

```javascript
const url = new URL("http://localhost/api/entities/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/entities/{entity}`


<!-- END_c8a6cd9605affdc093e041ad7405390c -->

<!-- START_f178682e39dcb66c17814ee8c3203fcc -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/files" 
```

```javascript
const url = new URL("http://localhost/api/files");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/files`


<!-- END_f178682e39dcb66c17814ee8c3203fcc -->

<!-- START_736a3ae548e1519d3995db9a1565520b -->
## Handle the incoming request.

> Example request:

```bash
curl -X PUT "http://localhost/api/files/1" 
```

```javascript
const url = new URL("http://localhost/api/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/files/{file}`


<!-- END_736a3ae548e1519d3995db9a1565520b -->

<!-- START_7fec844060fbf8f1a57014ed9b6c8e71 -->
## Handle the incoming request.

> Example request:

```bash
curl -X DELETE "http://localhost/api/files/1" 
```

```javascript
const url = new URL("http://localhost/api/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/files/{file}`


<!-- END_7fec844060fbf8f1a57014ed9b6c8e71 -->

<!-- START_7b4675bc9407f93c6e7e172dbe0d62e8 -->
## Show the file.

> Example request:

```bash
curl -X GET -G "http://localhost/api/files/1" 
```

```javascript
const url = new URL("http://localhost/api/files/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/files/{file}`


<!-- END_7b4675bc9407f93c6e7e172dbe0d62e8 -->

<!-- START_2b738d5901d80c82d2eea10a74e02d13 -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/files/1/move" 
```

```javascript
const url = new URL("http://localhost/api/files/1/move");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/files/{file}/move`


<!-- END_2b738d5901d80c82d2eea10a74e02d13 -->

<!-- START_89730c578257ae22c459d10a91df340c -->
## Rename a file and all its previous versions

> Example request:

```bash
curl -X POST "http://localhost/api/files/1/rename" 
```

```javascript
const url = new URL("http://localhost/api/files/1/rename");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/files/{file}/rename`


<!-- END_89730c578257ae22c459d10a91df340c -->

<!-- START_5ae9c94dba97ccf7f5762fc08212f0d2 -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/directories" 
```

```javascript
const url = new URL("http://localhost/api/directories");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/directories`


<!-- END_5ae9c94dba97ccf7f5762fc08212f0d2 -->

<!-- START_580ec0234a42154726f2a07d99d2ec36 -->
## Handle the incoming request.

> Example request:

```bash
curl -X PUT "http://localhost/api/directories/1" 
```

```javascript
const url = new URL("http://localhost/api/directories/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/directories/{directory}`


<!-- END_580ec0234a42154726f2a07d99d2ec36 -->

<!-- START_963f32f7612680b51fe393e023f7a8c6 -->
## Handle the incoming request.

> Example request:

```bash
curl -X DELETE "http://localhost/api/directories/1" 
```

```javascript
const url = new URL("http://localhost/api/directories/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/directories/{directory}`


<!-- END_963f32f7612680b51fe393e023f7a8c6 -->

<!-- START_e2dd17264008f7d07914c26d822d592b -->
## Show the directory

> Example request:

```bash
curl -X GET -G "http://localhost/api/directories/1" 
```

```javascript
const url = new URL("http://localhost/api/directories/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/directories/{directory}`


<!-- END_e2dd17264008f7d07914c26d822d592b -->

<!-- START_eec90bf26f0b1b0cc0c07ad97d5a1e70 -->
## Move a directory and recursively all its children

> Example request:

```bash
curl -X POST "http://localhost/api/directories/1/move" 
```

```javascript
const url = new URL("http://localhost/api/directories/1/move");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/directories/{directory}/move`


<!-- END_eec90bf26f0b1b0cc0c07ad97d5a1e70 -->

<!-- START_79f161908eef6ba66986dc61488b9063 -->
## Handle the incoming request.

> Example request:

```bash
curl -X POST "http://localhost/api/directories/1/rename" 
```

```javascript
const url = new URL("http://localhost/api/directories/1/rename");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/directories/{directory}/rename`


<!-- END_79f161908eef6ba66986dc61488b9063 -->

<!-- START_893ae955e8991ef06f6de91adbff0aaa -->
## List all projects the user has access to.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects" 
```

```javascript
const url = new URL("http://localhost/api/projects");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects`


<!-- END_893ae955e8991ef06f6de91adbff0aaa -->

<!-- START_ef65c5a82b5c66255f0be53e107acb2d -->
## Update a project.

> Example request:

```bash
curl -X PUT "http://localhost/api/projects/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/projects/{project}`


<!-- END_ef65c5a82b5c66255f0be53e107acb2d -->

<!-- START_70c859bdcb978e6cdba659235c2083d3 -->
## Handle the incoming request.

> Example request:

```bash
curl -X DELETE "http://localhost/api/projects/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`DELETE api/projects/{project}`


<!-- END_70c859bdcb978e6cdba659235c2083d3 -->

<!-- START_62d96e2c27434ddb7c604817f783bed8 -->
## Show details for a specific project.

> Example request:

```bash
curl -X GET -G "http://localhost/api/projects/1" 
```

```javascript
const url = new URL("http://localhost/api/projects/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Unauthenticated."
}
```

### HTTP Request
`GET api/projects/{project}`


<!-- END_62d96e2c27434ddb7c604817f783bed8 -->

<!-- START_66e08d3cc8222573018fed49e121e96d -->
## Show the application&#039;s login form.

> Example request:

```bash
curl -X GET -G "http://localhost/login" 
```

```javascript
const url = new URL("http://localhost/login");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET login`


<!-- END_66e08d3cc8222573018fed49e121e96d -->

<!-- START_ba35aa39474cb98cfb31829e70eb8b74 -->
## Handle a login request to the application.

> Example request:

```bash
curl -X POST "http://localhost/login" 
```

```javascript
const url = new URL("http://localhost/login");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST login`


<!-- END_ba35aa39474cb98cfb31829e70eb8b74 -->

<!-- START_e65925f23b9bc6b93d9356895f29f80c -->
## Log the user out of the application.

> Example request:

```bash
curl -X POST "http://localhost/logout" 
```

```javascript
const url = new URL("http://localhost/logout");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST logout`


<!-- END_e65925f23b9bc6b93d9356895f29f80c -->

<!-- START_ff38dfb1bd1bb7e1aa24b4e1792a9768 -->
## Show the application registration form.

> Example request:

```bash
curl -X GET -G "http://localhost/register" 
```

```javascript
const url = new URL("http://localhost/register");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET register`


<!-- END_ff38dfb1bd1bb7e1aa24b4e1792a9768 -->

<!-- START_d7aad7b5ac127700500280d511a3db01 -->
## Handle a registration request for the application.

> Example request:

```bash
curl -X POST "http://localhost/register" 
```

```javascript
const url = new URL("http://localhost/register");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST register`


<!-- END_d7aad7b5ac127700500280d511a3db01 -->

<!-- START_d72797bae6d0b1f3a341ebb1f8900441 -->
## Display the form to request a password reset link.

> Example request:

```bash
curl -X GET -G "http://localhost/password/reset" 
```

```javascript
const url = new URL("http://localhost/password/reset");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET password/reset`


<!-- END_d72797bae6d0b1f3a341ebb1f8900441 -->

<!-- START_feb40f06a93c80d742181b6ffb6b734e -->
## Send a reset link to the given user.

> Example request:

```bash
curl -X POST "http://localhost/password/email" 
```

```javascript
const url = new URL("http://localhost/password/email");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/email`


<!-- END_feb40f06a93c80d742181b6ffb6b734e -->

<!-- START_e1605a6e5ceee9d1aeb7729216635fd7 -->
## Display the password reset view for the given token.

If no token is present, display the link request form.

> Example request:

```bash
curl -X GET -G "http://localhost/password/reset/1" 
```

```javascript
const url = new URL("http://localhost/password/reset/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET password/reset/{token}`


<!-- END_e1605a6e5ceee9d1aeb7729216635fd7 -->

<!-- START_cafb407b7a846b31491f97719bb15aef -->
## Reset the given user&#039;s password.

> Example request:

```bash
curl -X POST "http://localhost/password/reset" 
```

```javascript
const url = new URL("http://localhost/password/reset");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/reset`


<!-- END_cafb407b7a846b31491f97719bb15aef -->

<!-- START_cb859c8e84c35d7133b6a6c8eac253f8 -->
## Show the application dashboard.

> Example request:

```bash
curl -X GET -G "http://localhost/home" 
```

```javascript
const url = new URL("http://localhost/home");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET home`


<!-- END_cb859c8e84c35d7133b6a6c8eac253f8 -->

<!-- START_910230cbc51d9374cfc60498e7d22411 -->
## public
> Example request:

```bash
curl -X GET -G "http://localhost/public" 
```

```javascript
const url = new URL("http://localhost/public");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET public`


<!-- END_910230cbc51d9374cfc60498e7d22411 -->

<!-- START_96d044950d6b8960cecdd9551c94ccf2 -->
## Return all published datasets for datatables

> Example request:

```bash
curl -X GET -G "http://localhost/getAllPublishedDatasets" 
```

```javascript
const url = new URL("http://localhost/getAllPublishedDatasets");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET getAllPublishedDatasets`


<!-- END_96d044950d6b8960cecdd9551c94ccf2 -->

<!-- START_4fca969d5ef09ae5bb5b580175846a0f -->
## public/new
> Example request:

```bash
curl -X GET -G "http://localhost/public/new" 
```

```javascript
const url = new URL("http://localhost/public/new");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET public/new`


<!-- END_4fca969d5ef09ae5bb5b580175846a0f -->

<!-- START_e2472eb8d0586538dc55f2132acc79e1 -->
## public/projects
> Example request:

```bash
curl -X GET -G "http://localhost/public/projects" 
```

```javascript
const url = new URL("http://localhost/public/projects");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET public/projects`


<!-- END_e2472eb8d0586538dc55f2132acc79e1 -->

<!-- START_56a0de0d55b82e712bb6c7f4a6cd66bd -->
## public/datasets
> Example request:

```bash
curl -X GET -G "http://localhost/public/datasets" 
```

```javascript
const url = new URL("http://localhost/public/datasets");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET public/datasets`


<!-- END_56a0de0d55b82e712bb6c7f4a6cd66bd -->

<!-- START_8a4737c8f4d498688b93113b2015c733 -->
## Show dataset details

> Example request:

```bash
curl -X GET -G "http://localhost/public/datasets/1" 
```

```javascript
const url = new URL("http://localhost/public/datasets/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET public/datasets/{dataset}`


<!-- END_8a4737c8f4d498688b93113b2015c733 -->

<!-- START_bca6fbe10f2f34acb69a610d56471ac4 -->
## public/authors
> Example request:

```bash
curl -X GET -G "http://localhost/public/authors" 
```

```javascript
const url = new URL("http://localhost/public/authors");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET public/authors`


<!-- END_bca6fbe10f2f34acb69a610d56471ac4 -->

<!-- START_bcb44cd839254d662fdd7b65ac46897b -->
## public/tags
> Example request:

```bash
curl -X GET -G "http://localhost/public/tags" 
```

```javascript
const url = new URL("http://localhost/public/tags");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET public/tags`


<!-- END_bcb44cd839254d662fdd7b65ac46897b -->

<!-- START_cf12053fb96f008b5b34834046f0c7f0 -->
## Invoke the controller method.

> Example request:

```bash
curl -X GET -G "http://localhost/public/community" 
```

```javascript
const url = new URL("http://localhost/public/community");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET public/community`


<!-- END_cf12053fb96f008b5b34834046f0c7f0 -->


