/*!Main Css v1.54 by @Prem*/

window.addEventListener('load', () => {
  document.getElementById('preloader').style.display = 'none';
//   document.getElementById('content').style.display = 'block';
});

// Wishlist Toggle
document.querySelectorAll('.Wishlist').forEach(wishlist => {
  wishlist.addEventListener('click', function () {
    const productSlug = this.dataset.url;
    const userId = this.dataset.auth;
    const productId = this.dataset.id;
    const productType = this.dataset.type;

    if (!userId) return alert('You must be logged in to add to the wishlist.');

    fetch('/wishlist', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ product_slug: productSlug, product_id: productId, product_type: productType })
    })
    .then(res => res.json())
    .then(data => {
      const heartOutlined = this.querySelector('.heart-o-icon');
      const heartFilled = this.querySelector('.heart-icon');
      const showSwal = (msg) => {
        let timerInterval;
        Swal.fire({
          title: msg,
          timer: 2000,
          timerProgressBar: true,
          didOpen: () => {
            Swal.showLoading();
            timerInterval = setInterval(() => {
              Swal.getPopup().querySelector("b").textContent = `${Swal.getTimerLeft()}`;
            }, 100);
          },
          willClose: () => clearInterval(timerInterval)
        });
      };

      if (data.message.includes('added')) {
        showSwal("Successfully added to wishlist");
        heartOutlined?.style.setProperty('display', 'none', 'important');
        heartFilled?.style.setProperty('display', 'inline', 'important');
      } else if (data.message.includes('removed')) {
        showSwal("Successfully removed from wishlist");
        heartOutlined?.style.setProperty('display', 'inline', 'important');
        heartFilled?.style.setProperty('display', 'none', 'important');
      } else {
        console.warn('Unexpected message:', data.message);
      }
    });
  });
});

// Add alt to all images if missing
(() => {
  document.querySelectorAll('img').forEach(img => {
    if (!img.hasAttribute('alt') || !img.alt.trim()) img.setAttribute('alt', 'morgan');
  });
  console.log('Alt attribute check completed.');
})();

// Owl Carousel Initializations (simplified batch init)
const carousels = [
  { id: "#instructor-slider", loop: false, items: 4 },
  { id: "#team-slider", loop: false, items: 4 },
  { id: "#single_slider", loop: false, items: 4 },
  { id: "#NewDevelopment", loop: true, items: 4 },
  { id: "#private-office", loop: true, items: 4 },
  { id: "#Brands", loop: true, items: 5, autoplay: true },
  { id: "#Top-Destinations", loop: true, items: 2, dots: true },
  { id: "#Testimonials", loop: true, items: 3, dots: true },
  { id: "#region-slider", loop: true, items: 3.4 },
  { id: "#team-slider", loop: true, items: 3.4 },
  { id: "#testimonials", loop: true, items: 1, dots: true },
];

carousels.forEach(({ id, loop, items, dots = false, autoplay = false }) => {
  const el = $(id);
  if (el.length) {
    el.owlCarousel({
      margin: 20,
      nav: true,
      loop,
      dots,
      autoplay,
      autoHeight: true,
      responsive: {
        0: { items: Math.min(items, 1.5), margin: 10, dots },
        600: { items: Math.min(items, 2.5), dots },
        1000: { items, dots }
      }
    });
  }
});

// Sidebar / Filter Toggle
(($) => {
  $(".nav-btn.nav-slider").click(() => $(".overlay").show() && $(".sidebar").toggleClass("open"));
  $(".overlay, .overlay-body").click(() => {
    $(".sidebar").removeClass("open");
    $(".overlay, .overlay-body").hide();
  });
  $(".filterButoon").click(() => $(".overlay-body").show() && $(".Products-Sidebar").toggleClass("open-filter"));
})(jQuery);

// Back to Top Button
$(window).on('scroll', () => {
  $('#back2Top').fadeToggle($(window).scrollTop() > 600);
});

// LightGallery Init
// $(document).ready(() => {
//  lightGallery(document.getElementById('aniimated-thumbnials'), { thumbnail: true });
// });

$(document).ready(() => {
  if (typeof lightGallery === 'function') {
    const galleryEl = document.getElementById('aniimated-thumbnials');
    if (galleryEl) {
      lightGallery(galleryEl, { thumbnail: true });
    }
  } else {
    console.warn('lightGallery is not loaded.');
  }
});



