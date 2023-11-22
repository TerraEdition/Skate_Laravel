@php
    use App\Helpers\Model;
@endphp
@if ($participant->isEmpty())
    <tr>
        <td colspan="5" class="table-danger text-center"><br>Belum ada Peserta<br><br></td>
    </tr>
@else
    @if ($group->round <= 1 && $group->total_seat == 1)
        @foreach ($participant as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->no_participant }}</td>
                <td>{{ ucwords($t->member) }}</td>
                <td>{{ ucwords($t->team) }}</td>
                <td>{{ $t->time }}</td>
            </tr>
        @endforeach
    @else
        <div class="tab-content" id="myTabContent">
            @for ($i = 0; $i < $group->total_seat; $i++)
                <div @class([
                    'tab-pane fade show active' => $index_seat == $i,
                    'tab-pane fade' => $index_seat != $i,
                ]) id="seat{{ $i }}-tab-pane" role="tabpanel"
                    aria-labelledby="seat{{ $i }}-tab" tabindex="0"
                    onclick="save_storage({{ $i }})">
                    <div class="table-responsive">
                        <table class="table table-striped table-primary">
                            <tr>
                                <th>No.</th>
                                <th>No. BIB</th>
                                <th>Peserta</th>
                                <th>Tim</th>
                                <th>Waktu</th>
                            </tr>
                            @php
                                $participant_perseat = Model::ParticipantPerSeat($group->slug, $i + 1);
                            @endphp
                            @foreach ($participant_perseat as $k => $p)
                                <tr @class(['bg-primary text-light' => $k < $group->passes])>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->no_participant }}</td>
                                    <td>{{ $p->member }}</td>
                                    <td>{{ $p->team }}</td>
                                    <td>{{ $p->time ?? '00:00:000' }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endfor
            <div @class([
                'tab-pane fade show active' => $index_seat == $i + 1,
                'tab-pane fade' => $index_seat != $i + 1,
            ]) id="final-tab-pane" role="tabpanel" aria-labelledby="final-tab"
                tabindex="0">
                <div class="table-responsive">
                    <table class="table table-striped table-primary">
                        <tr>
                            <th>No.</th>
                            <th>No. BIB</th>
                            <th>Peserta</th>
                            <th>Tim</th>
                            <th>Waktu</th>
                        </tr>
                        @php
                            $participant_final = Model::ParticipantPerFinal($group->slug);
                        @endphp
                        @foreach ($participant_final as $k => $p)
                            <tr @class(['bg-primary text-light' => $k < $group->passes])>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->no_participant }}</td>
                                <td>{{ $p->member }}</td>
                                <td>{{ $p->team }}</td>
                                <td>{{ $p->time ?? '00:00:000' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    @endif
@endif

@section('js')
@endsection
