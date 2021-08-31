@if(isset($receivers))
    @foreach($receivers as $key => $receiver)
        <input type="hidden" name="receivers[{{ $key }}][name]" value="{{ $receiver['name'] ?? '' }}">
        <input type="hidden" name="receivers[{{ $key }}][phone]" value="{{ $receiver['phone'] ?? '' }}">
    @endforeach
@endif
