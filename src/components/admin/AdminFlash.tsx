import { readFlash } from "@/lib/flash";
import { FlashClear } from "./FlashClear";

/**
 * Shows the success / error message set by the last admin action
 * (same markup as the Blade `session('success')` alerts). Place it where the old page had them.
 */
export async function AdminFlash({ className = "text-center mt-3" }: { className?: string }) {
  const message = await readFlash();
  if (!message) return null;

  return (
    <>
      <div
        className={`alert alert-${message.type === "success" ? "success" : "danger"} alert-dismissible fade show ${className}`}
        role="alert"
      >
        {message.message}
        <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close" />
      </div>
      <FlashClear />
    </>
  );
}
