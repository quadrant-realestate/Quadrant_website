"use client";

import { useState } from "react";

// "Talk to an expert" bar in the bottom corner; the chevron folds it down to a round WhatsApp button.
export function FloatingContact({ whatsapp, phone }: { whatsapp: string; phone: string }) {
  const [open, setOpen] = useState(true);
  const waHref = `https://api.whatsapp.com/send/?phone=${whatsapp}&text&type=phone_number`;

  if (!open) {
    return (
      <button type="button" className="qp-fc-mini" onClick={() => setOpen(true)} aria-label="Talk to an expert">
        <i className="fa-brands fa-whatsapp" />
      </button>
    );
  }

  return (
    <div className="qp-fc">
      <img className="qp-fc-avatar" src="/public/assets/images/Faisal_avatar.webp" alt="Faisal Farooqui" />
      <div className="qp-fc-text">
        <strong>Talk to an expert</strong>
        <span>Our team, on WhatsApp or the phone</span>
      </div>
      <a className="qp-fc-wa" href={waHref} target="_blank">
        <i className="fa-brands fa-whatsapp" /> WhatsApp
      </a>
      <a className="qp-fc-call" href={`tel:${phone}`} aria-label="Call us">
        <i className="fa-solid fa-phone" />
      </a>
      <button type="button" className="qp-fc-hide" onClick={() => setOpen(false)} aria-label="Minimise">
        <i className="fa-solid fa-chevron-down" />
      </button>
    </div>
  );
}
