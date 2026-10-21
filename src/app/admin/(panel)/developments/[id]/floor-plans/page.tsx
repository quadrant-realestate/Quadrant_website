import Link from "next/link";
import { notFound } from "next/navigation";
import { deleteFloorPlan } from "@/actions/admin/development-floor-plans";
import { ConfirmButton } from "@/components/admin/ConfirmButton";
import { floorPlans, getDevelopment } from "@/lib/admin/developments";
import { dec } from "@/lib/admin/projects-format";
import { toId } from "@/lib/form";
import { asset } from "@/lib/url";
import { PlainFlash } from "../../../_projects/PlainFlash";

export default async function FloorPlansIndex({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const development = id ? await getDevelopment(id) : null;
  if (!development) notFound();
  const plans = await floorPlans(development.id);

  return (
    <div className="container-fluid">
      <div className="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h3 className="mb-0">Floor Plans</h3>
          <p className="text-muted mb-0">{development.title}</p>
        </div>
        <div>
          <Link href={`/admin/developments/${development.id}/edit`} className="btn btn-outline-secondary">
            &larr; Back to Development
          </Link>{" "}
          <Link href={`/admin/developments/${development.id}/floor-plans/create`} className="btn btn-primary">
            + Add Floor Plan
          </Link>
        </div>
      </div>

      <PlainFlash />

      <div className="card">
        <div className="card-body">
          <table className="table table-bordered">
            <thead>
              <tr>
                <th style={{ width: 100 }}>Image</th>
                <th>Unit Type</th>
                <th>Size</th>
                <th>PDF</th>
                <th>Order</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {plans.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center">
                    No floor plans added yet.
                  </td>
                </tr>
              ) : (
                plans.map((plan) => (
                  <tr key={plan.id}>
                    <td>{plan.image ? <img src={asset(plan.image)} height={50} alt="" /> : <span className="text-muted">—</span>}</td>
                    <td>{plan.unit_type}</td>
                    <td>{plan.size_sqft !== null ? dec(plan.size_sqft) : "—"}</td>
                    <td>
                      {plan.pdf_file ? (
                        <a href={asset(plan.pdf_file)} target="_blank">
                          View PDF
                        </a>
                      ) : (
                        <span className="text-muted">—</span>
                      )}
                    </td>
                    <td>{plan.sort_order}</td>
                    <td>
                      <Link href={`/admin/developments/${development.id}/floor-plans/${plan.id}/edit`} className="btn btn-sm btn-info">
                        Edit
                      </Link>{" "}
                      <form action={deleteFloorPlan.bind(null, development.id, plan.id)} style={{ display: "inline-block" }}>
                        <ConfirmButton className="btn btn-sm btn-danger" message="Delete this floor plan?">
                          Delete
                        </ConfirmButton>
                      </form>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
