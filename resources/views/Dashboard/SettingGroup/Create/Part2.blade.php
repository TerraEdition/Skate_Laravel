@php
    $total_seat = (int) $data['total_seat'][0]['data'];
    $start = 0;
    $offset = ceil($group->total_participant / $total_seat);
@endphp
<div class="row">
    <div class="col-6">
        <form action="{{ url()->current() }}" method="POST">
            @csrf
        </form>
        @for ($i = 1; $i <= $total_seat; $i++)
            <div class="mb-3">
                <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#seat{{ $i }}" aria-expanded="false">
                    Seat {{ $i }}
                </button>
                <div class="collapse show" id="seat{{ $i }}">
                    <div class="card mt-3" ondragover="allowDrop(event)" ondrop="drop(event, {{ $i }})">
                        <div class="card-body" id="seat{{ $i }}-content">

                        </div>
                    </div>
                </div>
            </div>
        @endfor
        <div class="d-flex gap-2">
            <a href="{{ url()->current() }}/back" class="btn btn-sm btn-danger">Reset dan Ulang</a>
            <div class="btn btn-sm btn-success">Simpan</div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                Peserta
            </div>
            <div class="card-body d-flex flex-wrap gap-3" ondragover="allowDrop(event)"
                ondrop="drop(event, 'participants')" id="participants-content">
                @foreach ($participant as $p)
                    <div draggable="true" ondragstart="drag(event)">
                        <div class="border border-2 border-dark p-2">
                            {{ $p->member }}
                        </div>
                        <input type="hidden" value="{{ $p->id }}" data-seat_no='participants'>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    var draggedItem = null;

    function allowDrop(event) {
        event.preventDefault();
    }

    function drag(event) {
        draggedItem = event.target;
        event.stopPropagation(); // Stop the event propagation
        event.dataTransfer.setData("text", event.target.innerHTML);
    }

    function drop(event, target) {
        event.preventDefault();
        var data = event.dataTransfer.getData("text");
        var draggedElement = document.createElement("div");
        draggedElement.innerHTML = data;
        draggedElement.draggable = true;
        draggedElement.addEventListener('dragstart', drag);
        draggedElement.children[1].setAttribute('data-seat_no', target)
        if (target === 'participants') {
            var participantsContent = document.getElementById('participants-content');
            if (participantsContent) {
                participantsContent.appendChild(draggedElement);
            }
        } else {
            var targetContent = document.getElementById(`seat${target}-content`);
            if (targetContent) {
                targetContent.appendChild(draggedElement);
            }
        }

        // Remove the original element from Participants
        if (draggedItem && draggedItem.parentNode) {
            draggedItem.parentNode.removeChild(draggedItem);
        }

    }
</script>