// Sticky Header
$(window).scroll(() => {
  $(".header").toggleClass("header-fixed", $(window).scrollTop() >= 50);
});

// Price Range Slider
document.addEventListener("DOMContentLoaded", () => {
  const rangeInputs = document.querySelectorAll(".range-input input");
  const priceInputs = document.querySelectorAll(".price-input input");
  const range = document.querySelector(".slider .progress");
  const gap = 1000;

  const syncRange = (min, max) => {
    priceInputs[0].value = min;
    priceInputs[1].value = max;
    range.style.left = `${(min / rangeInputs[0].max) * 100}%`;
    range.style.right = `${100 - (max / rangeInputs[1].max) * 100}%`;
  };

  priceInputs.forEach((input, i) => {
    input.addEventListener("input", () => {
      const [min, max] = [+priceInputs[0].value, +priceInputs[1].value];
      if (max - min >= gap && max <= rangeInputs[1].max) {
        rangeInputs[i].value = i === 0 ? min : max;
        syncRange(min, max);
      }
    });
  });

  rangeInputs.forEach(input => {
    input.addEventListener("input", () => {
      let [min, max] = [+rangeInputs[0].value, +rangeInputs[1].value];
      if (max - min < gap) {
        input.className.includes("range-min") ? rangeInputs[0].value = max - gap : rangeInputs[1].value = min + gap;
      } else syncRange(min, max);
    });
  });
});

// Filter Sidebar Open/Close
function openNav() {
  document.getElementById("filters-sidebar").style.cssText = "width:360px;right:0px;";
  document.getElementById("filter-overlay").style.display = "block";
}
function closeNav() {
  document.getElementById("filters-sidebar").style.cssText = "width:0;right:-400px;";
  document.getElementById("filter-overlay").style.display = "none";
}
document.getElementById("filter-overlay")?.addEventListener("click", closeNav);

// Document Download Handler
function checkAndDownload(url, type, e) {
  if (!url) {
    $('#alertTitle').html('Document Not Found');
    $('#alertContent').html('Please connect with the admin regarding this document');
    return $('#documentNotFound').modal('show');
  }
  const link = document.createElement('a');
  link.href = url;
  link.download = '';
  link.click();
}

// Show More Toggle
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.parent-section_old').forEach(section => {
    const btn = section.querySelector('.link-btn');
    const content = section.querySelector('.show_more_content');
    const maxHeight = 300;
    content.style.maxHeight = `${maxHeight}px`;
    content.style.overflow = 'hidden';

    btn.addEventListener('click', () => {
      const isCollapsed = content.style.maxHeight === `${maxHeight}px`;
      content.style.maxHeight = isCollapsed ? '20000px' : `${maxHeight}px`;
      btn.textContent = isCollapsed ? 'Show Less' : 'Show More';
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.parent-section').forEach(section => {
    const btn = section.querySelector('.link-btn');
    const content = section.querySelector('.show_more_content');

    if (!btn || !content) return; // Skip if elements not found

    // Optional: get accurate line height from a <p> inside the block
    const paragraph = content.querySelector('p');
    const lineHeight = paragraph
      ? parseFloat(window.getComputedStyle(paragraph).lineHeight)
      : 22; // default fallback

    const linesToShow = 18;
    const collapsedHeight = lineHeight * linesToShow;

    content.style.maxHeight = `${collapsedHeight}px`;
    content.style.overflow = 'hidden';
    content.style.transition = 'max-height 0.5s ease-in-out';

    btn.addEventListener('click', () => {
      const isCollapsed = content.style.maxHeight === `${collapsedHeight}px`;
      content.style.maxHeight = isCollapsed
        ? `${content.scrollHeight}px`
        : `${collapsedHeight}px`;
      btn.textContent = isCollapsed ? 'Show Less' : 'Show More';
    });
  });
});



window.addEventListener('DOMContentLoaded', () => {
    const observer = new MutationObserver(() => {
        const closeBtn = document.querySelector('.lc_text-widget_prompt--prompt-close');
        if (closeBtn && !closeBtn.hasAttribute('aria-label')) {
            closeBtn.setAttribute('aria-label', 'Close chat prompt');
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
});

