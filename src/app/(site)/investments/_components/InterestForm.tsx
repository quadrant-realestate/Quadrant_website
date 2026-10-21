"use client";

import { useActionState } from "react";
import { submitInquiry, type FormState } from "@/actions/inquiries";
import { SpamTrap } from "@/components/site/SpamTrap";

/**
 * Register-interest form (posted to inquiry.submit in Laravel). Renders the success alert above the form
 * where the Blade session('interest_success') block was.
 */
export function InterestForm({ ts, source, notes }: { ts: string; source: string; notes?: string }) {
  const [state, formAction] = useActionState<FormState, FormData>(submitInquiry, {});
  const v = state.values ?? {};

  return (
    <>
      {state.success && <div className="alert alert-success">{state.success}</div>}

      <form id="contactForm" action={formAction}>
        <SpamTrap ts={ts} />
        <input type="hidden" name="inquiry_type" value="general" />
        <input type="hidden" name="source" value={source} />

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
        {notes !== undefined && (
          <div className="form-group">
            <label>Message</label>
            <textarea
              className="form-control"
              name="notes"
              placeholder="Enter your message..."
              defaultValue={v.notes ?? notes}
            />
          </div>
        )}
        <button type="submit" className="green-btn submit-btn">
          Submit
        </button>
      </form>
    </>
  );
}
