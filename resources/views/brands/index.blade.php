<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brands | MOTOVEX — Premium Motorcycle Group</title>
    <meta name="description" content="Discover premium motorcycle brands at MOTOVEX.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: Inter, sans-serif; background: #f8f9fa; color: #0f172a; margin: 0; }
        .light-card { background: #ffffff; border: 1px solid #e2e8f0; transition: all 0.3s; }
        .light-card:hover { border-color: #c084fc; transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(124,58,237,0.08); }
        .brand-logo-wrap { background: radial-gradient(circle at 50% 50%, rgba(124,58,237,0.08), transparent 70%); }
    </style>
</head>
<body>
    <section style="background: linear-gradient(to bottom, #f1f5f9, #ffffff, #f8f9fa); padding: 2.5rem 0; border-bottom: 1px solid #e2e8f0;">
        <div style="max-width: 80rem; margin: 0 auto; padding: 0 1rem;">
            <nav style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">
                <a href="/" style="color: #64748b; text-decoration: none;">Home</a>
                <span>›</span>
                <span style="color: #0f172a; font-weight: 600;">Brands</span>
            </nav>
            <h1 style="font-size: 2rem; font-weight: 900; color: #0f172a;">Premium Brands</h1>
            <p style="color: #475569; margin-top: 0.25rem;">Curated fictional marques in the MOTOVEX group.</p>
        </div>
    </section>
    <main style="max-width: 80rem; margin: 0 auto; padding: 2rem 1rem;">
        <div style="display: grid; gap: 1.25rem; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            @foreach($brands as $brand)
            <a href="{{ route('brands.show', $brand->slug) }}" style="text-decoration: none; color: inherit;">
                <div class="light-card" style="border-radius: 1rem; overflow: hidden;">
                    <div class="brand-logo-wrap" style="height: 10rem; display: flex; align-items: center; justify-content: center;">
                        @if($brand->logo_url)
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" style="max-height: 100%; max-width: 80%; object-fit: contain;">
                        @else
                            <div style="text-align: center;">
                                <div style="font-size: 2.5rem; font-weight: 900;">{{ strtoupper(substr($brand->name,0,1)) }}</div>
                                <div style="letter-spacing: 0.2em; text-transform: uppercase; font-size: 0.875rem;">{{ $brand->name }}</div>
                            </div>
                        @endif
                    </div>
                    <div style="padding: 1.25rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <h2 style="font-size: 1.125rem; font-weight: 700; margin: 0;">{{ $brand->name }}</h2>
                                @if($brand->country)<p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $brand->country }}</p>@endif
                            </div>
                            <span style="font-size: 0.75rem; background: #f5f3ff; color: #6d28d9; padding: 0.25rem 0.625rem; border-radius: 9999px;">{{ $brand->motorcycles_count }} Models</span>
                        </div>
                        @if($brand->description)<p style="margin-top: 0.75rem; font-size: 0.875rem; color: #475569;">{{ $brand->description }}</p>@endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </main>
</body>
</html>
