<pre>
# Get project details
project = c.get_project({{$project->id}})

# Delete project
c.delete_project({{$project->id}})

# Change attributes of the project
req = mcapi.UpdateProjectRequest(description="my new description")
project = c.update_project({{$project->id}}, req)

# Add user to project
user = c.get_user_by_email("user@email.com")
project = c.add_user_to_project({{$project->id}}, user.id)

# Remove user from project
# Remove the second user in the project
user = project.members[1]
c.remove_user_from_project({{$project->id}}, user.id)
</pre>