"use client";

import Link from "next/link";
import { useActionState } from "react";
import { createFloorPlan, updateFloorPlan } from "@/actions/admin/development-floor-plans";
import type { AdminFormState } from "@/lib/admin/form-state";
import { dec, old } from "@/lib/admin/projects-format";
import { asset } from "@/lib/url";

type FloorPlan = { id: number; unit_type: string | null; size_sqft: number | null; image: string | null; pdf_file: string | null };

/** admin/development_floor_plans/create + edit (with the shared _form partial). */
export function FloorPlanForm({ developmentId, floorPlan }: { developmentId: number; floorPlan?: FloorPlan }) {
  const action = floorPlan ? updateFloorPlan.bind(null, developmentId, floorPlan.id) : createFloorPlan.bind(null, developmentId);
  const [state, formAction] = useActionState<AdminFormState, FormData>(action, {});
  const errors = Object.values(state.errors ?? {});

  return (
    <>
      {errors.length > 0 && (
        <div className="alert alert-danger">
          <ul className="mb-0">
            {errors.map((error) => (
              <li key={error}>{error}</li>
            ))}
          </ul>
        </div>
      )}

      <div className="card">
        <div className="card-body">
          <form action={formAction} key={state.n ?? 0}>
            <div className="form-group">
              <label>
                Unit Type <small className="text-muted">(e.g. &quot;1 Bedroom&quot;, &quot;2 Bedroom&quot;, &quot;Penthouse&quot;)</small>
              </label>
              <input type="text" name="unit_type" className="form-control" required defaultValue={old(state, "unit_type", floorPlan?.unit_type)} />
            </div>

            <div className="form-group">
              <label>
                Size (sq ft) <small className="text-muted">(e.g. &quot;750 sq ft&quot;)</small>
              </label>
              <input type="text" name="size_sqft" className="form-control" defaultValue={old(state, "size_sqft", dec(floorPlan?.size_sqft))} />
            </div>

            <div className="form-group">
              <label>Floor Plan Image</label>
              <input type="file" name="image" className="form-control" />
              {floorPlan?.image && <img src={asset(floorPlan.image)} height={80} className="mt-2 d-block" alt="" />}
            </div>

            <div className="form-group">
              <label>Downloadable PDF</label>
              <input type="file" name="pdf_file" className="form-control" accept="application/pdf" />
              {floorPlan?.pdf_file && (
                <a href={asset(floorPlan.pdf_file)} target="_blank" className="d-block mt-2">
                  View current PDF
                </a>
              )}
            </div>
            <button type="submit" className="btn btn-primary">
              {floorPlan ? "Update Floor Plan" : "Add Floor Plan"}
            </button>{" "}
            <Link href={`/admin/developments/${developmentId}/floor-plans`} className="btn btn-secondary">
              Cancel
            </Link>
          </form>
        </div>
      </div>
    </>
  );
}
