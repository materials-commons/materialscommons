<div class="mt-2">
    <h5>Object Types:</h5>
    <ul>
        <li>Processes ({{$objectCounts->activitiesCount}})</li>
        <li>Samples ({{$objectCounts->entitiesCount}})</li>
        <li>Files ({{$objectCounts->filesCount}})</li>
        @isset($objectCounts->experimentsCount)
            <li>Experiments ({{$objectCounts->experimentsCount}})</li>
        @endisset
        <li>Published Datasets ({{$objectCounts->publishedDatasetsCount}})</li>
        <li>Unpublished Datasets ({{$objectCounts->unpublishedDatasetsCount}})</li>
    </ul>
</div>