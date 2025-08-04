<x-card-container>
    <br>
    @isset($dataset->file_selection["include_files"])
        <h4>File Includes</h4>
        <ul>
            @foreach($dataset->file_selection["include_files"] as $includeFile)
                <li>{{$includeFile}}</li>
            @endforeach
        </ul>
    @endisset

    @isset($dataset->file_selection["include_dirs"])
        <h4>Directory Includes</h4>
        <ul>
            @foreach($dataset->file_selection["include_dirs"] as $includeDir)
                <li>{{$includeDir}}</li>
            @endforeach
        </ul>
    @endisset

    @isset($dataset->file_selection["exclude_files"])
        <h4>File Exclusions</h4>
        <ul>
            @foreach($dataset->file_selection["exclude_files"] as $excludeFile)
                <li>{{$excludeFile}}</li>
            @endforeach
        </ul>
    @endisset

    @isset($dataset->file_selection["exclude_dirs"])
        <h4>Directory Exclusions</h4>
        <ul>
            @foreach($dataset->file_selection["exclude_dirs"] as $excludeDir)
                <li>{{$excludeDir}}</li>
            @endforeach
        </ul>
    @endisset
</x-card-container>
