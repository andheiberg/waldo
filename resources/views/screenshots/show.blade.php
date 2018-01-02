@extends('layouts.main')

@section('title', 'Home')

@section('main')
    <div class="row">
        <a class="btn btn-primary" href="/branches/{{ $branch->id }}">< Back</a>

        <h1>
        @if ($screenshot->getStatus() == 'Pass')
            <span class="label label-success">Passed</span>
        @elseif ($screenshot->getStatus() == 'Fail')
            <span class="label label-danger">Failed</span>
        @else
            <span class="label label-default">Not Run</span>
        @endif
            {{ $screenshot->suite }}, {{ $screenshot->feature }}, {{ $screenshot->scenario }}, {{ $screenshot->step }}
        </h1>
    </div>

    <br>

    <div class="row">
        <div class="col-md-4">
            <img src="{{ $screenshot->getPublicUrl() }}" alt="">
        </div>

        <div class="col-md-4">
            <img src="{{ $screenshot->getBaseLinePublicUrl() }}" alt="">
        </div>

        <div class="col-md-4">
            <img src="{{ $screenshot->getDiffPublicUrl() }}" alt="">
        </div>
    </div>
@endsection
