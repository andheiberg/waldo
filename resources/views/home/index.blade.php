@extends('layouts.main')

@section('title', 'Home')

@section('main')
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                @foreach($branches as $b)
                    <a class="list-group-item {{ Request::is("branches/{$b->id}") ? 'active' : '' }}" href="/branches/{{ $b->id }}">{{ $b->name }}</a>
                @endforeach
            </div>
        </div>

        <div class="col-md-9">
            @if (! isset($branch))
                <div class="well">
                    <h1>No branch selected, please select a branch.</h1>
                </div>
            @else
                <div class="list-group">
                    @foreach($screenshots as $s)
                        <a class="list-group-item" href="/branches/{{ $s->branch_id }}/screenshots/{{ $s->id }}">
                            @if ($s->getStatus() == 'Pass')
                                <span class="label label-success">Passed</span>
                            @elseif ($s->getStatus() == 'Fail')
                                <span class="label label-danger">Failed</span>
                            @else
                                <span class="label label-default">Not Run</span>
                            @endif
                             / {{ $s->suite }} / {{ $s->feature }} / {{ $s->scenario }} / {{ $s->step }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
