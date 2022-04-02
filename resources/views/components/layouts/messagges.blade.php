@if ( session( 'positive-message' ) != null )
    <div class="happy-news">
        {!! session( 'positive-message' ) !!}
    </div>
@endif

@if ( session( 'negative-message' ) != null )
    <div class="bad-news">
        {!! session( 'negative-message' ) !!}
    </div>
@endif