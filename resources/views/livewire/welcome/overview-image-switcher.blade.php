<div>
    <div class="row justify-content-center">
        <div class="btn-group" role="group" style="background-color: #20376a; padding: 3px; border-radius: 24px">
            <button type="button" class="btn btn-secondary mr-2"
                    wire:click="switchToImage('organize')"
                    style="border-radius: 24px; background-color: #2b61a7">
                <i class="fa fa-fw fa-folder-open mr-2"></i> Store
            </button>
            <button type="button" class="btn btn-secondary mr-2"
                    wire:click="switchToImage('analyze')"
                    style="border-radius: 24px; background-color: #2b61a7">
                <i class="fa fa-fw fa-project-diagram mr-2"></i> Share
            </button>
            <button type="button" class="btn btn-secondary"
                    wire:click="switchToImage('publish')"
                    style="border-radius: 24px; background-color: #2b61a7">
                <i class="fa fa-fw fa-file-export mr-2"></i> Publish
            </button>
        </div>
    </div>

    @if($showImage == "organize")
        <br/>
        <br/>
        <h3 style="color: #ffffff" class="text-center">We ensure your data is securly and redundantly stored</h3>
        <img src="{{asset('images/welcome/organize.jpg')}}"
             class="img-fluid mt-4" alt="Responsive image"
             style="border: 10px solid rgba(43, 107, 177, 0.3); border-radius: 8px"/>
    @elseif($showImage == "analyze")
        <br/>
        <br/>
        <h3 style="color: #ffffff" class="text-center">Share and give access to your research assets</h3>
        <img src="{{asset('images/welcome/analyze.jpg')}}"
             class="img-fluid mt-4" alt="Responsive image"
             style="border: 10px solid rgba(43, 107, 177, 0.3); border-radius: 8px"/>
    @else
        <br/>
        <br/>
        <h3 style="color: #ffffff" class="text-center">When your ready publish your research</h3>
        <img src="{{asset('images/welcome/publish.jpg')}}"
             class="img-fluid mt-4" alt="Responsive image"
             style="border: 10px solid rgba(43, 107, 177, 0.3); border-radius: 8px"/>
    @endif
</div>
