import { redirect } from "next/navigation";
import { requireAdmin } from "@/lib/auth";
import { flash } from "@/lib/flash";

// Pages can't set cookies while rendering, so an edit/show page whose record doesn't exist redirects here;
// this sets the same flash message the Laravel controller used and continues to the list page.
const TARGETS: Record<string, { message: string; to: string }> = {
  property: { message: "Property not found.", to: "/admin/properties" },
  "property-type": { message: "Property type not found.", to: "/admin/property-types" },
  amenity: { message: "Amenity not found.", to: "/admin/amenities" },
  inquiry: { message: "Inquiry not found.", to: "/admin/inquiries" },
};

export async function GET(request: Request) {
  await requireAdmin();
  const key = new URL(request.url).searchParams.get("m") ?? "";
  const target = TARGETS[key];
  if (!target) redirect("/admin");
  await flash("error", target.message);
  redirect(target.to);
}
