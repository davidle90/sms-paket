@extends('rl_webadmin::layouts.new_master')

@section('styles')
    <style>

    </style>
@endsection

@section('breadcrumbs')
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">Avsändare</li>
    </ol>
@endsection

@section('modals')
{{--    @include('rl_sms::admin.pages.sms.modals.send')--}}
@endsection

@section('sidebar')
    @can('sms_create')
        <a class="btn btn-block btn-outline-success go-to-url" data-url="{{ route('rl_sms.admin.senders.create') }}"><i class="essential-xs essential-add mr-1"></i>Skapa avsändare</a>
    @endcan
@endsection

@section('content')
    <table class="table table-striped table-white table-outline table-hover mb-0 border-secondary">
        <thead>
        <tr>
            <th class="w-25">Namn</th>
            <th>Label</th>
            <th>Slug</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($senders) && !empty($senders))
            @foreach($senders as $sender)
                <tr class="pointer go-to-url" data-url="{{ route('rl_sms.admin.senders.edit', ['id' => $sender->id]) }}">
                    <td>{{ $sender->name ?? '' }}</td>
                    <td>{{ $sender->sms_label ?? '' }}</td>
                    <td>{{ $sender->slug ?? '' }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@stop

@section('scripts')
    <script type="text/javascript">

        $(document).ready(function(){

            $('.go-to-url').on('click', function(e){
                if(!$(e.target).hasClass('dropdown-toggle') && !$(e.target).hasClass('do-delete-row')){
                    goToURL = $(this).attr('data-url');
                    window.location = goToURL;
                }
            });

        });

    </script>

    @include('rl_sms::admin.pages.senders.scripts.flash')
@stop

