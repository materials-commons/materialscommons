<?php
/**
* @apiDefine AuthenticationError
* @apiErrorExample {json} Error-Response:
* HTTP/1.1 401 Unauthorized
* {
*      "message": "Unauthenticated."
* }
*/

/**
* @apiDefine ValidationError
* @apiErrorExample {json} Error-Response:
* HTTP/1.1 422 Unprocessable Entity
* {
*      "errors": {
*          "project_id": [
*              "The project id field is required."
*          ]
*      },
*      "message": "The given data was invalid."
* }
*/

/**
 * @apiDefine APITokenParam
 * @apiParam (URL Parameters) {String} api_token Mandatory API token
 */


