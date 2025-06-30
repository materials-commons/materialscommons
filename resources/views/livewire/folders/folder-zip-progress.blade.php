<div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg p-4">
        <div class="mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Zip File Creation Progress
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                @if($status === 'waiting')
                    Preparing to create zip file...
                @elseif($status === 'processing')
                    Creating zip file...
                @elseif($status === 'completed')
                    Zip file creation completed!
                @else
                    {{ $status }}
                @endif
            </p>
        </div>

        <div class="mb-4">
            <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-indigo-600 bg-indigo-200">
                            Progress
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold inline-block text-indigo-600">
                            {{ $progress }}%
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-indigo-200">
                    <div style="width:{{ $progress }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500 transition-all duration-500"></div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <p class="text-sm text-gray-600">
                Processed {{ $processedFiles }} of {{ $totalFiles }} files
            </p>
        </div>

        @if($showDownloadButton)
            <div class="mt-4">
                <a href="{{ $downloadUrl }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Download Zip File
                </a>
                <p class="mt-2 text-sm text-gray-500">Your download should start automatically. If it doesn't, click the button above.</p>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Start polling for progress updates
            setInterval(() => {
                @this.checkZipProgress();
            }, 1000);
            
            // Listen for zip completed event to trigger download
            Livewire.on('zipCompleted', (data) => {
                setTimeout(() => {
                    window.location.href = data.downloadUrl;
                }, 1000);
            });
        });
    </script>
</div>