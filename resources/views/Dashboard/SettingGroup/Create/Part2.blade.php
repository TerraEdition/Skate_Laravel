@php
    $total_seat = (int) $data['total_seat'][0]['data'];
    $start = 0;
    $offset = ceil($group->total_participant / $total_seat);
@endphp
<div class="row">
    <div class="col-6">
        <form action="{{ url()->current() }}" method="POST" id="form_seat">
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
                        <div class="card-body card-seat" id="seat{{ $i }}-content">
                            @if ($total_seat == 1)
                                @foreach ($participant as $p)
                                    <div draggable="true" ondragstart="drag(event)">
                                        <div class="border border-2 border-dark p-2" title="{{ $p->team }}">
                                            {{ $p->member }} - {{ $p->team_initial }}
                                        </div>
                                        <input type="hidden" value="{{ $p->id }}" data-seat_no='participants'>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endfor
        <div class="d-flex gap-2">
            <a href="{{ url()->current() }}/back" class="btn btn-sm btn-danger">Reset dan Ulang</a>
            <div class="btn btn-sm btn-success" id="save_btn">Simpan</div>
        </div>
    </div>
    <div class="col-6">
        @if ($total_seat > 1)
            <div class="card">
                <div class="card-header">
                    Peserta
                </div>
                <div class="card-body card-participant d-flex flex-wrap gap-3" ondragover="allowDrop(event)"
                    ondrop="drop(event, 'participants')" id="participants-content">
                    @foreach ($participant as $p)
                        <div draggable="true" ondragstart="drag(event)">
                            <div class="border border-2 border-dark p-2" title="{{ $p->team }}">
                                {{ $p->member }} - {{ $p->team_initial }}
                            </div>
                            <input type="hidden" value="{{ $p->id }}" data-seat_no='participants'>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card-participant"></div>
        @endif
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
        const data = event.dataTransfer.getData("text");
        const draggedElement = document.createElement("div");
        draggedElement.innerHTML = data;
        draggedElement.draggable = true;
        draggedElement.addEventListener('dragstart', drag);
        draggedElement.children[1].setAttribute('data-seat_no', target)
        if (target === 'participants') {
            const participantsContent = document.getElementById('participants-content');
            if (participantsContent) {
                participantsContent.appendChild(draggedElement);
            }
        } else {
            const targetContent = document.getElementById(`seat${target}-content`);
            if (targetContent) {
                targetContent.appendChild(draggedElement);
            }
        }

        // Remove the original element from Participants
        if (draggedItem && draggedItem.parentNode) {
            draggedItem.parentNode.removeChild(draggedItem);
        }
    }

    document.querySelector('#save_btn').addEventListener('click', function() {
        const passes = "{{ $data['total_seat'][0]['passes_position'] }}"
        const card_seat = document.querySelectorAll(".card-seat");
        const card_participant = document.querySelector(".card-participant");
        const form_seat = document.querySelector('#form_seat');
        if (card_participant.children.length > 0) {
            alert("Masih ada peserta yang masih tersedia")
        } else {
            error = false;
            [...card_seat].some((a, i) => {
                if (a.children.length <= passes) {
                    error = true;
                    alert('Seat ' + (i + 1) + ' tidak memiliki peserta yang cukup');
                    [...form_seat.children].forEach((v, k) => {
                        if (k > 0) {
                            v.remove();
                        }
                    });
                    return true;
                } else {
                    [...a.children].forEach(c => {
                        const input = document.createElement('input');
                        input.setAttribute('name', 'seat[' + i + '][]');
                        input.value = c.children[1].value;
                        input.setAttribute('type', 'hidden')
                        form_seat.appendChild(input);
                    })
                    return false;
                }
            })
            if (!error) {
                form_seat.submit();
            }
        }
    })
</script>
