<div class="mt-2">
    <h5>Object Types:</h5>
    <ul>
        <li>Processes ({{$objectCounts->activitiesCount}})</li>
        <li>Samples ({{$objectCounts->entitiesCount}})</li>

        @isset($objectCounts->filesCount)
            <li>Files ({{$objectCounts->filesCount}})</li>
        @endisset

        @isset($objectCounts->experimentsCount)
            <li>Experiments ({{$objectCounts->experimentsCount}})</li>
        @endisset

        @isset($objectCounts->publishedDatasetsCount)
            <li>Published Datasets ({{$objectCounts->publishedDatasetsCount}})</li>
        @endisset

        @isset($objectCounts->unpublishedDatasetsCount)
            <li>Unpublished Datasets ({{$objectCounts->unpublishedDatasetsCount}})</li>
        @endisset
    </ul>
</div>