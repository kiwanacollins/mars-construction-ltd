# TopNotchPlan.com — Homepage Layout & Behavior Analysis

Reference for adopting layout/UX patterns (not visual design) into the Mars Construction homepage.
Compiled by inspecting the rendered DOM (headless Chrome) and the site's own React/Vite JS bundles
(`assets/index.DOfLEbJK.js` — app shell, `assets/Index.Cia8AVGN.js` — homepage route chunk).
Site is a client-rendered React SPA (Vite build, Tailwind CSS, "lucide" icon set), served desktop-in-a-phone-frame.

## 1. Top-level page structure (in document order)

1. **Hero** — `<section aria-label="Featured architectural projects">`, `aspect-ratio: 3/4; max-height: 88vh`
   - Stacked, always-mounted background image layers (not swapped in/out), each `absolute inset-0`.
   - Crossfade via plain CSS `transition: opacity 1400ms ease-out` (active layer `opacity:1`, rest `opacity:0`) — not a Swiper/carousel library.
   - Only the **active** layer gets an additional `@keyframes` slow zoom: `scale(1) → scale(1.12)` over `18s ease-out forwards`. Zoom restarts each time a layer becomes active; fade and zoom are two independent animations, not one combined keyframe.
   - Floating header nav sits on top of the hero, not a separate bar above it: two pill buttons ("Request Custom Design", "Architecture Awards"), translucent/blurred background, logo top-left.
   - No hero text content was found overlaid in the captured markup beyond the image + top nav — copy/CTA appears to live in the "Choose your path" block directly below, not inside the hero itself.

2. **"Choose your path"** — `<section aria-label="Choose your path">`, sits directly under the hero.
   - One large, prominent tile: **animated gradient border** (conic/linear gradient sweeping via `background-position` keyframe `animate-gradient-pan`, 8s loop) wrapping a dark rounded card. Contents: icon badge (shopping-bag), eyebrow label ("READY TO BUILD"), title ("Buy Ready House Plans"), subtitle ("Curated, architect-approved designs available now"), circular arrow-right affordance on the right. This is the exact card the user is trying to replicate — full markup captured in §4.
   - Below it, a **2-column grid** of two smaller secondary tiles: "Explore Designs" (compass icon, "Signature showcases") and "Custom Design" (sparkles icon, "Tailored to your land"). Same card anatomy, no gradient border, plain translucent tile.
   - Reads as a **3-tile "quick intents" menu**: buy a ready plan / browse the portfolio / commission a custom design. This is the top-of-funnel decision point for the whole page.

3. **Repeating horizontal-scroll carousel rows** (curated/themed shelves — NOT the infinite-scroll feed):
   - Section titles found, in order: "Trending This Week" (subtitle "Most viewed lately"), "Most Viewed", "Luxury Villas", "Tropical Mansions", "Hillside Homes", "African Luxury", "Dubai-Inspired", "Discover".
   - Each row: `<h2>` title + optional subtitle on the left, a chevron-right affordance on the right (implies "see all" for that shelf, though no visible link/href was captured — likely a client-side filter jump).
   - Row body: `overflow-x-auto scrollbar-hide snap-x snap-mandatory scroll-px-4`, i.e. native horizontal scroll with CSS scroll-snap, no JS carousel library. Cards are `shrink-0` at responsive widths (`w-[58%]` mobile → `w-[20%]` desktop, i.e. ~1.7 cards visible on phones, 5 on desktop).
   - This is a fixed, curated set of shelves — each is its own themed slice of the catalog (by recency/popularity/category), not paginated.

4. **"More Designs to Explore"** — the actual infinite-scroll section, appears after all curated shelves.
   - `<div id="feed-sale-plans" class="scroll-mt-24">` containing one placeholder `<div data-feed-anchor="feed-plan-<uuid>" style="min-height: 520px">` (or `620px` for some — likely plans with an extra badge/video that adds height) **per plan already known to exist**, before that plan's real card content has mounted. See §5 for why.
   - A mid-feed cross-sell card is woven in after ~25 anchors: "Inspired by these designs? → Start a brief" (gradient-tinted rounded card, sparkles icon, primary-color button) — nudges undecided browsers toward the custom-design lead form without leaving the feed.
   - Loading more triggers an `IntersectionObserver` (see §3) watching a sentinel element near the bottom of the feed; `rootMargin: "400px"` means the next page starts fetching ~400px (roughly one screen) before the user actually reaches the bottom, so it feels seamless.
   - End state: when `hasMore` is false, a plain caption is shown: `"All {totalCount} plans loaded"`.

