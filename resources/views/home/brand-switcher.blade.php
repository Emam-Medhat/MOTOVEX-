<section id="brands" class="relative z-10 py-20 lg:py-28">
    <div class="mx-auto max-w-[1600px] px-5 lg:px-10">
        <x-section-heading
            eyebrow="The Collection"
            title="Four brands. Four disciplines."
            description="Each marque owns a single segment and a single character. Hover a panel to re-theme the page to its accent." />

        <div class="mt-12 flex flex-col gap-3 sm:flex-row sm:items-stretch lg:gap-4"
             data-reveal-stagger
             x-data="{ open: null }"
             x-on:switch-brand.window="window.apexTheme($event.detail.accent)">
            @foreach($brands as $brand)
                <x-brand-tab :brand="$brand" />
            @endforeach
        </div>
    </div>
</section>
