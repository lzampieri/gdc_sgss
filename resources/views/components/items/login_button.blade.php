<a href="{{ route('auth.redirect') }}" class="button-large">
    @if ( App\Http\Controllers\Settings::obtain( 'signup_enabled' ) == 1 )
        Registrati | Accedi
    @else
        Accedi
    @endif
</a>