## 2. The "Buy Ready House Plans" card — exact anatomy

This is the card the user is trying to replicate, captured verbatim from the rendered DOM:

- Outer: `button`/`a`, `rounded-2xl`, `p-[1.5px]` (the 1.5px padding is the gradient border's visible thickness).
- A `::before`-like absolutely-positioned layer paints the border: `linear-gradient(120deg, primary 0%, amber 30%, pink 60%, primary 100%)`, `background-size: 220% 220%`, animated via `background-position` (`animate-gradient-pan`, 8s ease-in-out infinite) — this is what makes the border appear to "flow."
- Inner content sits on a solid near-black layer (`bg-zinc-950/90`, `backdrop-blur-xl`) inset by the gradient's 1.5px, rounded slightly less (`rounded-[14px]`) than the outer wrapper so the border reads as a continuous ring.
- A soft radial-gradient glow blob (`blur-3xl`, orange, `animate-soft-pulse`) is absolutely positioned top-right, purely decorative, low opacity.
- Layout inside: icon badge (12×12 rounded-xl tile, amber icon on translucent white) — text block (eyebrow / title / subtitle, each a separate line, `line-clamp-1` on the subtitle so it never wraps) — circular arrow button on the far right that nudges right on hover (`group-hover:translate-x-0.5`).
- This card is real HTML/CSS (Tailwind utility classes + two custom `@keyframes`), not an image or third-party embed.

**What's genuinely reusable as a *pattern* (not a copy):** the row layout (icon · text block · arrow affordance), the "eyebrow / title / subtitle" text hierarchy, and the "one big primary tile + a 2-column grid of secondary tiles" composition for a homepage intent-picker. The neon gradient border, dark glassmorphism, and glow blobs are this site's specific visual skin — the earlier work in this project already reproduced the *structure* of this card in the Mars Construction navy/teal palette (see `plans-cta-one` classes added to `index.php` / `brand-overrides.css`) instead of copying the neon look.

## 3. Infinite scroll — exact mechanism

Found in `Index.Cia8AVGN.js`, inside the plan-feed hook:

```
plans, loading, loadingMore, hasMore, loadMore, isFromCache, cacheAge, isOnline
```

- A `ref` (`L`) is attached to a sentinel element placed at/near the bottom of the feed.
- `useEffect` creates `new IntersectionObserver(cb, { rootMargin: "400px" })` and observes the sentinel.
- Callback: `if (entry.isIntersecting && hasMore && !loadingMore) loadMore()`.
- Guards against double-fetching: bails out early if `loadingMore` or `loading` is already true.
- `rootMargin: "400px"` is the key UX detail — it starts loading the next batch **400px before** the sentinel scrolls into view, so by the time the user actually reaches the bottom, the next batch is usually already there. This is what makes it feel instantaneous rather than showing a visible loading pause.
- Client-side filtering (`bedrooms`, `storeys`, `styles`, `plotSizes` by `size_sqm`) is applied via `useMemo` **on top of** the already-fetched `plans` array — filters don't necessarily re-fetch from the server, they slice the loaded set (exact fetch-vs-filter boundary wasn't fully confirmed from static analysis, but the filter hook takes the full `plans` array as input, not a query param).
- Caching layer: `isFromCache` / `cacheAge` / `isOnline` (from `navigator.onLine`) suggest a stale-while-revalidate pattern — likely serves a cached page instantly, then silently refreshes, and would degrade gracefully offline. A per-plan "seen" counter is persisted to `sessionStorage` (keyed by a hash of the plan+something), incrementing only when the live count exceeds the stored one — this looks like it backs the "views" badge (§4) rather than being a real server-tracked analytics counter.

### The "virtualized anchor" trick (placeholder-first rendering)

Before a plan's real card mounts, the feed renders a lightweight placeholder:

```html
<div data-feed-anchor="feed-plan-<uuid>" style="min-height: 520px"></div>
```

- Every plan the client currently knows about (from the initial fetch/cache) gets an anchor **immediately**, in the correct final order, each pre-sized to its expected rendered height (`520px` normal, `620px` for plans with extra content — likely a video badge or a longer title wrapping to 2 lines).
- The actual card content (image, title, beds/baths/sqft, badges) is mounted into each anchor lazily/progressively — this reserves the correct scroll height up front so there's **no layout shift** as content streams in, and it lets the browser's native scrollbar reflect the true final page length immediately rather than growing unpredictably as more pages load.
- This is a more advanced technique than a typical "append cards + spinner" infinite scroll — worth calling out explicitly if replicating scroll *feel*, since it's what makes their scrollbar behave correctly and avoids jank, independent of the IntersectionObserver pagination logic itself.

