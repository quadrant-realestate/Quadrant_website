import { notFound } from "next/navigation";
import { getDevelopment } from "@/lib/admin/developments";
import { toId } from "@/lib/form";
import { FloorPlanForm } from "../FloorPlanForm";

export default async function CreateFloorPlan({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const development = id ? await getDevelopment(id) : null;
  if (!development) notFound();

  return (
    <div className="container-fluid">
      <h3 className="mb-1">Add Floor Plan</h3>
      <p className="text-muted mb-4">{development.title}</p>
      <FloorPlanForm developmentId={development.id} />
    </div>
  );
}
