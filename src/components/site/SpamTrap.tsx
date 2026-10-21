/**
 * Bot trap: humans never see or fill the "website" field; bots usually do.
 * form_ts lets the server reject forms submitted faster than a human could.
 *
 * Works in server and client components. Get `ts` on the server with
 * `formTimestamp()` from "@/lib/spam" and pass it down to client forms as a prop.
 */
export function SpamTrap({ ts, id = "f" }: { ts: string; id?: string }) {
  return (
    <>
      <div
        style={{ position: "absolute", left: -10000, top: "auto", width: 1, height: 1, overflow: "hidden" }}
        aria-hidden="true"
      >
        <label htmlFor={`website_${id}`}>Leave this field empty</label>
        <input type="text" name="website" id={`website_${id}`} defaultValue="" tabIndex={-1} autoComplete="off" />
      </div>
      <input type="hidden" name="form_ts" value={ts} />
    </>
  );
}
