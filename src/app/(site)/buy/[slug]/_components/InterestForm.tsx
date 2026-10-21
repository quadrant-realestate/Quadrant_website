"use client";

import { useActionState } from "react";
import { submitInquiry, type FormState } from "@/actions/inquiries";
import { SpamTrap } from "@/components/site/SpamTrap";

/** 9. Register-interest form (posted to inquiry.submit in Laravel). */
export function InterestForm({ developmentId, title, ts }: { developmentId: number; title: string; ts: string }) {
  const [state, action] = useActionState<FormState, FormData>(submitInquiry, {});
  const v = state.values ?? {};

  return (
    <div className="qp-pd-form-card">
      <h3>Register Your Interest</h3>

      {state.success && <div className="alert alert-success">{state.success}</div>}

      <form action={action}>
        <SpamTrap ts={ts} />
        <input type="hidden" name="development_id" value={developmentId} />
        <input type="hidden" name="inquiry_type" value="general" />
        <input type="hidden" name="source" value="project_detail" />

        <div className="form-group">
          <label>Name</label>
          <input className="form-control" name="name" type="text" required defaultValue={v.name} />
        </div>
        <div className="form-group">
          <label>Email</label>
          <input className="form-control" name="email" type="email" required defaultValue={v.email} />
        </div>
        <div className="form-group">
          <label>Phone</label>
          <input className="form-control" name="phone" type="text" required defaultValue={v.phone} />
        </div>
        <div className="form-group">
          <label>Message</label>
          <textarea className="form-control" name="notes" rows={3} defaultValue={v.notes ?? `Inquiry about: ${title}`} />
        </div>

        <label className="qp-pd-checkbox">
          <input type="checkbox" name="request_brochure" value="1" defaultChecked={v.request_brochure === "1"} />
          Send me the brochure
        </label>

        <button type="submit" className="qp-pd-submit">
          Submit
        </button>
      </form>
    </div>
  );
}
