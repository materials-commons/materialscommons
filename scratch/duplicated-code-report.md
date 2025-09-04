# Duplicated Code Report (File model and related services)

Generated: 2025-09-03 11:07 local time

This report summarizes duplicated or overlapping logic centered around `app/Models/File.php` and other parts of the codebase, based on the latest repository snapshot.

## Summary of Findings

1. File version activation logic duplicated
   - app/Models/File.php: File::setAsActiveFile
   - app/Services/Files/FileVersioningService.php: FileVersioningService::setActive
   - Description: Both methods wrap a DB transaction to mark all sibling versions (same directory_id + name, excluding dataset and deleted) as not current, then mark the target file as current. The service returns a fresh File; the model method does not.
   - Impact: Two sources of truth for the same behavior increase maintenance cost and risk of divergence.
   - Suggestion: Prefer the service (`FileVersioningService::setActive`) as the single implementation, and deprecate/forward `File::setAsActiveFile` to the service to avoid duplication.

2. File UUID selection logic duplicated
   - app/Models/File.php: File::getFileUuidToUse
   - app/Services/Files/FilePathResolver.php: FilePathResolver::getFileUuidToUse (private)
   - Description: Both resolve the UUID to use, favoring `uses_uuid` when present; otherwise `uuid`.
   - Impact: Logic drift is possible (e.g., future rules) since one copy is private inside the resolver and another is public in the model.
   - Suggestion: Keep a single public source (e.g., File::getFileUuidToUse) and have FilePathResolver call it, or extract a small helper service/trait used by both.

3. Project path grouping logic duplicated
   - app/Services/Files/FilePathResolver.php: FilePathResolver::projectPathDirPartial
   - app/Models/Project.php: Project::projectFilesDir
   - Shared pattern: `intdiv(<id>, 10)` to create a directory grouping, then place under `projects/{group}/{project_id}`.
   - Impact: If the grouping scheme changes, two locations must be updated. One returns a partial path string; the other uses Storage::disk('mcfs')->path with the full path.
   - Suggestion: Centralize the grouping calculation in one place (e.g., FilePathResolver or a new PathHelper) and have Project delegate to it for consistency.

## Additional Notes

- Many path-related helpers in `File` correctly delegate to services (FilePathResolver, FileArtifactService), which is good separation of concerns and avoids duplication.
- Convertible file-type decisions (images, office docs, Jupyter notebooks) are centralized in `File` and used by FilePathResolver to append appropriate extensions.

## Proposed Remediation Steps

- Deprecate `File::setAsActiveFile` and internally call `FileVersioningService::setActive($this)`; mark the model method as a convenience wrapper. Update call sites over time to use the service directly.
- Remove the private duplicate `getFileUuidToUse` from FilePathResolver and instead call `$file->getFileUuidToUse()`. This preserves a single source in the model.
- Extract a shared helper for project path grouping, e.g., `App\Support\PathHelpers::projectPathDirPartial(Project|File $subject): string`, and refactor both `Project::projectFilesDir` and `FilePathResolver::projectPathDirPartial` to use it.

## Code References

- File model: app/Models/File.php (notably methods: setAsActiveFile, getFileUuidToUse, projectPathDirPartial delegator)
- File path resolver: app/Services/Files/FilePathResolver.php (methods: pathDirPartial, realPathPartial, convertedPathPartial, thumbnailPathPartial, projectPathDirPartial, and private getFileUuidToUse)
- File versioning service: app/Services/Files/FileVersioningService.php (method: setActive)
- Project model: app/Models/Project.php (method: projectFilesDir)

---
This document was generated to satisfy the request to store the duplicated code report in the repository under `scratch/`. If you’d like it in a different location or format, let me know.
