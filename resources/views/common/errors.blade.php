@if ($errors->any())
    <br>
    <br>
    <div class="bg-red-6 text-white">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
