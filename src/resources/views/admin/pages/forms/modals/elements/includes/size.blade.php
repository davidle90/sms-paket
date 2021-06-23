@php
$size = $element->pivot->size ?? [];
@endphp

<!-- Column size, collapsable -->
<div class="mt-2">
    <h6
            class="bold pointer collapse-button"
            data-toggle="collapse"
            data-target="#section_{{ $section_index }}_element_{{ $element_index }}_collapseColumns"
            aria-expanded="true"
            aria-controls="collapseColumns"
            style="display: inline"
    >
        Kolumn storlekar<i style="font-size: 13px;" class="icon-arrow-down collapse-icon ml-2"></i>
    </h6>
</div>

<div
    class="row collapse"
    id="section_{{ $section_index }}_element_{{ $element_index }}_collapseColumns"
    data-section-index="{{ $section_index }}"
    data-element-index="{{ $element_index }}"
>
    <!-- Col/Col-xs, Mobile portrait -->
    <div class="col-12">
        <div class="form-group">
            <small>Mobil porträtt</small>
            <select
                    id="section_{{ $section_index }}_element_{{ $element_index }}_size_xs"
                    class="select-size form-control size-xs"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][size][xs]"
                    style="width:100%;"
            >
                <option value="">Ej vald</option>
                @for($i = 1; $i <= 12; $i++)
                    @if(isset($size['xs']) && $size['xs'] == $i)
                        <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                @endfor
            </select>
        </div>
    </div>
    <!-- Col-sm, Mobile landscape -->
    <div class="col-12">
        <div class="form-group">
            <small>Mobil landskap</small>
            <select
                    id="section_{{ $section_index }}_element_{{ $element_index }}_size_sm"
                    class="select-size form-control size-sm"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][size][sm]"
                    style="width:100%;"
            >
                <option value="">Ej vald</option>
                @for($i = 1; $i <= 12; $i++)
                    @if(isset($size['sm']) && $size['sm'] == $i)
                        <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                @endfor
            </select>
        </div>
    </div>
    <!-- Col-md, Tablet -->
    <div class="col-12">
        <div class="form-group">
            <small>Surfplatta</small>
            <select
                    id="section_{{ $section_index }}_element_{{ $element_index }}_size_md"
                    class="select-size form-control size-md"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][size][md]"
                    style="width:100%;"
            >
                <option value="">Ej vald</option>
                @for($i = 1; $i <= 12; $i++)
                    @if(isset($size['md']) && $size['md'] == $i)
                        <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                @endfor
            </select>
        </div>
    </div>
    <!-- Col-lg, Laptop -->
    <div class="col-12">
        <div class="form-group">
            <small>Laptop</small>
            <select
                    id="section_{{ $section_index }}_element_{{ $element_index }}_size_lg"
                    class="select-size form-control size-lg"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][size][lg]"
                    style="width:100%;"
            >
                <option value="">Ej vald</option>
                @for($i = 1; $i <= 12; $i++)
                    @if(isset($size['lg']) && $size['lg'] == $i)
                        <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                @endfor
            </select>
        </div>
    </div>
    <!-- Col-xl, Desktop -->
    <div class="col-12">
        <div class="form-group">
            <small>Stationär dator</small>
            <select
                    id="section_{{ $section_index }}_element_{{ $element_index }}_size_xl"
                    class="select-size form-control size-xl"
                    name="sections[{{ $section_index }}][elements][{{ $element_index }}][size][xl]"
                    style="width:100%;"
            >
                <option value="">Ej vald</option>
                @for($i = 1; $i <= 12; $i++)
                    @if(isset($size['xl']) && $size['xl'] == $i)
                        <option value="{{ $i }}" selected>{{ $i }}</option>
                    @else
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endif
                @endfor
            </select>
        </div>
    </div>
</div>