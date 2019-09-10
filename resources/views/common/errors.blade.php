@if ($errors->any())
    <div class="bg-red-6 text-white">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Testing popup</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <script>
        setTimeout(() => $(".alert").alert('close'), 2000);
    </script>
@endif