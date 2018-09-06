<div class="form-group-wrapper">
    <div class="form-group">

        <label for="name" class="control-label">{{$element->label}}
            @if($element->attr_required == 1)
                <i class="fa fa-asterisk required-marker" aria-hidden="true"></i>
            @endif
        </label>

        @if(!empty($element->help_text))
            <span class="help-text">{{$element->help_text}}</span>
        @endif


        <?php
        $test = [
                'columns' => [
                        'Very bad',
                        'Fair',
                        'Very good',
                ],
                'rows' => [
                        'Service',
                        'Speed',
                        'Knowledge',
                ]
        ];
        ?>

        <table class="table">
            <tbody>
            <tr>
                <td></td>
                @foreach($test['columns'] as $c)
                    <td class="" align="center">{{$c}}</td>
                @endforeach
            </tr>
            @php
            $i=0;
            @endphp
            @foreach($test['rows'] as $r)
                <tr>
                    <td>{{ $r }}</td>
                    @foreach($test['columns'] as $c)
                        <td class="" align="center"><input type="radio" name="testra[{{$i}}]" value="" /></td>
                    @endforeach
                </tr>
                @php
                $i++;
                @endphp
            @endforeach
            </tbody>
        </table>

        <p class="help-block"></p>

    </div>
</div>