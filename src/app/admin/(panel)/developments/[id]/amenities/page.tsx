import Link from "next/link";
import { notFound } from "next/navigation";
import { updateDevelopmentAmenities } from "@/actions/admin/development-amenities";
import { allAmenities, getDevelopment, selectedAmenityIds, type AmenityOption } from "@/lib/admin/developments";
import { toId } from "@/lib/form";
import { PlainFlash } from "../../../_projects/PlainFlash";

export default async function DevelopmentAmenities({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const development = id ? await getDevelopment(id) : null;
  if (!development) notFound();

  const [amenities, selected] = await Promise.all([allAmenities(), selectedAmenityIds(development.id)]);

  // $allAmenities->groupBy('category'), in first-seen order
  const grouped = new Map<string, AmenityOption[]>();
  for (const amenity of amenities) {
    const key = amenity.category ?? "";
    grouped.set(key, [...(grouped.get(key) ?? []), amenity]);
  }

  return (
    <div className="container-fluid">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h3 className="mb-0">Amenities</h3>
          <p className="text-muted mb-0">{development.title}</p>
        </div>
        <Link href={`/admin/developments/${development.id}/edit`} className="btn btn-outline-secondary">
          &larr; Back to Development
        </Link>
      </div>

      <PlainFlash />

      <div className="card">
        <div className="card-body">
          <form action={updateDevelopmentAmenities.bind(null, development.id)}>
            {grouped.size === 0 ? (
              <p className="text-muted">
                No amenities exist yet. Add some under <Link href="/admin/amenities/create">Amenities</Link> first.
              </p>
            ) : (
              [...grouped.entries()].map(([category, items]) => (
                <div key={category}>
                  <h6 className="mt-3 mb-2">{category || "General"}</h6>
                  <div className="row mb-3">
                    {items.map((amenity) => (
                      <div className="col-md-3 col-6 mb-2" key={amenity.id}>
                        <div className="form-check">
                          <input
                            className="form-check-input"
                            type="checkbox"
                            name="amenity_ids[]"
                            value={amenity.id}
                            id={`amenity_${amenity.id}`}
                            defaultChecked={selected.includes(amenity.id)}
                          />
                          <label className="form-check-label" htmlFor={`amenity_${amenity.id}`}>
                            {amenity.name}
                          </label>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              ))
            )}

            <button type="submit" className="btn btn-primary mt-3">
              Save Amenities
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}
