define({ "api": [
  {
    "type": "post",
    "url": "/activities",
    "title": "Create a new activity",
    "group": "Activities",
    "name": "CreateActivity",
    "description": "<p>Creates a new activity in a project</p>",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 201 Created\n{\n     \"created_at\": \"2019-08-19 15:00:56\",\n      \"id\": 1,\n      \"name\": \"activity1\",\n      \"owner_id\": 1,\n      \"project_id\": \"1\",\n      \"updated_at\": \"2019-08-19 15:00:56\",\n      \"uuid\": \"2eab0d89-bc15-408e-b0fb-772c8bf216dd\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "routes/api_routes/activities_api.php",
    "groupTitle": "Activities",
    "parameter": {
      "fields": {
        "URL Parameters": [
          {
            "group": "URL Parameters",
            "type": "String",
            "optional": false,
            "field": "api_token",
            "description": "<p>Mandatory API token</p>"
          }
        ],
        "Body Parameters": [
          {
            "group": "Body Parameters",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Mandatory name of activity</p>"
          },
          {
            "group": "Body Parameters",
            "type": "Integer",
            "optional": false,
            "field": "project_id",
            "description": "<p>Mandatory id of project that Activity should belong to</p>"
          },
          {
            "group": "Body Parameters",
            "type": "String",
            "optional": true,
            "field": "description",
            "description": "<p>Optional description for activity</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 401 Unauthorized\n{\n     \"message\": \"Unauthenticated.\"\n}",
          "type": "json"
        },
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 422 Unprocessable Entity\n{\n     \"errors\": {\n         \"project_id\": [\n             \"The project id field is required.\"\n         ]\n     },\n     \"message\": \"The given data was invalid.\"\n}",
          "type": "json"
        }
      ]
    }
  },
  {
    "type": "delete",
    "url": "/activities/{activity_id}",
    "title": "Delete an existing activity",
    "group": "Activities",
    "name": "DeleteActivity",
    "description": "<p>Delete an existing activity and all of its relationships, does not delete the items the relationships point at.</p>",
    "version": "0.0.0",
    "filename": "routes/api_routes/activities_api.php",
    "groupTitle": "Activities",
    "parameter": {
      "fields": {
        "URL Parameters": [
          {
            "group": "URL Parameters",
            "type": "String",
            "optional": false,
            "field": "api_token",
            "description": "<p>Mandatory API token</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/activities/{activity_id}",
    "title": "Show an existing activity",
    "group": "Activities",
    "name": "ShowActivity",
    "description": "<p>Show an existing activity</p>",
    "version": "0.0.0",
    "filename": "routes/api_routes/activities_api.php",
    "groupTitle": "Activities",
    "parameter": {
      "fields": {
        "URL Parameters": [
          {
            "group": "URL Parameters",
            "type": "String",
            "optional": false,
            "field": "api_token",
            "description": "<p>Mandatory API token</p>"
          }
        ]
      }
    }
  },
  {
    "type": "put",
    "url": "/activities/{activity_id}",
    "title": "Update an existing activity",
    "group": "Activities",
    "name": "UpdateActivity",
    "description": "<p>Updates attributes on an existing activity</p>",
    "version": "0.0.0",
    "filename": "routes/api_routes/activities_api.php",
    "groupTitle": "Activities",
    "parameter": {
      "fields": {
        "URL Parameters": [
          {
            "group": "URL Parameters",
            "type": "String",
            "optional": false,
            "field": "api_token",
            "description": "<p>Mandatory API token</p>"
          }
        ],
        "Body Parameters": [
          {
            "group": "Body Parameters",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Mandatory name of activity</p>"
          },
          {
            "group": "Body Parameters",
            "type": "Integer",
            "optional": false,
            "field": "project_id",
            "description": "<p>Mandatory id of project that Activity should belong to</p>"
          },
          {
            "group": "Body Parameters",
            "type": "String",
            "optional": true,
            "field": "description",
            "description": "<p>Optional description for activity</p>"
          }
        ]
      }
    }
  }
] });
