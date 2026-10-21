import { notFound } from "next/navigation";
import { getDevelopment, getFloorPlan } from "@/lib/admin/developments";
import { toId } from "@/lib/form";
import { FloorPlanForm } from "../../FloorPlanForm";

export default async function EditFloorPlan({ params }: { params: Promise<{ id: string; planId: string }> }) {
  const p = await params;
  const id = toId(p.id);
  const planId = toId(p.planId);
  const development = id ? await getDevelopment(id) : null;
  if (!development) notFound();
  const floorPlan = planId ? await getFloorPlan(planId) : null;
  if (!floorPlan) notFound();

  return (
    <div className="container-fluid">
      <h3 className="mb-1">Edit Floor Plan</h3>
      <p className="text-muted mb-4">{development.title}</p>
      <FloorPlanForm developmentId={development.id} floorPlan={floorPlan} />
    </div>
  );
}
