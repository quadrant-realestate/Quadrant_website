"use client";

import { useActionState } from "react";
import { submitInquiry, type FormState } from "@/actions/inquiries";
import { SpamTrap } from "@/components/site/SpamTrap";

/** "Register Your Interest" sidebar form (posts to inquiry.submit in the old site). */
export function InterestForm({
  ts,
  propertyId,
  defaultNotes,
}: {
  ts: string;
  propertyId: number;
  defaultNotes: string;
}) {
  const [state, action] = useActionState<FormState, FormData>(submitInquiry, {});
  // The Blade view didn't show validation errors or old input; keep what was typed after a failed submit.
  const v = state.values ?? {};

  return (
    <div className="list-from">
      <h3 className="mb-2">Register Your Interest</h3>

      {state.success && <div className="alert alert-success">{state.success}</div>}

      <form id="contactForm" action={action} key={state.success ? "sent" : "form"}>
        <SpamTrap ts={ts} />
        <input type="hidden" name="property_id" value={propertyId} />
        <input type="hidden" name="inquiry_type" value="buy" />
        <input type="hidden" name="source" value="property_detail" />

        <div className="form-group">
          <label>Full Name</label>
          <input className="form-control" name="name" type="text" placeholder="John Doe" required defaultValue={v.name} />
        </div>
        <div className="form-group">
          <label>Email</label>
          <input
            className="form-control"
            name="email"
            type="email"
            placeholder="example@gmail.com"
            required
            defaultValue={v.email}
          />
        </div>
        <div className="form-group">
          <label>Contact Number</label>
          <input
            className="form-control"
            name="phone"
            type="text"
            placeholder="+971 50 000 0000"
            required
            defaultValue={v.phone}
          />
        </div>
        <div className="form-group">
          <label>Message</label>
          <textarea
            className="form-control"
            name="notes"
            placeholder="Enter your message..."
            defaultValue={v.notes ?? defaultNotes}
          />
        </div>
        <button type="submit" className="green-btn submit-btn">
          Submit
        </button>
      </form>
    </div>
  );
}
