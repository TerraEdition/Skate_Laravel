<div>
    <form action="/{{ $url }}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm m-1 btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Hapus</button>
    </form>
</div>