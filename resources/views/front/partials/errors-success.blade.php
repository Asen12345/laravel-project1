@if ($errors->any())
    <div class="mfp-bg"></div>
    <div class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-ready" tabindex="-1" style="overflow-x: hidden; overflow-y: auto;">
        <div class="mfp-container mfp-s-ready mfp-inline-holder">
            <div class="mfp-content popup-email">
                <div id="li-popup-email" class="li-popup-email-2">
                    <header class="li-popup__header">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                            <button title="Close (Esc)" type="button" class="mfp-close" onclick="$('.mfp-ready, .mfp-bg').css('display', 'none')">×</button>
                    </header>
                </div>
            </div>
            <div class="mfp-preloader">Loading...</div>
        </div>
    </div>
@endif
@if (session()->has('success'))
    <div class="mfp-bg"></div>
    <div class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-ready" tabindex="-1" style="overflow-x: hidden; overflow-y: auto;">
        <div class="mfp-container mfp-s-ready mfp-inline-holder">
            <div class="mfp-content popup-email"><div id="li-popup-email" class="li-popup li-popup-email-2">
                    <header class="li-popup__header">
                        {{session()->get('success')}}
                        <button title="Close (Esc)" type="button" class="mfp-close" onclick="$('.mfp-ready, .mfp-bg').css('display', 'none')">×</button>
                    </header>
                </div>
            </div>
            <div class="mfp-preloader">Loading...</div>
        </div>
    </div>
@endif