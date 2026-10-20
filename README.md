# Baan Plearn Talee theme, version 3.0.0

A WordPress theme for Baan Plearn Talee, Kamala, Phuket. It needs WordPress 6.0 or newer and PHP 7.4 or newer.

## Install

1. Appearance > Themes > Add New > Upload Theme, choose `baan-plearn-talee.zip`, then Activate.
   The zip is about 8 MB. If you see a Cloudflare error 524 (timeout) while uploading, the upload was too slow for Cloudflare's 100 second limit: use a faster connection, pause Cloudflare for the upload, or put the unzipped folder into `wp-content/themes/` with FTP or your host's File Manager, which skips the upload form altogether.
2. Go to **Appearance > Villa setup** and press **Create or update the pages**. It runs by itself on activation too. It creates the home page, the rooms, services, floor plan, location, gallery, reviews and the booking summary page, assigns their templates, and sets the home page. Text you have already written is kept.
3. Add the home hero film. It is a link, not part of the theme, so the theme uploads quickly. Go to **Appearance > Customize > Villa Details > Home hero video link (MP4)** and paste the direct link to the film (a web address that ends in `.mp4`, from your own hosting or a video CDN; a Google Drive or YouTube page link will not play). The film is already set to `baanplearntalee.com/wp-content/uploads/2026/10/plearnwebsite.mp4`; change it there if you move the file. If the link is empty or does not load, the home hero shows a still photograph.
4. Under **Appearance > Customize > Villa Details**, check the address, phone, reservations email and the Google reviews link. Booking requests are emailed to the reservations email.

## If a page looks empty

The theme repairs itself. The pages are created automatically the first time the theme runs, a page that has no template or an older one is shown with the right template for its address, and opening the address of a villa page that does not exist yet creates it and takes you there. If something still looks wrong, open **Appearance > Villa setup** and press the button, and check **Settings > Permalinks** is not set to "Plain".

## What is on each page

| Page | Template | Notes |
|---|---|---|
| Home | front-page.php | Film hero (full screen), booking bar, intro with tabs, spaces (magazine), bedrooms (bento, on the pale green of the booking bar), services, reviews (a strip that moves by itself), location, enquiry band on a photo |
| Pool Area, Living Area, Kitchen & Dining, Media & Game Room, Fitness Room, Outdoor Sala, Master Bedroom, Guest Bedroom 1 to 5 | Room | Photos left, details right |
| Services & Features | Services and features | Text lives in `inc/pages-data.json` |
| Floor Plan | Floor plan | Set a **Featured Image** on the page to show your drawing |
| Location | Location | Distances live in `inc/pages-data.json` |
| Gallery | Gallery | Photos are grouped into tabs |
| Reviews | Reviews | Text lives in `inc/pages-data.json` |
| Booking Summary | Booking summary | Reached from the booking bar and the booking window. It is not in the menu |
| Terms and Conditions | Terms and conditions | The booking agreement, with a list of its 30 clauses that follows the reader. The text is in `inc/terms-data.json`. The footer link goes here |
| Home (Guesty test) | Home (Guesty booking test) | A copy of the home page with the live Guesty booking on, to compare with the home page |
| Home (Guesty test) | Home (Guesty booking test) | A copy of the home page at `/home-guesty/` with the live Guesty booking, to compare with the home page. Hidden from search engines, not in the menu |
| Contact us | Contact us (details first) | The "Contact" link in the menu points here. Three contact cards, then your Zoho Forms enquiry form |

### Editing a room
Open the room page in the editor. The bulleted list in the page content is the features list. The headline is the custom field `_bpt_intro`, and the bed line under a bedroom name is the "Room summary" box in the sidebar. Photos attached to the page (Media Library, upload them from the page) replace the bundled photographs.

## Comparing the two bookings (Home and Home (Guesty test))

