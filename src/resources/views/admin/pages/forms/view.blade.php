@extends('rl_webadmin::layouts.new_master')

@section('styles')
@endsection

@section('breadcrumbs')
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('rl_forms.admin.forms.index') }}">Formulär</a></li>
        <li class="breadcrumb-item active">{{ $form->in($default_language ?? $fallback_language)->label ?? '' }}</li>
    </ol>
@endsection

@section('sidebar')
    <a class="btn btn-block btn-outline-primary" href="{{ route('rl_forms.admin.forms.index') }}"><i class="fal fa-angle-left mr-2"></i> Formulär</a>
    <a class="btn btn-block btn-outline-primary" href="{{ route('rl_forms.admin.forms.edit', ['id' => $form->id]) }}">Redigera formulär</a>
@endsection

@section('content')

    <div class="card">

        <div class="card-header">
            <b>{{ $form->in($default_language ?? $fallback_language)->label ?? '' }}</b>
        </div>

        <div class="card-body">

            <h5 class="bold">Formulär sektioner</h5>

            {{--@if(isset($form) && !empty($form))
                @foreach($form->fields as $field)
                    <div class="row">
                        <div class="col-12">
                            <div class="card">

                                <div class="card-header">
                                    <h6 class="bold m-0">{!! $field->label ?? '<i>Namn ej angivet</i>' !!}</h6>
                                </div>

                                <table class="table table-responsive-sm table-striped table-white table-outline table-hover mb-0 border-secondary border-0">
                                    <thead class="thead-white"
                                    <tr>
                                        <th style="width: 12.5%">Tabell</th>
                                        <th style="width: 12.5%">Input typ</th>
                                        <th style="width: 12.5%" class="text-center">Ordning</th>
                                        <th style="width: 12.5%" class="text-center">col</th>
                                        <th style="width: 12.5%" class="text-center">col-sm</th>
                                        <th style="width: 12.5%" class="text-center">col-md</th>
                                        <th style="width: 12.5%" class="text-center">col-lg</th>
                                        <th style="width: 12.5%" class="text-center">col-xl</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($field->tables as $key => $t)
                                        <tr>
                                            <td>{{ $t->label ?? '' }}</td>
                                            <td>{{ $t->pivot->input_type ?? '' }}</td>
                                            <td class="text-center">{{ $t->pivot->sort_order ?? '' }}</td>
                                            <td class="text-center">{{ $t->pivot->col ?? '' }}</td>
                                            <td class="text-center">{!! $t->pivot->col_sm ?? '<span class="text-danger">Ej angivet</span>' !!}</td>
                                            <td class="text-center">{!! $t->pivot->col_md ?? '<span class="text-danger">Ej angivet</span>' !!}</td>
                                            <td class="text-center">{!! $t->pivot->col_lg ?? '<span class="text-danger">Ej angivet</span>' !!}</td>
                                            <td class="text-center">{!! $t->pivot->col_xl ?? '<span class="text-danger">Ej angivet</span>' !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                @endforeach
            @endif--}}

        </div>

    </div>

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

@stop

