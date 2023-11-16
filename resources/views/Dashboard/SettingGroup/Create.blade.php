@extends('Dashboard.Layout.Main')
@section('css')
@endsection
@section('js')
@endsection

@section('content')
<x-alert />
@include('Dashboard.SettingGroup.Create.Part' . $step)

@endsection