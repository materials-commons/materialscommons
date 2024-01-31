<div>
    <ul>
        @foreach($users as $user)
            <li>
                <span class="fs-10 grey-5">Name: {{$user->name}}</span>
                <span class="fs-10 grey-5 ml-3">Email: {{$user->email}}</span>
                <span class="fs-10 grey-5 ml-3">ID: {{$user->id}}</span>
            </li>
        @endforeach
    </ul>
</div>