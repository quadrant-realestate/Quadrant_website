"use client";

import { useActionState } from "react";
import { submitContact, type FormState } from "@/actions/inquiries";
import { SpamTrap } from "@/components/site/SpamTrap";

const INTERESTS: [value: string, label: string][] = [
  ["off-plan", "Off-Plan"],
  ["luxury", "Luxury Acquisition"],
  ["investment", "Investment"],
  ["selling", "Selling"],
  ["general", "General"],
];

/** Enquiry form on the contact page (posts to submitContact, Laravel's contact.submit). */
export function ContactForm({ ts }: { ts: string }) {
  const [state, action] = useActionState<FormState, FormData>(submitContact, {});
  // old('field') — previous input after a validation error
  const old = (key: string) => state.values?.[key] ?? "";
  const hasErrors = !!state.errors && Object.keys(state.errors).length > 0;

  return (
    <>
      {state.success && <div className="qp-success-msg">{state.success}</div>}

      {hasErrors && (
        <div className="alert alert-danger" style={{ marginBottom: 18, fontSize: 13 }}>
          Please fill in all required fields correctly.
        </div>
      )}

      {/* key: remount with the old values after each failed submission */}
      <form action={action} key={state.values ? JSON.stringify(state.values) : "empty"}>
        <SpamTrap ts={ts} />

        {/* Name */}
        <div className="qp-cf-group">
          <label>Name *</label>
          <input type="text" name="name" required placeholder="Your full name" defaultValue={old("name")} />
        </div>

        {/* Email + Phone */}
        <div className="qp-cf-row">
          <div className="qp-cf-group">
            <label>Email *</label>
            <input type="email" name="email" required placeholder="your@email.com" defaultValue={old("email")} />
          </div>
          <div className="qp-cf-group">
            <label>Phone *</label>
            <input type="text" name="phone" required placeholder="+971 50 000 0000" defaultValue={old("phone")} />
          </div>
        </div>

        {/* Interest Dropdown */}
        <div className="qp-cf-group">
          <label>I am interested in</label>
          <select name="interest" defaultValue={old("interest")}>
            <option value="">Select...</option>
            {INTERESTS.map(([value, label]) => (
              <option value={value} key={value}>
                {label}
              </option>
            ))}
          </select>
        </div>

        {/* Message */}
        <div className="qp-cf-group">
          <label>Message</label>
          <textarea
            name="message"
            placeholder="Tell us what you're looking for..."
            defaultValue={old("message")}
          ></textarea>
        </div>

        <button type="submit" className="qp-contact-submit">
          Send Message
        </button>
      </form>
    </>
  );
}
