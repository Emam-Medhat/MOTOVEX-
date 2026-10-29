/**
 * Lucide icon registry.
 *
 * Only the icons actually referenced by `data-lucide="..."` across the site are
 * bundled — importing the full `icons` object pulls in ~2,100 definitions.
 * When a view starts using a new icon, add its PascalCase name here.
 */
import {
    ArrowUpRight,
    ArrowRight,
    ArrowLeft,
    ChevronUp,
    ChevronDown,
    ChevronRight,
    MapPin,
    Phone,
    Mail,
    Clock,
    Navigation,
    X,
    CheckCircle2,
    Bike,
    Wallet,
    Wrench,
    Package,
    ShieldCheck,
    Headset,
    // Social block — lucide v1 dropped brand marks, these are the closest
    // generic glyphs (see partials/apex/footer.blade.php).
    Camera,
    AtSign,
    Play,
    Briefcase,
    Info,
    Settings,
    Shield,
    Layout,
    createIcons,
} from 'lucide';

const icons = {
    ArrowUpRight,
    ArrowRight,
    ArrowLeft,
    ChevronUp,
    ChevronDown,
    ChevronRight,
    MapPin,
    Phone,
    Mail,
    Clock,
    Navigation,
    X,
    CheckCircle2,
    Bike,
    Wallet,
    Wrench,
    Package,
    ShieldCheck,
    Headset,
    Camera,
    AtSign,
    Play,
    Briefcase,
    Info,
    Settings,
    Shield,
    Layout,
};

/**
 * Swap every `[data-lucide]` placeholder in `root` for its inline SVG.
 * Safe to call again after injecting dynamic markup.
 */
export function refreshIcons(root = document) {
    createIcons({ icons, root });
}
