"use client";

import { useActionState } from "react";
import { submitPartner, type FormState } from "@/actions/inquiries";
import { useTrackLead } from "@/components/site/MetaPixel";
import { SpamTrap } from "@/components/site/SpamTrap";

const PARTNER_TYPES = ["Individual", "Licensed agent", "Agency"];
const HEARD_FROM = ["Google", "Instagram", "LinkedIn", "Referral", "Other"];

/** Application form on the Become a Partner page (posts to submitPartner). */
export function PartnerForm({ ts }: { ts: string }) {
  const [state, action] = useActionState<FormState, FormData>(submitPartner, {});
  useTrackLead(state, "partner_page");
  // old('field') — previous input after a validation error
  const old = (key: string) => state.values?.[key] ?? "";
  const hasErrors = !!state.errors && Object.keys(state.errors).length > 0;

  return (
    <>
      {state.success && <div className="qp-partner-success">{state.success}</div>}

      {hasErrors && (
        <div className="alert alert-danger" style={{ marginBottom: 18, fontSize: 13 }}>
          Please fill in all required fields correctly.
        </div>
      )}

      {/* key: remount with the old values after each failed submission */}
      <form action={action} key={state.values ? JSON.stringify(state.values) : "empty"}>
        <SpamTrap ts={ts} />

        {/* Name */}
        <div className="qp-sf-group">
          <label htmlFor="partner-name">Name *</label>
          <input
            type="text"
            id="partner-name"
            name="name"
            required
            placeholder="Your full name"
            defaultValue={old("name")}
          />
        </div>

        {/* Email + Phone */}
        <div className="qp-sf-row">
          <div className="qp-sf-group">
            <label htmlFor="partner-email">Email *</label>
            <input
              type="email"
              id="partner-email"
              name="email"
              required
              placeholder="your@email.com"
              defaultValue={old("email")}
            />
          </div>
          <div className="qp-sf-group">
            <label htmlFor="partner-phone">Phone / WhatsApp *</label>
            <input
              type="text"
              id="partner-phone"
              name="phone"
              required
              placeholder="+971 50 000 0000"
              defaultValue={old("phone")}
            />
          </div>
        </div>

        {/* Partner type + Company */}
        <div className="qp-sf-row">
          <div className="qp-sf-group">
            <label htmlFor="partner-type">I am a *</label>
            <select id="partner-type" name="partner_type" required defaultValue={old("partner_type")}>
              <option value="">Select...</option>
              {PARTNER_TYPES.map((type) => (
                <option value={type} key={type}>
                  {type}
                </option>
              ))}
            </select>
          </div>
          <div className="qp-sf-group">
            <label htmlFor="partner-company">Company</label>
            <input
              type="text"
              id="partner-company"
              name="company"
              placeholder="Company or agency name"
              defaultValue={old("company")}
            />
          </div>
        </div>

        {/* How they heard about us */}
        <div className="qp-sf-group">
          <label htmlFor="partner-heard">How did you hear about us?</label>
          <select id="partner-heard" name="heard_from" defaultValue={old("heard_from")}>
            <option value="">Select...</option>
            {HEARD_FROM.map((source) => (
              <option value={source} key={source}>
                {source}
              </option>
            ))}
          </select>
        </div>

        {/* Notes */}
        <div className="qp-sf-group">
          <label htmlFor="partner-notes">Anything else?</label>
          <textarea
            id="partner-notes"
            name="notes"
            placeholder="Tell us about your network or the clients you work with..."
            defaultValue={old("notes")}
          ></textarea>
        </div>

        <button type="submit" className="qp-sf-submit">
          Become a Partner
        </button>
      </form>
    </>
  );
}
