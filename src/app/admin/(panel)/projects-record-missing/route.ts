import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { flash } from "@/lib/flash";

// Edit pages can't set cookies while rendering, so when the record doesn't exist they redirect here;
// this sets the flash message the Laravel controller used and continues to the list page
// (developments / investments / branded residences / communities / recognitions).
const TARGETS: Record<string, { message: string; to: string }> = {
  development: { message: "Development not found.", to: "/admin/developments" },
  investment: { message: "Investment not found.", to: "/admin/investments" },
  "branded-residence": { message: "Branded residence not found.", to: "/admin/branded-residences" },
  community: { message: "Community not found.", to: "/admin/communities" },
  recognition: { message: "Recognition not found.", to: "/admin/recognitions" },
};

export async function GET(request: Request) {
  await requireAdmin();
  const key = new URL(request.url).searchParams.get("m") ?? "";
  const target = TARGETS[key];
  if (!target) redirect("/admin");
  await flash("error", target.message);
  redirect(target.to);
}
