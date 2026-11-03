"use client";

import { useState } from "react";

// "Talk to an expert" bar in the bottom corner; the chevron folds it down to a round WhatsApp button.
export function FloatingContact({ whatsapp, phone }: { whatsapp: string; phone: string }) {
  const [open, setOpen] = useState(true);
  const waHref = `https://api.whatsapp.com/send/?phone=${whatsapp}&text&type=phone_number`;
  const telHref = `tel:${phone.replace(/\s/g, "")}`;

  if (!open) {
    return (
      <button type="button" className="qp-fc-mini" onClick={() => setOpen(true)} aria-label="Talk to an expert">
        <i className="fa-brands fa-whatsapp" />
      </button>
    );
  }

  return (
    <div className="qp-fc">
      <img className="qp-fc-avatar" src="/public/assets/images/expert_avatar.png" alt="Quadrant Properties" />
      <div className="qp-fc-text">
        <strong>Talk to an expert</strong>
        <a href={telHref}>{phone}</a>
      </div>
      <a className="qp-fc-wa" href={waHref} target="_blank">
        <i className="fa-brands fa-whatsapp" /> WhatsApp
      </a>
      <a className="qp-fc-call" href={telHref} aria-label={`Call us on ${phone}`}>
        <i className="fa-solid fa-phone" />
        <span className="qp-fc-number">
          {phone}
          <small>Click to call</small>
        </span>
      </a>
      <button type="button" className="qp-fc-hide" onClick={() => setOpen(false)} aria-label="Minimise">
        <i className="fa-solid fa-chevron-down" />
      </button>
    </div>
  );
}