* **Home** (`/`) has the theme's own booking: the booking window and bar open a calendar, and **Continue** goes to the booking summary, where the request is emailed to reservations. The rate is "confirmed by our reservations team" until a rates source is connected.
* **Home (Guesty test)** (`/home-guesty/`) is the same page with the booking of your **Guesty Booking Engine plugin** (the old version). Its search bar is the plugin's (`[guesty_calendar_picker]`), styled to match the site. The search goes to the plugin's checkout page at `/checkout/`, which quotes the price and makes the reservation. The Check availability buttons on this page take the visitor to the search bar. The page is hidden from search engines and is not in the menu. Visitors who are not logged in see only a small "Guesty booking test" tag.

What it needs, the same as your other villa sites:

1. The **Guesty Booking Engine plugin** installed and active (the zip you have).
2. `define( 'GBP_INTERNAL_SECRET', '...' );` in `wp-config.php`.
3. The **Listing ID** of Baan Plearn Talee in **Settings > Booking (Guesty)** (or `define( 'BPT_GUESTY_LISTING_ID', '...' );` in `wp-config.php`). The same page has a checklist and a **Test Guesty** button.

What the theme does around the plugin:

* Creates the **/checkout/** page with the plugin's "Guesty Internal Checkout" template, once the plugin is active.
* Chooses the villa for the visitor (there is only one), so the search bar asks for dates and guests only.
* On the checkout, changes the terms line from "Villa Leelawadee" and its terms PDF to this villa and the Terms and Conditions page.
* Lets only dates, numbers and the listing code through from the checkout address into the page, because the plugin writes those values straight into its script.
* Loads jQuery early on these two pages, which the plugin needs.
* Shows a message instead of an empty bar if the plugin or Guesty is not ready.

Things to know about the plugin itself (not changed by the theme):

* The two addresses `/wp-json/guesty/v1/create-quote` and `/wp-json/guesty/v1/create-reservation` are open to everyone. A person who knows the address can ask for a quote or make a reservation without using the site. Consider adding a nonce, a captcha or a limit on how often they can be called.
* The checkout makes a **confirmed** reservation in Guesty when the guest presses Reserve.
* The plugin folder contains `guesty_debug.log`. It can be deleted.

## Bedrooms and the Guesty listings

The booking bar, the booking window and the booking summary offer two options, **6 bedrooms** and **5 bedrooms**, each with its Guesty listing. The list is in `inc/integration.php` (`bpt_listings()`); it is fixed in the theme, like on the Villa Mayavee site, and can be changed with the `bpt_listings` filter.

| Bedrooms | Listing ID |
|---|---|
| 6 | `6a4c8259fecc9a0012527a0b` |
| 5 | `6a4df654b26696001372962f` |

* The guest chooses the bedrooms first. Nothing is chosen at the start, and the dates stay closed until one is. Changing the bedrooms starts the dates again, because each listing has its own calendar.
* The listing goes with the guest everywhere: in the address of the booking summary (`&listing=`), in the request that is saved and emailed to reservations (**Bedrooms** and **Listing ID** lines), and in the title of the saved request.
* With the **Guesty Booking Engine plugin** active (and `GBP_INTERNAL_SECRET` in `wp-config.php`), the window reads from Guesty, for the chosen listing: the nights that are taken (struck out in the calendar), the minimum stay for each date, how many guests the villa sleeps, and the price with taxes on the summary. The theme asks Guesty through the plugin and keeps the answers for a few minutes. Prices are limited per visitor.
* Without the plugin the options still work: the listing is sent with the request, any future date can be picked, and the summary says the rate is confirmed by the reservations team.
* The request is **emailed**; no reservation is made in Guesty, unless the plugin below is active. The old booking engine's search bar on `/home-guesty/` shows the same two options.

### With the plugin "Baan Plearn Talee - Guesty Integration"

With that plugin active (and the Guesty keys in `wp-config.php`), the booking window gets its data from it, instead of from the old booking engine:

* **Availability**: booked nights, nights held by blocks, days closed to arrival or departure, and the minimum stay of the arrival day, for the chosen bedrooms.
* **The summary** shows the accommodation, **each tax and fee from Guesty** (for example Service Charge, VAT, local tax) and the total.
* **The guest details form** asks for first and last name, a phone with country code, and the Terms and Conditions. **Confirm request** makes the guest and the reservation in Guesty, emails reservations, and shows the confirmation code. The reservation is an inquiry unless the plugin is set to `reserved` or `confirmed`.
* Without the plugin, the booking works as described above (request emailed, rate confirmed by the team).


## The booking flow (what the buttons do)

**The booking window is on:** every Check availability button opens it. To show a "COMING SOON" notice on those buttons instead, set `live` to `false` with the `bpt_booking_config` filter. See "Comparing the two bookings" above to try the Guesty Booking Engine on its own page first and then switch it on.

1. Guest picks dates and guests in the booking bar on Home, or presses **Check availability** in the menu, which opens the booking window.
2. **Continue** opens the Booking Summary page with the dates in the address: `/booking-summary/?arrive=2026-12-20&depart=2026-12-27&guests=4`.
3. The summary shows the stay and the rate. **Continue to guest details** shows the form, and **Confirm request** sends it.
4. The request is saved as a private **Enquiry** (left menu) and emailed to the reservations address.

The theme never invents a price. The ticket says "Confirmed by our reservations team" unless a booking plugin supplies the rate through the `bpt_booking_config` filter (`live`, `calendarUrl`, `quoteUrl`).

## Menu

The header menu is built into `header.php`: Home, The villa (a dropdown that is not a link), Gallery, Reviews and Contact (the Contact us page). The dropdown lists the pages by their slugs. Page titles come from WordPress, so rename a page and the menu follows.

## Notes

* The Contact us page uses your Zoho Forms form exactly as it was, so enquiries keep reaching Zoho. Dates can be sent to it in the address: `/contact-us/?arrival=2026-12-20&departure=2026-12-27`. The form needs the jQuery date picker that WordPress already includes.
* The villa name shown in the hero, the booking window and the footer is set under **Appearance > Customize > Villa Details > Villa name**. The browser tab uses it too. Also set **Settings > General > Site Title** to the same name.
* The old The villa page and the video page are no longer used. You can delete them.
* Hero film: a link to an MP4 set in the Customizer (720p, no sound works best, and a few MB per minute keeps the page quick). `assets/video/hero-poster.jpg` is shown while it loads, and by itself if no film is set.
* Fonts: Prata, Jost and EB Garamond are part of the theme.

## Speed and search (PageSpeed Insights)

What the theme does by itself:

* Fonts (Prata and Jost) are part of the theme, so nothing waits on Google.
* The home film starts after the page has loaded, and not at all on "data saver", a very slow connection, or when the visitor has asked their device to reduce motion. Until then the still photograph shows.
* Photographs carry their width and height, home photographs have a smaller copy for phones, and the logos are small.
* Emoji, embed and block editor scripts and styles are not loaded where the theme does not use them.
* Every page has a search description. Edit one in the **Search description** box on the right of the page editor. The home page description is in **Appearance > Customize > Villa Details**. The home page also tells search engines it is a lodging business (name, address, phone, email).

Things only you can switch on:

1. **Settings > Reading > "Discourage search engines from indexing this site" must be unticked.** While it is ticked, WordPress tells Google not to list the site and PageSpeed shows "Page is blocked from indexing" (SEO stays around 58). Untick it when the site is ready to be found. If the message stays, check that your SEO plugin, your host or Cloudflare is not adding a "noindex" as well.
2. **Cache lifetime.** In Cloudflare, go to **Caching > Configuration > Browser Cache TTL** and set it to 1 month or longer. If your host runs Apache or LiteSpeed, add this to `.htaccess` as well:

```
<IfModule mod_expires.c>
ExpiresActive On
ExpiresByType image/jpeg "access plus 1 year"
ExpiresByType image/png "access plus 1 year"
ExpiresByType image/webp "access plus 1 year"
ExpiresByType video/mp4 "access plus 1 month"
ExpiresByType font/woff2 "access plus 1 year"
ExpiresByType text/css "access plus 1 year"
ExpiresByType application/javascript "access plus 1 year"
</IfModule>
```
3. **Compression and a page cache** (a caching plugin or your host's cache) make the home page arrive faster. Turn on Brotli or Gzip in Cloudflare (Speed > Optimization).
4. The home film is the biggest file the visitor can download. Keep it as small as you can (the 7 MB version works well on phones).
