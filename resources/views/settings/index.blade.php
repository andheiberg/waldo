@extends('layouts.main')

@section('title', 'Home')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <h1>Settings</h1>

            <h2>Comparison target</h2>
            <p>When generating diffs we need something to compare to. Most commonly you will want this to be your production environment.</p>

            +@form(['route' => 'settings.update', 'method' => 'PUT'])
                +@formSelect('branch', 'Branch', $branches)

                +@formText('env')

                +@formSubmit('Save')
            -@form

            <h2>Clean up screenshot storage</h2>
            <p>Screenshots can take up a lot of space. If you're running out feel free to delete them. This will also delete data associated with when the tests were run.</p>

            +@deleteButton('Delete All', route('settings.delete.all'))

            +@deleteButton('Delete Old (7+ days)', route('settings.delete.old'))
        </div>
    </div>
@endsection