## 4. Plan card — data fields and compact-card anatomy

Card component signature (from JS, function `Xa`):

```
{ title, beds, baths, sqft, views, builtUpArea, planId }
```

Rendered meta line: `{beds} Beds | {baths} Baths | {sqft formatted} | {builtUpArea (if present, shown in primary/brand color)}`.

A "views" badge (eye icon, outlined pill, `12px` text) is shown **only when views ≥ 100** — small counts are hidden rather than shown as "3 views", which would look thin. Below the meta line, the plan title is shown truncated to one line (`truncate`).

**Compact carousel-card variant** (used in the horizontal shelf rows), captured in full:

- `aspect-ratio: 3/4` card, `rounded-2xl`, full-bleed cover image (`object-cover`, `group-hover:scale-105` on hover — subtle zoom, not the hero's slow Ken Burns).
- A bottom-anchored gradient scrim over the image: `bg-gradient-to-t from-black/85 via-black/20 to-transparent` — this is what keeps the overlaid white text legible regardless of image content, without needing a separate white card body below the image.
- Text sits directly on the image, bottom-left, inside the scrim: title (`text-sm font-semibold text-white line-clamp-1`) then a meta row directly under it: `{beds} Bed · {baths} Bath · {sqft} sqft` in smaller, dimmer white (`text-[10px] text-white/70`), separated by a middle-dot `·` rather than pipes.
- No separate "card body" panel — the entire card *is* the image, with text overlaid. This is the main structural difference from Mars Construction's current property cards, which use a white card body below the image for the title/meta row.

**Likely fuller feed-card variant** (used in "More Designs to Explore", not fully captured due to lazy-mount timing — see limitations below): given the taller placeholder heights (520–620px vs. the carousel card's aspect-ratio-driven height) and that this is the primary buy-intent listing, it's likely a taller card with more visible metadata (matches the `views` badge / `builtUpArea` fields found in the shared card component `Xa`, which the compact carousel card does NOT display — those two fields must belong to this fuller variant).

## 5. Header / navigation

- No traditional multi-item horizontal nav menu was found on the homepage. Instead: logo top-left, and two floating pill buttons over the hero image ("Request Custom Design" with a subtle warm glow/shadow to draw the eye, "Architecture Awards" plain). Very sparse compared to Mars Construction's current mega-nav (House Plans / Villas / Apartments / Residential / Hotels / Country Homes / Modern Villas / Get a Quote).
- The category/intent navigation work is effectively done by the "Choose your path" 3-tile block instead of a nav bar — categories/filters live inside the page content, not the header chrome.

## 6. What did NOT get fully confirmed (limitations of this analysis)

- The full-width "More Designs to Explore" feed card markup could not be captured verbatim — anchors stayed empty placeholders through repeated scroll simulations in headless Chrome (likely gated behind real intersection/visibility timing or a fetch that headless automation didn't trigger correctly). Its data fields are known (from the shared `Xa` component signature) but its exact visual layout (image size/position, where `views`/`builtUpArea` render) is inferred, not screenshot-verified.
- Whether category-filter chips/tabs exist elsewhere on the page (e.g. a sticky filter bar) wasn't confirmed — only the underlying filter *state* (`bedrooms`, `storeys`, `styles`, `plotSizes`) was found in code.
- The exact fetch/pagination request shape (page size, endpoint, cursor format) wasn't captured — only the client-side hook's public interface (`hasMore`, `loadMore`, cursor-based per the variable name `cursor` found near this logic).
- Mobile vs. desktop layout differences beyond the responsive Tailwind width classes already noted (e.g. `w-[58%] xs:w-[46%] sm:w-[34%] md:w-[26%] lg:w-[20%]`) were not separately screenshot-verified at each breakpoint.

## 7. Ethical/product note worth flagging to the client

The `likes` / `shares` / `comments` numbers referenced near the card-engagement code are **not real counters** — they're deterministically generated from a hash of the plan's id/title (`Za`/`Ja` functions: hash the string, derive pseudo-random-but-stable percentages of a base "views" number for likes/shares/comments, cache in a `Map` so the same plan always shows the same fake numbers). If the client wants "social proof" numbers on plan cards, worth deciding explicitly whether Mars Construction wants real tracked engagement or is comfortable with fabricated-but-consistent numbers — this is a legitimate design pattern some sites use, but it's not something to carry over silently without the client knowing what it is.
