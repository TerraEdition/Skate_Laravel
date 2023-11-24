@extends('Dashboard.PDF.Layout.Main')
@section('content')
<div class="text-center fw-bold">HASIL LOMBA TURNAMEN :</div>
<div class="text-center fw-bold">{{strtoupper($tournament->tournament)}}</div>


<div class="d-flex justify-content-center">
    <table class="table mt-4" cellpadding="3">
        <tr>
            <th colspan="2">RACE {{strtoupper($tournament->tournament)}}</th>
            <th>1</th>
            <th colspan="2">{{strtoupper($group->group)}}</th>
        </tr>
        <tr>
            <th class="border border-2 text-center">NO</th>
            <th class="border border-2 text-center">NO BIB</th>
            <th class="border border-2 text-center">NAMA ATLET</th>
            <th class="border border-2 text-center">TIM</th>
            <th class="border border-2 text-center">WAKTU</th>
        </tr>
        @foreach ($participant as $k => $member)
        <tr>
            <td class="border border-2 text-center">{{$loop->iteration}}</td>
            <td class="border border-2 text-center">{{$member->no_participant}}</td>
            <td class="border border-2 text-left">{{strtoupper($member->member)}}</td>
            <td class="border border-2 text-left">{{strtoupper($member->team)}}</td>
            <td class="border border-2 text-center">{{$member->time}}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection