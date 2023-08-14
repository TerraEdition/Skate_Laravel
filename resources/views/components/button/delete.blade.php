<div>
    <form action="/{{$url}}/{{ $id}}" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm m-1 btn-danger"><i class="fa-solid fa-trash"></i> Hapus</button>
    </form>
</div>