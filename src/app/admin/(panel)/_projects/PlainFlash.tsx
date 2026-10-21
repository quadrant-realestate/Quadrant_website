import { FlashClear } from "@/components/admin/FlashClear";
import { readFlash } from "@/lib/flash";

/**
 * `@if(session('success')) <div class="alert alert-success">...</div> @endif` — the plain (non-dismissible)
 * success alert used by the floor plan and development amenity pages.
 */
export async function PlainFlash() {
  const message = await readFlash();
  if (!message || message.type !== "success") return null;
  return (
    <>
      <div className="alert alert-success">{message.message}</div>
      <FlashClear />
    </>
  );
}
