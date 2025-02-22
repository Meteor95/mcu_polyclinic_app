<div class="row">
    @if($visible_sebelumnya)
        <div class="col-md-6 mt-2">
            <a href="{{ $url_sebelumnya }}" class="btn btn-amc-orange">
                <i class="fa fa-arrow-left"></i> {{ $text_sebelumnya }}
            </a>
        </div>
    @endif

    @if($visible_selanjutnya)
        <div class="{{ $visible_sebelumnya ? 'col-md-6 text-end' : 'col-md-12 text-end' }} mt-2">
            <a href="{{ $url_selanjutnya }}" class="btn btn-amc-orange">
                {{ $text_selanjutnya }} <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    @endif
</div>
