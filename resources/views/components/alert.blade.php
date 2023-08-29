<div>
    @if(Session::has('message'))
    <div class="alert {{ Session::get('bg') }} my-3" role="alert">
        {{ Session::get('message') }}
    </div>
    @endif
</div>